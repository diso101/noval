<?php

namespace Novel\Notification\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**
     * Get available options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('Success')],
            ['value' => '0', 'label' => __('Failed')],
        ];
    }

    /**
     * Get options as key-value pairs
     *
     * @return array
     */
    public function toArray()
    {
        return [
            '1' => __('Success'),
            '0' => __('Failed')
        ];
    }
}
