<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Bart van Eijck <bart.van.eijck@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace Search\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Solarium\Client;
use Solarium\Core\Query\AbstractQuery;
use Solarium\Exception\HttpException;
use Solarium\QueryType\Select\Query\Query;
use Solarium\QueryType\Select\Result\Result as SelectResult;
use Solarium\QueryType\Update\Result as UpdateResult;
use Zend\I18n\View\Helper\Translate;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\Url;

abstract class AbstractSearchService implements SearchServiceInterface
{
    /**
     * SOLR date format
     *
     * @type string
     */
    public const DATE_SOLR = 'Y-m-d\TH:i:s\Z';

    /**
     * The default query term/clause boost amount
     *
     * @type int
     */
    public const QUERY_TERM_BOOST = 30;

    /**
     * Default SOLR connection
     *
     * @type string
     */
    public const SOLR_CONNECTION = 'default';
    /**
     * A Solarium client instance
     *
     * @var Client
     */
    protected $solrClient;
    /**
     * A Solarium query instance
     *
     * @var Query
     */
    protected $query;
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Parse a SOLR search query from a search term
     *
     * @param string $searchTerm
     * @param array  $matchFields
     * @param string $operator
     *
     * @return string
     */
    public static function parseQuery(
        string $searchTerm,
        array $matchFields,
        string $operator = Query::QUERY_OPERATOR_OR
    ): string {
        // ((?:\w+-)*\w+) matches both regular words and words-with-hypens
        preg_match_all('/-?"[^"]+"|\+|-?((?:\w+-)*\w+)/', $searchTerm, $searchParts);

        if (isset($searchParts[0])) {
            $searchParts = $searchParts[0];
        }

        $query = '';
        $fieldIteration = 0;
        $fieldCount = count($matchFields);
        $searchPartCount = count($searchParts);

        $parseTerm = function (string $field, string $searchTerm): string {
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
                    if ((isset($searchParts[($key - 1)]) && ($searchParts[($key - 1)] === '+'))
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

    /**
     * Parse a temp file name for extraction of binary content for an entity
     *
     * @param mixed $entity
     *
     * @return string
     */
    protected static function parseTempFile($entity): string
    {
        return sys_get_temp_dir() . '/solr_' . str_replace('\\', '_', strtolower(get_class($entity))) . '_'
            . $entity->getId();
    }

    /**
     * @param array $data
     *
     * @return \stdClass
     */
    public function parseDateInterval(array $data): \stdClass
    {
        //Create the date
        $fromDate = null;
        $toDate = null;

        if (isset($data['dateInterval'])) {
            $dateInterval = $data['dateInterval'];

            switch ($dateInterval) {
                case 'older':
                    $toDate = new \DateTime();
                    $toDate->sub(new \DateInterval('P12M'));
                    break;
                case 'P1M':
                case 'P3M':
                case 'P6M':
                case 'P12M':
                    $fromDate = new \DateTime();
                    $fromDate->sub(new \DateInterval($dateInterval));
                    break;
            }
        }

        //        if (isset($data['fromDate']['month'], $data['toDate']['month'])) {
        //            $fromDate = \DateTime::createFromFormat('d-m-Y',
        //                sprintf('01-%s-%s', $data['fromDate']['month'], $data['fromDate']['year']));
        //            $toDate = \DateTime::createFromFormat('d-m-Y',
        //                sprintf('31-%s-%s', $data['toDate']['month'], $data['toDate']['year']));
        //        }

        $class = new \stdClass();
        $class->fromDate = $fromDate;
        $class->toDate = $toDate;

        return $class;
    }

    /**
     * Insert/update a full index, optionally clearing the index first
     * Basic implementation is to call updateIndexWithCollection with the proper entity collection
     *
     * @param bool $clear
     */
    abstract public function updateIndex($clear = false);

    /**
     * Set the search params and prepare Solarium
     *
     * @param string $searchTerm
     * @param array  $searchFields
     * @param string $order
     * @param string $direction
     *
     * @return SearchServiceInterface
     */
    abstract public function setSearch(
        string $searchTerm,
        array $searchFields = [],
        string $order = '',
        string $direction = Query::SORT_ASC
    ): SearchServiceInterface;

    /**
     * Delete a single document by entity resource ID
     *
     * @param object $entity
     * @param bool   $optimize
     *
     * @return UpdateResult
     * @throws \Exception
     */
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
        throw new \Exception($message);
    }

    /**
     * Get search client
     *
     * @return Client
     */
    public function getSolrClient(): Client
    {
        if (!isset($this->solrClient) && defined('static::SOLR_CONNECTION')) {
            $config = $this->serviceLocator->get('Config');
            $params = null;

            if (isset($config['solr']['connection'][static::SOLR_CONNECTION])) {
                $params = $config['solr']['connection'][static::SOLR_CONNECTION];
            }

            $this->solrClient = new Client($params);
        }

        return $this->solrClient;
    }

    /**
     * @param Client $solrClient
     *
     * @return $this
     */
    public function setSolrClient(Client $solrClient)
    {
        $this->solrClient = $solrClient;

        return $this;
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

    /**
     * @param ServiceLocatorInterface|ContainerInterface $serviceLocator
     *
     * @return AbstractSearchService
     */
    public function setServiceLocator($serviceLocator): AbstractSearchService
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Add an extra filter to the query to further refine the results
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Query
     */
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

    /**
     * Get the Solarium Query instance
     *
     * @return Query
     */
    public function getQuery(): ?Query
    {
        return $this->query;
    }

    /**
     * Set a Solarium Query instance
     *
     * @param Query $query
     *
     * @return AbstractSearchService
     */
    protected function setQuery(Query $query): AbstractSearchService
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return SelectResult
     */
    public function getResultSet(): SelectResult
    {
        return $this->getSolrClient()->select($this->getQuery());
    }

    /**
     * @param string $entity
     *
     * @return array
     */
    public function findAll(string $entity): array
    {
        $entityManager = $this->serviceLocator->get(EntityManager::class);

        return $entityManager->getRepository($entity)->findAll();
    }

    /**
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getServerUrl(): string
    {
        return ($this->serviceLocator->get('ViewHelperManager')->get('serverUrl'))();
    }

    /**
     * @param string $route
     * @param array  $params
     *
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getUrl(string $route, array $params = []): string
    {
        /** @var Url $url */
        $url = $this->serviceLocator->get('ViewHelperManager')->get('url');

        return $url($route, $params);
    }

    /**
     * @param $string
     *
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function translate($string): string
    {
        /** @var Translate $translate */
        $translate = $this->serviceLocator->get('ViewHelperManager')->get('translate');

        return $translate($string);
    }

    /**
     * Execute a document update
     *
     * @param AbstractQuery $update
     * @param string        $fileName
     *
     * @return UpdateResult
     * @throws HttpException
     *
     * \Solarium\QueryType\Extract\Query
     */
    protected function executeUpdateDocument(AbstractQuery $update, ?string $fileName = null): UpdateResult
    {
        try {
            if (method_exists($update, 'addCommit')) {
                $update->addCommit();
            }
            $result = $this->getSolrClient()->update($update);
        } catch (HttpException $e) {
            $result = null;
            throw $e;
        } finally {
            // Garbage collection
            if (!empty($fileName) && is_file($fileName)) {
                unlink($fileName);
            }
        }

        return $result;
    }

    /**
     * Update the current index with the given entitycollection and optionally clear all existing data
     *
     * @param array   $entityCollection
     * @param boolean $clear
     */
    protected function updateIndexWithCollection(array $entityCollection, bool $clear = false): void
    {
        $start = time();
        $errors = 0;
        echo "\n";
        if ($clear) {
            echo "Clearing index...";
            $this->clearIndex();
            echo " \033[1;33mDone!\033[0m\n";
        }
        echo "Updating index:\n";
        // Iterate all publications in the database and add them to the search index
        foreach (array_reverse($entityCollection) as $entity) {
            try {
                $this->updateDocument($entity);
                echo ".";
            } catch (HttpException $e) {
                $errors++;
                $responseBody = $e->getBody();
                $template = "\n\n\033[0;31mError: Document creation for entity %s with ID %s failed\033[0m\n";
                $template .= "Solarium HTTP request status: \033[1;33m%s\033[0m\n";

                if (!empty($responseBody)) {
                    $response = json_decode($responseBody);
                    if (isset($response->responseHeader)) {
                        $template .= "Solr HTTP response code: \033[1;33m" . $response->responseHeader->status
                            . "\033[0m\n";
                    }
                    if (isset($response->error)) {
                        $template .= "Solr error message: \033[1;33m" . $response->error->msg . "\033[0m\n";
                    }
                }
                echo sprintf($template, get_class($entity), $entity->getId(), $e->getMessage());
                echo "\n";
            } catch (\Throwable $e) {
                $errors++;

                $template = "\n\n\033[0;31mError: Document creation for entity %s with ID %s failed\033[0m\n";
                $template .= "Error message: \033[1;33m" . $e->getMessage() . "\033[0m\n";


                echo sprintf($template, get_class($entity), $entity->getId());
                echo "\n";
            }
        }

        echo "\n\n===================================";
        echo "\nDocuments processed: \033[1;33m" . count($entityCollection) . "\033[0m";
        echo "\nErrors: \033[1;33m" . $errors . "\033[0m";
        echo "\nDuration: \033[1;33m" . gmdate("H:i:s", (time() - $start)) . "\033[0m\n\n";
    }

    /**
     * Clear the current index
     *
     * @param bool $optimize
     *
     * @return UpdateResult
     */
    public function clearIndex($optimize = true): UpdateResult
    {
        $update = $this->getSolrClient()->createUpdate();
        $update->addDeleteQuery('*:*');
        $update->addCommit();
        $result = $this->getSolrClient()->update($update);
        if ($optimize) {
            $this->optimizeIndex();
        }

        return $result;
    }

    /**
     * Update or insert a single document
     *
     * @param object $entity
     *
     * @return UpdateResult
     */
    abstract public function updateDocument($entity);
}
