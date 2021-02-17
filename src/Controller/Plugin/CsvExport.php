<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Search\Controller\Plugin;

use Search\Service\AbstractSearchService;
use Solarium\QueryType\Select\Result\Document;
use Laminas\Http\Headers;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class CsvExport
 *
 * @package Search\Controller\Plugin
 */
final class CsvExport extends AbstractPlugin
{
    public function __invoke(AbstractSearchService $searchService, array $fields, $header = true): Response
    {
        $searchService->getQuery()->setRows(10000000); // Solr requires an upper limit
        $resultSet = $searchService->getResultSet();
        $fileName = $searchService::SOLR_CONNECTION . '.csv';
        $template = array_fill_keys($fields, '');
        $gzip = false;

        ob_start();
        $output = fopen('php://output', 'wb');
        // Set header row when required
        if ($header) {
            fputcsv($output, $fields);
        }
        /** @var Document $document */
        foreach ($resultSet as $document) {
            // Add result rows
            $documentFields = array_merge($template, $document->getFields());
            fputcsv($output, array_intersect_key($documentFields, $template));
        }

        // Convert to UTF-16LE
        $csv = mb_convert_encoding(ob_get_clean(), 'UTF-16LE', 'UTF-8');

        // Prepend BOM
        $csv = "\xFF\xFE" . $csv;

        ob_start();
        // Gzip the output when possible. @see http://php.net/manual/en/function.ob-gzhandler.php
        if (ob_start('ob_gzhandler')) {
            $gzip = true;
        }
        echo $csv;
        if ($gzip) {
            ob_end_flush(); // Flush the gzipped buffer into the main buffer
        }
        $contentLength = ob_get_length();

        // Prepare the response
        $response = new Response();
        $response->setContent(ob_get_clean());
        $response->setStatusCode(200);
        $headers = new Headers();
        $headers->addHeaders(
            [
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Content-Type'        => 'application/octet-stream',
                'Content-Length'      => $contentLength,
                'Expires'             => '@0', // @0, because ZF2 parses date as string to \DateTime() object
                'Cache-Control'       => 'must-revalidate',
                'Pragma'              => 'public',
            ]
        );
        if ($gzip) {
            $headers->addHeaders(['Content-Encoding' => 'gzip']);
        }
        $response->setHeaders($headers);

        return $response;
    }
}
