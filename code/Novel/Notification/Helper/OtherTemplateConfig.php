<?php

namespace Novel\Notification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class OtherTemplateConfig extends AbstractHelper
{
    public const REDEMPTION_SCHEME = 'redemption_scheme_template_id';
    public const REDEMPTION_AUTO = 'auto_template_id';
    public const PRE_CLOSURE = 'pre_template_id';

    protected const XML_PATH_CUSTOMER_TEMPLATE = 'novel_notification/whatsapp/';

    /**
     * Get configuration value by field and store ID
     *
     * @param string $field
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_TEMPLATE . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get the redemption scheme template ID
     *
     * @return string|null
     */
    public function getRedemptionSchemeTemplateId()
    {
        return $this->getConfigValue('redemption/'.self::REDEMPTION_SCHEME);
    }

    /**
     * Get the redemption auto template ID
     *
     * @return string|null
     */
    public function getRedemptionAutoTemplateId()
    {
        return $this->getConfigValue('redemption/'.self::REDEMPTION_AUTO);
    }

    /**
     * Get the pre-closure template ID
     *
     * @return string|null
     */
    public function getPreClosureTemplateId()
    {
        return $this->getConfigValue('closure/'.self::PRE_CLOSURE);
    }
}
