<?php

namespace Novel\Notification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class MailConfig extends AbstractHelper
{
    const XML_PATH_MAIL = 'novel_notification/mail/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAIL . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getScheme()
    {
        return $this->getConfigValue('scheme');
    }

    public function getReminder()
    {
        return $this->getConfigValue('reminder');
    }

    public function getEmi()
    {
        return $this->getConfigValue('emi');
    }

    public function getOverdue()
    {
        return $this->getConfigValue('overdue');
    }
}
