<?php

namespace LPhilippo\Magento2Connector;

use LPhilippo\Magento2Connector\Adapter\CurlAdapter;
use LPhilippo\Magento2Connector\Exception\AdapterException;
use LPhilippo\Magento2Connector\Helper\ResponseHelper;
use LPhilippo\Magento2Connector\Model\Filter;
use LPhilippo\Magento2Connector\Model\SearchCriteria;
use LPhilippo\Magento2Connector\Response\AdapterResponse;
use LPhilippo\Magento2Connector\Response\ExceptionResponse;

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
     * @param string|null $googleSecret
     *
     * @return Client
     */
    public function authenticate(string $username, string $password, string $googleSecret = null)
    {
        // Remove old token, to prevent sending it along.
        $this->token = null;
        $endpoint = 'integration/admin/token';
        $code = null;

        if ($googleSecret) {
            $endpoint = 'tfa/provider/google/authenticate';
            $otp = TOTP::create($googleSecret);
            $code = $otp->now();
        }

        $result = $this->call(
            RequestFactory::makeForPost(
                $endpoint,
                array_filter(
                    [
                        'otp' => $code,
                        'password' => $password,
                        'username' => $username,
                    ]
                )
            )
        );

        $content = $result->getContent();

        if ($result instanceof ExceptionResponse || !is_string($content)) {
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

        $content = $response->getContent();
        if ($response instanceof ExceptionResponse) {
            throw new AdapterException('unexpected-response: ' . $content['message']);
        }

        return $content['items'];
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
        $searchCriteria = new SearchCriteria();

        $searchCriteria->addFilter(
            new Filter(
                'entity_id',
                $productId
            )
        );

        return ResponseHelper::getFirstItemOrNull(
            $this->getCatalogProducts($searchCriteria)
        );
    }

    /**
     * @param array $attributeIds
     * @param SearchCriteria $searchCriteria
     *
     * @return array
     */
    public function getCatalogProducts(SearchCriteria $searchCriteria)
    {
        $response = $this->call(
            RequestFactory::make(
                'products',
                [
                    'searchCriteria' => $searchCriteria->toArray(),
                ]
            )
        );

        $content = $response->getContent();
        if ($response instanceof ExceptionResponse) {
            throw new AdapterException('unexpected-response: ' . $content['message']);
        }

        return $content['items'];
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

    /**
     * @param array $productChanges
     *
     * @return array
     */
    public function changeProducts(array $productChanges)
    {
        $this->call(
            RequestFactory::makeForPut(
                'products',
                array_map(function ($productChange) {
                    return [
                        'product' => $productChange,
                    ];
                }, $productChanges),
                true
            )
        );

        return true;
    }
}
