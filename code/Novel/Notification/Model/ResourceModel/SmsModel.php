<?php

namespace Novel\Notification\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Sms
 */
class SmsModel extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('sms_notification', 'entity_id');
    }
}
