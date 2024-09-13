<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Template extends AbstractHelper
{
    // Configuration fields
    public const TO_TEST = 'to_test';
    public const OTHERS_TEMPLATE_ID = 'templates';

    // Path to the configuration section
    protected const XML_PATH_TEMPLATE = 'novel_notification/template/';

    /**
     * Retrieve configuration value by field ID and store ID.
     *
     * @param string $field
     * @param null|string $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TEMPLATE . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get 'To Test' configuration value.
     *
     * @param null|string $storeId
     * @return string
     */
    public function getToTest($storeId = null)
    {
        return $this->getConfigValue(self::TO_TEST, $storeId);
    }

    /**
     * Get 'Others Template ID' configuration value.
     *
     * @param null|string $storeId
     * @return array
     */
    public function getTemplates($storeId = null)
    {
        return $this->getConfigValue(self::OTHERS_TEMPLATE_ID, $storeId);
    }
}
