<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace Search\Controller;

use Search\Form\Search;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Search\Controller
 */
final class IndexController extends AbstractActionController
{
    private array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function searchAction(): ViewModel
    {
        $searchForm = new Search();
        $searchForm->get('q')->setValue($this->getRequest()->getQuery()->get('q'));

        return new ViewModel(
            [
                'form' => $searchForm,
                'cx'   => $this->config['google']['cx'],
            ]
        );
    }
}
