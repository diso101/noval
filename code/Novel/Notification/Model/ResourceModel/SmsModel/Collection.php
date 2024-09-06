<?php

namespace Novel\Notification\Model\ResourceModel\SmsModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Novel\Notification\Model\SmsModel;
use Novel\Notification\Model\ResourceModel\SmsModel as SmsResource;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(SmsModel::class, SmsResource::class);
    }
}