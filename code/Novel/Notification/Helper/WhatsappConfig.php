<?php

namespace Novel\Notification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface ;

class WhatsappConfig extends AbstractHelper
{
    const XML_PATH_WHATSAPP = 'novel_notification/whatsapp/';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $encryptor;

    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        EncryptorInterface $encryptor,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->encryptor = $encryptor;
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_WHATSAPP . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getFrom()
    {
        return $this->getConfigValue('from');
    }

    public function getEndpoint()
    {
        return $this->getConfigValue('endpoint');
    }

    public function getKey1()
    {
        return $this->getConfigValue('key1');
    }

    public function getKey2()
    {
        return $this->encryptor->decrypt($this->getConfigValue('key2'));
    }

    public function getAuthKey()
    {
        return $this->getConfigValue('auth_key');
    }

    public function getWebhookDnId()
    {
        return $this->getConfigValue('webhook_dn_id');
    }
}
