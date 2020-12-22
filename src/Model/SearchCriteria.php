<?php

namespace LPhilippo\Magento2Connector;

use LPhilippo\Magento2Connector\Model\Filter;

class SearchCriteria
{
    /**
     * @var array
     */
    private $filterGroups = [];

    /**
     * @param Filter $filter
     * @param string $filterGroup
     *
     * @return SearchCriteria
     */
    public function addFilter(Filter $filter, string $filterGroup = null)
    {
        // TODO: support mutliple filters per filterGroup.
        $this->filterGroups[] = [
            'filters' => [
                $filter,
            ],
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'filter_groups' => array_map(function (array $filterGroup) {
                return [
                    'filters' => array_map(function (Filter $filter) {
                        return $filter->toArray();
                    }, $filterGroup['filters']),
                ];
            }, $this->filterGroups),
        ];
    }
}
