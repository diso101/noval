<?php

namespace Novel\Notification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class CustomerTemplateConfig extends AbstractHelper
{

    public const CUSOMTER_CREATION = 'creation_template_id';
    public const CUSOMTER_SCHEME_CREATION = 'scheme_template_id';
    public const CUSOMTER_PROFILE_UPDATE = 'update_template_id';

    protected const XML_PATH_CUSTOMER_TEMPLATE = 'novel_notification/whatsapp/customer/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_TEMPLATE . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @inheritDoc
     */
    public function getCustomerCreationTemplateId()
    {
        return $this->getConfigValue(self::CUSOMTER_CREATION);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerSchemeTemplateId()
    {
        return $this->getConfigValue(self::CUSOMTER_SCHEME_CREATION);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerUpdateTemplateId()
    {
        return $this->getConfigValue(self:CUSOMTER_PROFILE_UPDATE);
    }

}
