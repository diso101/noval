<?php

namespace Novel\Notification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class PaymentTemplateConfig extends AbstractHelper
{
    public const PAYMENT_SUCCESS = 'success_template_id';
    public const PAYMENT_REMINDER_EMI = 'reminder_emi_template_id';
    public const PAYMENT_SUCCESS_EMI = 'success_emi_template_id';
    public const PAYMENT_OVERDUE = 'overdue_template_id';

    protected const XML_PATH_PAYMENT_TEMPLATE = 'novel_notification/whatsapp/payment/';

    /**
     * Retrieve the configuration value.
     *
     * @param string $field
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAYMENT_TEMPLATE . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Payment Success Template ID.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPaymentSuccessTemplateId($storeId = null)
    {
        return $this->getConfigValue(self::PAYMENT_SUCCESS, $storeId);
    }

    /**
     * Get Payment Reminder EMI Template ID.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPaymentReminderEmiTemplateId($storeId = null)
    {
        return $this->getConfigValue(self::PAYMENT_REMINDER_EMI, $storeId);
    }

    /**
     * Get Payment Success EMI Template ID.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPaymentSuccessEmiTemplateId($storeId = null)
    {
        return $this->getConfigValue(self::PAYMENT_SUCCESS_EMI, $storeId);
    }

    /**
     * Get Payment Overdue Template ID.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPaymentOverdueTemplateId($storeId = null)
    {
        return $this->getConfigValue(self::PAYMENT_OVERDUE, $storeId);
    }
}
