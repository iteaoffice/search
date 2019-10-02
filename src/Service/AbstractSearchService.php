<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Bart van Eijck <bart.van.eijck@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace Search\Service;

use DateInterval;
use DateTime;
use RuntimeException;
use Solarium\Client;
use Solarium\Core\Query\AbstractQuery;
use Solarium\Exception\HttpException;
use Solarium\QueryType\Select\Query\Query;
use Solarium\QueryType\Select\Result\Result as SelectResult;
use Solarium\QueryType\Update\Result as UpdateResult;
use stdClass;
use Throwable;
use Zend\Json\Json;
use function count;
use function defined;
use function get_class;
use function method_exists;
use function preg_match;
use function preg_match_all;
use function sprintf;
use function str_replace;
use function strtolower;
use function substr;
use function sys_get_temp_dir;
use function time;
use function unlink;

abstract class AbstractSearchService implements SearchServiceInterface
{
    public const DATE_SOLR = 'Y-m-d\TH:i:s\Z';
    public const QUERY_TERM_BOOST = 30;
    public const SOLR_CONNECTION = 'default';
    /**
     * @var Query
     */
    protected $query;
    /**
     * @var Client
     */
    private $solrClient;
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public static function parseQuery(
        string $searchTerm,
        array $matchFields,
        string $operator = Query::QUERY_OPERATOR_OR
    ): string {
        $searchTerm = strtolower($searchTerm);
        // ((?:\w+-)*\w+) matches both regular words and words-with-hypens
        preg_match_all('/-?"[^"]+"|\+|-?((?:\w+-)*\w+)/', $searchTerm, $searchParts);

        if (isset($searchParts[0])) {
            $searchParts = $searchParts[0];
        }

        $query = '';
        $fieldIteration = 0;
        $fieldCount = count($matchFields);
        $searchPartCount = count($searchParts);

        $parseTerm = static function (string $field, string $searchTerm): string {
            // Exclude term
            if (substr($searchTerm, 0, 1) === '-') {
                return '-' . $field . ':' . substr($searchTerm, 1);
            } elseif (empty($searchTerm)) { // Empty search
                return $field . ':*';
            } else { // Regular term
                return $field . ':' . $searchTerm;
            }
        };

        foreach ($matchFields as $field) {
            $fieldIteration++;
            $query .= '(';
            // Search term is a phrase, boost exact matches ("exact match"^30)
            if ($searchPartCount > 1) {
                $partIteration = 1;
                foreach ($searchParts as $key => $part) {
                    // Previous part is a + or the part is an excluded term (-term)
                    if ((isset($searchParts[$key - 1]) && ($searchParts[$key - 1] === '+'))
                        || preg_match('/^-.+$/', $part)
                    ) {
                        $query .= ' ' . Query::QUERY_OPERATOR_AND . ' ';
                    } elseif (($partIteration > 1) && ($part !== '+')) {
                        // Add an OR between fields by default
                        $query .= ' ' . Query::QUERY_OPERATOR_OR . ' ';
                    }
                    // Part is a quoted literal string -> "literal string" or -"literal string"
                    if (preg_match('/^-?".+"$/', $part)) {
                        $query .= $parseTerm($field, $part) . '^' . self::QUERY_TERM_BOOST;
                    } elseif ($part !== '+') {
                        // Other unquoted term
                        $query .= $parseTerm($field, $part);
                    }
                    $partIteration++;
                }
            } else {
                // Search term is a single word
                $query .= $parseTerm($field, $searchTerm);
            }
            $query .= ')';

            // Add operator but not after the last field
            if ($fieldIteration < $fieldCount) {
                $query .= ' ' . $operator . ' ';
            }
        }

        return $query;
    }

    public static function parseTempFile(object $entity): string
    {
        return sys_get_temp_dir() . '/solr_' . str_replace('\\', '_', strtolower(get_class($entity))) . '_'
            . $entity->getId();
    }

    public function parseDateInterval(array $data): stdClass
    {
        //Create the date
        $fromDate = null;
        $toDate = null;

        if (isset($data['dateInterval'])) {
            $dateInterval = $data['dateInterval'];

            switch ($dateInterval) {
                case 'older':
                    $toDate = new DateTime();
                    $toDate->sub(new DateInterval('P12M'));
                    break;
                case 'P1M':
                case 'P3M':
                case 'P6M':
                case 'P12M':
                    $fromDate = new DateTime();
                    $fromDate->sub(new DateInterval($dateInterval));
                    break;
            }
        }

        $class = new stdClass();
        $class->fromDate = $fromDate;
        $class->toDate = $toDate;

        return $class;
    }

    abstract public function setSearch(
        string $searchTerm,
        array $searchFields = [],
        string $order = '',
        string $direction = Query::SORT_ASC
    ): SearchServiceInterface;

