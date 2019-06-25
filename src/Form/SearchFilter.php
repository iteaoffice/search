<?php

/**
 * Jield BV all rights reserved
 *
 * @category    Admin
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2004-2017 Jield BV (https://jield.nl)
 */

declare(strict_types=1);

namespace Search\Form;

use Zend\Form\Element\Search;
use Zend\Form\Element\Submit;
use Zend\Form\Fieldset;
use Zend\Form\Form;

/**
 * Class SearchFilter
 *
 * @package Search\Form
 */
class SearchFilter extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'get');
        $this->setAttribute('id', 'search-form');

        $filterFieldset = new Fieldset('filter');

        $filterFieldset->add(
            [
                'type'       => Search::class,
                'name'       => 'search',
                'attributes' => [
                    'class'       => 'form-control',
                    'placeholder' => _('txt-search'),
                ],
            ]
        );

        $this->add($filterFieldset);

        $this->add(
            [
                'type'       => Submit::class,
                'name'       => 'search',
                'attributes' => [
                    'id'    => 'search',
                    'class' => 'btn btn-primary submitButton',
                    'value' => _('txt-search'),
                ],
            ]
        );

        $this->add(
            [
                'type'       => Submit::class,
                'name'       => 'submit',
                'attributes' => [
                    'id'    => 'search',
                    'class' => 'btn btn-primary submitButton',
                    'value' => _('txt-search'),
                ],
            ]
        );

        $this->add(
            [
                'type'       => Submit::class,
                'name'       => 'reset',
                'attributes' => [
                    'id'    => 'resetButton',
                    'class' => 'btn btn-warning resetButton',
                    'value' => _('txt-reset'),
                ],
            ]
        );
    }
}
