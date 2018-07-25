<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Content
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license   https://itea3.org/license.txt proprietary
 *
 * @link      https://itea3.org
 */

declare(strict_types=1);

namespace Search\Factory;

use Interop\Container\ContainerInterface;
use Search\Service\AbstractSearchService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class SearchServiceFactory
 *
 * @package Search\Factory
 */
final class SearchServiceFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): AbstractSearchService {
        /** @var AbstractSearchService $searchService */
        $searchService = new $requestedName();
        $searchService->setServiceLocator($container);

        return $searchService;
    }
}
