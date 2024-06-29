<?php

namespace LPhilippo\Magento2Connector\Model;

class SearchCriteria
{
    /**
     * @var int
     */
    private $currentPage = 1;

    /**
     * @var array
     */
    private $filterGroups = [];

    /**
     * @var int
     */
    private $pageSize = 20;

    /**
     * @var array
     */
    private $sortOrders = [];

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
     * @param string $field
     * @param string $direction
     *
     * @return SearchCriteria
     */
    public function addSortOrder(string $field, string $direction = 'ASC')
    {
        $this->sortOrders[] = [
            'field' => $field,
            'direction' => $direction,
        ];

        return $this;
    }

    /**
     * @param int $currentPage
     *
     * @return SearchCriteria
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * @param int $pageSize
     *
     * @return SearchCriteria
     */
    public function setPageSize(int $pageSize)
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'current_page' => $this->currentPage,
            'filter_groups' => array_map(function (array $filterGroup) {
                return [
                    'filters' => array_map(function (Filter $filter) {
                        return $filter->toArray();
                    }, $filterGroup['filters']),
                ];
            }, $this->filterGroups),
            'page_size' => $this->pageSize,
            'sort_orders' => $this->sortOrders,
        ];
    }
}
