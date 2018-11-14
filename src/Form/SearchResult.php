<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category  Search
 * @package   Form
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 */

declare(strict_types=1);

namespace Search\Form;

use Solarium\Component\FacetSet as FacetSetComponent;
use Solarium\Component\Result\FacetSet as FacetSetResult;
use Zend\Form\Element;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Fieldset;
use Zend\Form\Form;

/**
 * Class SearchResult
 *
 * @package Search\Form
 */
final class SearchResult extends Form
{
    private $facetLabels = [];

    public function __construct(array $searchFields = [])
    {
        parent::__construct('search_result');
        $this->setAttribute('method', 'get');
        $this->setAttribute('action', '');
        $this->setAttribute('id', 'search');

        // The search query
        $this->add(
            [
                'type'       => Element\Text::class,
                'name'       => 'query',
                'attributes' => [
                    'placeholder' => _('txt-search-term'),
                    'class'       => 'form-control',
                ],
            ]
        );

        // Add the field selection
        if (!empty($searchFields)) {
            $this->add(
                [
                    'type'       => Element\MultiCheckbox::class,
                    'name'       => 'fields',
                    'options'    => [
                        'label'         => _('txt-search-fields'),
                        'value_options' => $searchFields
                    ],
                    'attributes' => [
                        'id' => 'searchFields',
                    ],
                ]
            );
        }

        $this->add(
            [
                'type'       => Element\MonthSelect::class,
                'name'       => 'fromDate',
                'options'    => [
                    'label'            => _('txt-from-date'),
                    'month_attributes' => [
                        'class' => 'month-select',
                    ]
                ],
                'attributes' => [
                    'class' => 'from-date',
                ]
            ]
        );
        $this->add(
            [
                'type'    => Element\MonthSelect::class,
                'name'    => 'toDate',
                'options' => [
                    'label' => _('txt-to-date'),
                ],
            ]
        );
        $this->add(
            [
                'type'    => Element\Radio::class,
                'name'    => 'dateInterval',
                'options' => [
                    'value_options' => [
                        'P3M'   => _("txt-last-3-months"),
                        'P6M'   => _("txt-last-6-months"),
                        'P12M'  => _("txt-last-year"),
                        'older' => _("txt-older-than-one-year"),
                        'all'   => _("txt-all-results"),
                    ],
                    'allow_empty'   => true,
                    'empty_option'  => '-- Select a period',
                    'label'         => _('txt-date-interval'),
                    'inline'        => true,
                ],
            ]
        );

        // Submit a search programmatically (button)
        $this->add(
            [
                'type'       => Element\Button::class,
                'name'       => 'search',
                'options'    => [
                    'label' => _('txt-search'),
                ],
                'attributes' => [
                    'id'    => 'searchButton',
                    'class' => 'btn btn-primary',
                ],
            ]
        );
        // Submit a search (submit)
        $this->add(
            [
                'type'       => Element\Submit::class,
                'name'       => 'submit',
                'attributes' => [
                    'id'    => 'searchButton',
                    'class' => 'btn btn-primary',
                    'value' => _('txt-search'),
                ],
            ]
        );
        // Reset a search
        $this->add(
            [
                'type'       => Element\Button::class,
                'name'       => 'reset',
                'options'    => [
                    'label' => _('txt-reset'),
                ],
                'attributes' => [
                    'id'    => 'resetButton',
                    'class' => 'btn btn-danger',
                ],
            ]
        );
    }


    public function addSearchResults(
        FacetSetComponent $facetSet,
        FacetSetResult $facetSetResult,
        bool $reverse = false
    ): SearchResult {
        $facetFieldset = new Fieldset('facet');

        foreach ($facetSet->getFacets() as $facetName => $facet) {
            $multiOptions = [];
            foreach ($facetSetResult->getFacets()[$facetName] as $value => $count) {
                if ($facetName === 'content_type') {
                    switch (strtolower($value)) {
                        case 'application/pdf':
                        case 'application/postscript':
                            $type = 'PDF';
                            break;
                        case 'application/zip':
                            $type = 'ZIP archive';
                            break;
                        case 'application/x-rar-compressed':
                            $type = 'RAR archive';
                            break;
                        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                        case 'application/vnd.ms-excel.sheet.macroenabled.12':
                        case 'application/vnd.ms-excel':
                            $type = 'Excel';
                            break;
                        case 'application/msword':
                        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
                        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                            $type = 'Word';
                            break;
                        case 'application/vnd.ms-powerpoint':
                        case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                        case 'application/vnd.openxmlformats-officedocument.presentationml.template':
                        case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
                            $type = 'PowerPoint';
                            break;
                        case 'text/html; charset=windows-1252':
                            $type = 'HTML';
                            break;
                        case 'text/plain; charset=iso-8859-1':
                        case 'text/plain; charset=windows-1252':
                            $type = 'Plain/HTML';
                            break;
                        case 'image/png':
                            $type = 'PNG image';
                            break;
                        case 'image/jpg':
                        case 'image/jpeg':
                            $type = 'JPG image';
                            break;
                        case 'video/x-msvideo':
                            $type = 'MS video';
                            break;
                        case 'video/x-ms-wmv':
                            $type = 'WMV video';
                            break;
                        case 'video/x-ms-asf':
                            $type = 'ASF video';
                            break;
                        case 'application/mp4':
                            $type = 'MP4 video';
                            break;
                        case 'message/rfc822':
                            $type = 'Message';
                            break;
                        case 'application/xml':
                            $type = 'XML document';
                            break;
                        case 'application/octet-stream':
                            $type = 'Other';
                            break;
                        default:
                            $type = $value;
                    }
                    $multiOptions[$value] = sprintf("%s [%s]", $type, $count);
                } else {
                    $value = (string)$value;

                    $title = (strlen($value) > 30) ? substr(ucfirst($value), 0, 29) . '…'
                        : ucfirst($value);
                    $multiOptions[$value] = sprintf('%s [%s]', $title, $count);
                }
            }

            if ($reverse) {
                $multiOptions = \array_reverse($multiOptions, true);
            }

            if (\count($multiOptions) > 0) {
                $facetElement = new MultiCheckbox();
                $facetElement->setName($facet->getOptions()['field']);
                if (isset($this->facetLabels[$facetName])) {
                    $facetElement->setLabel($this->facetLabels[$facetName]);
                } else {
                    $facetElement->setLabel(\ucfirst(\str_replace('_', ' ', $facetName)));
                }
                $facetElement->setValueOptions($multiOptions);
                $facetElement->setLabelOption('escape', false);
                $facetElement->setOption('inline', true);
                $facetElement->setDisableInArrayValidator(true);
                $facetFieldset->add($facetElement);
            }
        }

        return $this->add($facetFieldset);
    }

    public function setFacetLabels(array $labels): SearchResult
    {
        $this->facetLabels = $labels;

        return $this;
    }
}
