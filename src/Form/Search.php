<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 */

declare(strict_types=1);

namespace Search\Form;

use Zend\Form\Form;

/**
 * Class Search
 *
 * @package Search\Form
 */
final class Search extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'get');
        $this->setAttribute('action', 'search');
        $this->add(
            [
                'type'       => 'Zend\Form\Element\Text',
                'name'       => 'q',
                'attributes' => [
                    'label'       => 'search',
                    'class'       => 'form-control col-6',
                    'id'          => "search",
                    'placeholder' => _("txt-site-search"),
                ],
            ]
        );
        $this->add(
            [
                'type'       => 'Zend\Form\Element\Submit',
                'name'       => 'submit',
                'attributes' => [
                    'class' => "btn btn-primary",
                    'value' => _("txt-search"),
                ],
            ]
        );
    }
}
