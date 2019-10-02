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
use function array_filter;
use function array_reverse;
use function array_search;
use function count;
use function http_build_query;
use function in_array;
use function sprintf;
use function str_replace;
use function strtolower;
use function ucfirst;

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
                        'P3M'   => _('txt-last-3-months'),
                        'P6M'   => _('txt-last-6-months'),
                        'P12M'  => _('txt-last-year'),
                        'older' => _('txt-older-than-one-year'),
                        'all'   => _('txt-all-results'),
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
        array $reverse = []
    ): Form {
        $facetFieldset = new Fieldset('facet');

        foreach ($facetSet->getFacets() as $facetName => $facet) {
            $multiOptions = [];
            foreach ($facetSetResult->getFacets()[$facetName] as $value => $count) {
                $title = $this->parseTitleFromFacetName($facetName, (string)$value);

                $multiOptions[$value] = sprintf('%s [%s]', $title, $count);
            }

            if (in_array($facetName, $reverse, true)) {
                $multiOptions = array_reverse($multiOptions, true);
            }

            if (count($multiOptions) > 0) {
                $facetElement = new MultiCheckbox();
                $facetElement->setName($facet->getOptions()['field']);
                if (isset($this->facetLabels[$facetName])) {
                    $facetElement->setLabel($this->facetLabels[$facetName]);
                } else {
                    $facetElement->setLabel(ucfirst(str_replace('_', ' ', $facetName)));
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

    private function parseTitleFromFacetName(string $facetName, string $value): string
    {
        if ($facetName !== 'content_type') {
            return $value;
        }

        switch (strtolower($value)) {
            case 'application/pdf':
            case 'application/postscript':
                return 'PDF';

            case 'application/zip':
                return 'ZIP archive';

            case 'application/x-rar-compressed':
                return 'RAR archive';

            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/vnd.ms-excel.sheet.macroenabled.12':
            case 'application/vnd.ms-excel':
                return 'Excel';

            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'Word';

            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
            case 'application/vnd.openxmlformats-officedocument.presentationml.template':
            case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
                return 'PowerPoint';

            case 'text/html; charset=windows-1252':
                return 'HTML';

            case 'text/plain; charset=iso-8859-1':
            case 'text/plain; charset=windows-1252':
                return 'Plain/HTML';

            case 'image/png':
                return 'PNG image';

            case 'image/jpg':
            case 'image/jpeg':
                return 'JPG image';

            case 'video/x-msvideo':
                return 'MS video';

            case 'video/x-ms-wmv':
                return 'WMV video';

            case 'video/x-ms-asf':
                return 'ASF video';

            case 'application/mp4':
                return 'MP4 video';

            case 'message/rfc822':
                return 'Message';

            case 'application/xml':
                return 'XML document';

            case 'application/octet-stream':
                return 'Other';

            default:
                return $value;
        }
    }

    public function setFacetLabels(array $labels): SearchResult
    {
        $this->facetLabels = $labels;

        return $this;
    }

    public function getBadges(): array
    {
        $badges = [];
        if ('' !== $this->data['query']) {
            $badges[] = [
                'facet'          => $this->data['query'],
                'facetArguments' => http_build_query(
                    [
                        'facet' => $this->data['facet']
                    ]
                )
            ];
        }
        foreach ($this->data['facet'] as $facetName => $facets) {
            foreach ($facets as $facet) {
                //Remaining facets are all facets where the current facet value is filtered out
                $remainingFacets = $this->data['facet'];

                if (($key = array_search($facet, $remainingFacets[$facetName], true)) !== false) {
                    unset($remainingFacets[$facetName][$key]);
                }
                $badges[] = [
                    'facet'          => $facet,
                    'facetArguments' => http_build_query(
                        [
                            'query' => $this->data['query'],
                            'facet' => $remainingFacets
                        ]
                    )
                ];
            }
        }

        return $badges;
    }

    public function getFilteredData(): array
    {
        // Remove order and direction from the GET params to prevent duplication
        return array_filter(
            $this->data,
            static function ($key) {
                return !in_array($key, ['order', 'direction'], true);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
