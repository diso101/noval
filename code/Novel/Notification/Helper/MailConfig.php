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

class MailConfig extends AbstractHelper
{
    protected const XML_PATH_MAIL = 'novel_notification/mail/';

    /**
     * @inheritDoc
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAIL . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @inheritDoc
     */
    public function getScheme()
    {
        return $this->getConfigValue('scheme');
    }

    /**
     * @inheritDoc
     */
    public function getReminder()
    {
        return $this->getConfigValue('reminder');
    }

    /**
     * @inheritDoc
     */
    public function getEmi()
    {
        return $this->getConfigValue('emi');
    }

    /**
     * @inheritDoc
     */
    public function getOverdue()
    {
        return $this->getConfigValue('overdue');
    }
}