    public function deleteDocument($entity, bool $optimize = false): UpdateResult
    {
        if (method_exists($entity, 'getResourceId')) {
            $update = $this->getSolrClient()->createUpdate();
            $update->addDeleteById($entity->getResourceId());
            $update->addCommit();
            $result = $this->getSolrClient()->update($update);
            if ($optimize) {
                $this->optimizeIndex();
            }

            return $result;
        }

        $message = get_class($entity) . ' has no method getResourceId. ' . get_called_class()
            . ' should implement a custom deleteDocument method.';
        throw new RuntimeException($message);
    }

    public function getSolrClient(): Client
    {
        if (null === $this->solrClient && defined('static::SOLR_CONNECTION')) {
            $params = $this->config['solr']['connection'][static::SOLR_CONNECTION] ?? [];

            //Only change the core when this is different than the already given core
            if (!isset($params['endpoint']['server']['core'])) {
                $params['endpoint']['server']['core'] = static::SOLR_CONNECTION;
            }

            if (isset($this->config['solr']['host'])) {
                $params['endpoint']['server']['host'] = $this->config['solr']['host'];
            }

            $this->solrClient = new Client($params);
        }

        return $this->solrClient;
    }

    /**
     * Optimize the current index
     *
     * @see http://wiki.apache.org/solr/SolrPerformanceFactors#Optimization_Considerations
     * @return UpdateResult|null
     */
    public function optimizeIndex(): ?UpdateResult
    {
        $update = $this->getSolrClient()->createUpdate();
        $update->addOptimize(); // No params, just use Solr's default optimization settings

        return $this->getSolrClient()->update($update);
    }

    public function addFilterQuery(string $key, $value): Query
    {
        return $this->getQuery()->addFilterQuery(
            [
                'key'   => $key,
                'query' => $key . ':(' . $value . ')',
                'tag'   => $key,
            ]
        );
    }

    public function getQuery(): ?Query
    {
        return $this->query;
    }

    protected function setQuery(Query $query): AbstractSearchService
    {
        $this->query = $query;

        return $this;
    }

    public function getResultSet(): SelectResult
    {
        return $this->getSolrClient()->select($this->getQuery());
    }

    public function updateIndexWithCollection(array $entityCollection, bool $clear = false): void
    {
        $start = time();
        $errors = 0;
        echo "\n";
        if ($clear) {
            echo 'Clearing index...';
            $this->clearIndex();
            echo " \033[1;33mDone!\033[0m\n";
        }
        echo "Updating index:\n";
        // Iterate all publications in the database and add them to the search index
        foreach (array_reverse($entityCollection) as $entity) {
            try {
                $this->executeUpdateDocument($entity);
                echo '.';
            } catch (HttpException $e) {
                $errors++;
                $responseBody = $e->getBody();
                $template = "\n\n\033[0;31mError: Document creation for entity %s with ID %s failed\033[0m\n";
                $template .= "Solarium HTTP request status: \033[1;33m%s\033[0m\n";


                if (!empty($responseBody)) {
                    $response = Json::decode($responseBody);
                    if (isset($response->responseHeader)) {
                        $template .= "Solr HTTP response code: \033[1;33m" . $response->responseHeader->status
                            . "\033[0m\n";
                    }
                    if (isset($response->error)) {
                        $template .= "Solr error message: \033[1;33m" . $response->error->msg . "\033[0m\n";
                    }
                }
                echo sprintf($template, get_class($entity), '', $e->getMessage());
                echo "\n";
            } catch (Throwable $e) {
                $errors++;

                $template = "\n\n\033[0;31mError: Document creation for entity %s with ID %s failed\033[0m\n";
                $template .= "Error message: \033[1;33m" . $e->getMessage() . "\033[0m\n";
                $template .= "Error file: \033[1;33m" . $e->getFile() . "\033[0m\n";
                $template .= "Error number: \033[1;33m" . $e->getLine() . "\033[0m\n";

                echo sprintf($template, get_class($entity), 'asdf');
                echo "\n";
            }
        }

        echo "\n\n===================================";
        echo "\nDocuments processed: \033[1;33m" . count($entityCollection) . "\033[0m";
        echo "\nErrors: \033[1;33m" . $errors . "\033[0m";
        echo "\nDuration: \033[1;33m" . gmdate('H:i:s', time() - $start) . "\033[0m\n\n";
    }

    public function clearIndex(bool $optimize = true): UpdateResult
    {
        print 'test';
        $update = $this->getSolrClient()->createUpdate();
        $update->addDeleteQuery('*:*');
        $update->addCommit();
        $result = $this->getSolrClient()->update($update);
        if ($optimize) {
            $this->optimizeIndex();
        }

        return $result;
    }

    public function executeUpdateDocument(AbstractQuery $update): ?UpdateResult
    {
        $result = null;

        try {
            if (method_exists($update, 'addCommit')) {
                $update->addCommit();
            }
            $result = $this->getSolrClient()->update($update);
        } catch (HttpException $e) {
            throw $e;
        } finally {
            // Garbage collection, only needed when we are dealing with an extract update
            $fileName = null;
            if ($update instanceof \Solarium\QueryType\Extract\Query) {
                $fileName = $update->getFile();
            }

            if (null !== $fileName && is_file($fileName)) {
                unlink($fileName);
            }
        }

        return $result;
    }
}
