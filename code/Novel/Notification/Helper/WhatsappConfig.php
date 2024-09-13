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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface ;

class WhatsappConfig extends AbstractHelper
{
    public const XML_PATH_WHATSAPP = 'novel_notification/whatsapp/';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * Constructor
     *
     * @param EncryptorInterface $encryptor
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        EncryptorInterface $encryptor,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->encryptor = $encryptor;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_WHATSAPP . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @inheritDoc
     */
    public function getFrom()
    {
        return $this->getConfigValue('from');
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint()
    {
        return $this->getConfigValue('endpoint');
    }

    /**
     * @inheritDoc
     */
    public function getKey1()
    {
        return $this->getConfigValue('key1');
    }

    /**
     * @inheritDoc
     */
    public function getKey2()
    {
        return $this->encryptor->decrypt($this->getConfigValue('key2'));
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->getConfigValue('auth_key');
    }

    /**
     * @inheritDoc
     */
    public function getWebhookDnId()
    {
        return $this->getConfigValue('webhook_dn_id');
    }
}
