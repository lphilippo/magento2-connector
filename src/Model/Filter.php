<?php

namespace LPhilippo\Magento2Connector\Model;

class Filter
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $conditionType;

    /**
     * @param string $field
     * @param mixed $value
     * @param string $conditionType
     */
    public function __construct(
        string $field,
        $value,
        string $conditionType = 'eq'
    ) {
        $this->field = $field;
        $this->value = $value;
        $this->conditionType = $conditionType;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'condition_type' => $this->conditionType,
            'field' => $this->field,
            'value' => $this->value,
        ];
    }
}
