<?php

namespace LPhilippo\Magento2Connector;

use LPhilippo\Magento2Connector\Adapter\CurlAdapter;
use LPhilippo\Magento2Connector\Exception\AdapterException;
use LPhilippo\Magento2Connector\Helper\ResponseHelper;
use LPhilippo\Magento2Connector\Model\Filter;
use LPhilippo\Magento2Connector\Response\AdapterResponse;

class Client
{
    /**
     * @var CurlAdapter
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $token;

    /**
     * @param CurlAdapter $adapter
     */
    public function __construct(CurlAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Request $request
     *
     * @return AdapterResponse
     */
    protected function call(Request $request)
    {
        if ($this->token) {
            $request->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ]);
        }

        return $this->adapter->call($request)->wait();
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return Client
     */
    public function authenticate(string $username, string $password)
    {
        // Remove old token, to prevent sending it along.
        $this->token = null;

        $result = $this->call(
            RequestFactory::makeForPost(
                'integration/admin/token',
                [
                    'username' => $username,
                    'password' => $password,
                ]
            )
        );

        $content = $result->getContent();

        if (!is_string($content)) {
            throw new AdapterException('token-not-received');
        }

        $this->token = $content;

        return $this;
    }

    /**
     * @param SearchCriteria $searchCriteria
     *
     * @return array
     */
    public function getSalesOrders(SearchCriteria $searchCriteria)
    {
        $response = $this->call(
            RequestFactory::make(
                'orders',
                [
                    'searchCriteria' => $searchCriteria->toArray(),
                ]
            )
        );

        return $response->getContent()['items'];
    }

    /**
     * @param string $incrementId
     *
     * @return array
     */
    public function getSalesOrder(string $incrementId)
    {
        $searchCriteria = new SearchCriteria();
        $searchCriteria->addFilter(
            new Filter(
                'increment_id',
                $incrementId
            )
        );

        return ResponseHelper::getFirstItemOrNull(
            $this->getSalesOrders($searchCriteria)
        );
    }

    /**
     * @param string $incrementId
     * @param int $productId
     *
     * @return array
     */
    public function getCatalogProduct(int $productId)
    {
        $response = $this->call(
            RequestFactory::make(
                'products',
                [
                    'searchCriteria' => [
                        'filter_groups' => [
                            [
                                'filters' => [
                                    [
                                        'field' => 'entity_id',
                                        'value' => $productId,
                                        'condition_type' => 'eq',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            )
        );

        return ResponseHelper::getFirstItemOrNull($response->getContent());
    }

    /**
     * @param int $externalId
     * @param string $status
     * @param string|null $comment
     *
     * @return array
     */
    public function addSalesOrderComment(int $externalId, string $status, string $comment = null)
    {
        $this->call(
            RequestFactory::makeForPost(
                sprintf('orders/%d/comments', $externalId),
                [
                    'statusHistory' => [
                        'comment' => $comment,
                        'is_customer_notified' => 0,
                        'is_visible_on_front' => 0,
                        'parent_id' => $externalId,
                        'status' => $status,
                    ],
                ]
            )
        );

        return true;
    }
}
