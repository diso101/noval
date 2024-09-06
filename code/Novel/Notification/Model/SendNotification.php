<?php

namespace Novel\Notification\Model;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Exception\LocalizedException;
use Novel\Notification\Helper\WhatsappConfig;
use Novel\Notification\Helper\CustomerTemplateConfig;
use Novel\Notification\Helper\PaymentTemplateConfig;
use Novel\Notification\Helper\OtherTemplateConfig;
use Novel\Notification\Model\SmsModelFactory;

class SendNotification
{
    public const CUSOMTER_CREATION = 'creation_template_id';
    public const CUSOMTER_SCHEME_CREATION = 'scheme_template_id';
    public const CUSOMTER_PROFILE_UPDATE = 'update_template_id';
    public const PAYMENT_SUCCESS = 'success_template_id';
    public const PAYMENT_REMINDER_EMI = 'reminder_emi_template_id';
    public const PAYMENT_SUCCESS_EMI = 'success_emi_template_id';
    public const PAYMENT_OVERDUE = 'overdue_template_id';
    public const REDEMPTION_SCHEME = 'redemption_scheme_template_id';
    public const REDEMPTION_AUTO = '_auto_template_id';
    public const PRE_CLOSURE = 'pre_template_id';

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var WhatsappConfig
     */
    protected $whatsappConfig;

    /**
     * @var CustomerTemplateConfig
     */
    protected $customerTemplateConfig;

    /**
     * @var PaymentTemplateConfig
     */
    protected $paymentTemplateConfig;

    /**
     * @var OtherTemplateConfig
     */
    protected $otherTemplateConfig;

    /**
     * @var string
     */
    protected $toNumber;

    /**
     * @var string
     */
    protected $templateId;

    /**
     * @var string
     */
    protected $button = [];

    /**
     * @var string
     */
    protected $mediaType = null;
    
    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @var SmsModelFactory
     */
    protected $smsModel;

    /**
     * Constructor
     *
     * @param Curl $curl
     * @param WhatsappConfig $whatsappConfig
     * @param CustomerTemplateConfig $customerTemplateConfig
     * @param PaymentTemplateConfig $paymentTemplateConfig
     * @param SmsModelFactory $smsModel
     * @param OtherTemplateConfig $otherTemplateConfig
     */
    public function __construct(
        Curl $curl,
        WhatsappConfig $whatsappConfig,
        CustomerTemplateConfig $customerTemplateConfig,
        PaymentTemplateConfig $paymentTemplateConfig,
        SmsModelFactory $smsModel,
        OtherTemplateConfig $otherTemplateConfig
    ) {
        $this->curl = $curl;
        $this->whatsappConfig = $whatsappConfig;
        $this->customerTemplateConfig = $customerTemplateConfig;
        $this->paymentTemplateConfig = $paymentTemplateConfig;
        $this->smsModel = $smsModel;
        $this->otherTemplateConfig = $otherTemplateConfig;
    }

    /**
     * Set the recipient phone number
     *
     * @param string $toNumber
     * @return void
     */
    public function setToNumber($toNumber)
    {
        $this->toNumber = $toNumber;
    }

    /**
     * Get the recipient phone number
     *
     * @return string
     */
    public function getToNumber()
    {
        return $this->toNumber;
    }

    /**
     * Set the recipient phone number
     *
     * @param array $vars
     * @return void
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    /**
     * Get the recipient phone number
     *
     * @return string
     */
    public function getVars() : array
    {
        return $this->vars;
    }

    /**
     * Set the template ID
     *
     * @param string $templateId
     * @return void
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * Get the template ID
     *
     * @return string
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * Set the template ID
     *
     * @param string $mediaType
     * @return void
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
    }

    /**
     * Get the template ID
     *
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @inheritDoc
     */
    public function setButton($param, $type, $index, $payload)
    {
        $button = null;
        $this->button[$param] = [];
        $button['type']    = $type;
        $button['index']   = $index;
        $button['payload'] = $payload;
        array_push($this->button[$param], $button);
    }

    /**
     * Get the template ID
     *
     * @return string
     */
    public function getButton()
    {
        return $this->button;
    }

    /**
     * @inheritDoc
     */
    public function sendWhatsapp()
    {
        $sms = $this->smsModel->create();
        $to = $this->getToNumber();
        $templateId =  $this->getTemplateId();
        $vars = $this->getVars();
        $from = $this->whatsappConfig->getFrom();
        $webhookDnId = $this->whatsappConfig->getWebhookDnId();
        
        if (empty($to) || empty($templateId) || empty($vars)) {
            throw new LocalizedException(__('Missing required parameters for sending WhatsApp notification.'));
        }
        if (empty($from) || empty($webhookDnId)) {
            throw new LocalizedException(__('Missing Some configuration settings.'));
        }
     
        $sms->setSourceId($templateId);
        $sms->setTo($to);

        // Convert to an object
        $outputObject = (object) $vars;
        $sms->setMessage(json_encode($outputObject));
        $payload = [];
        $payload['message']['channel'] = "WABA";
        $payload['message']['content']['preview_url'] = false;
        $payload['message']['recipient']['to'] = $to;
        $payload['message']['recipient']['recipient_type'] = "individual";
        $payload['message']['recipient']['reference']['cust_ref'] = "test2";
        $payload['message']['sender']['from'] = $from;
        $payload['message']['preferences']['webHookDNId'] = $webhookDnId;
        $payload['metaData']['version'] = "v1.0.9";

        if ($this->mediaType == 'MEDIA_TEMPLATE') {
            $payload['message']['content']['shorten_url'] = true;
            $payload['message']['content']['type'] = $this->mediaType;
            $payload['message']['content']['mediaTemplate']['templateId'] = $templateId;
            $payload['message']['content']['mediaTemplate']['bodyParameterValues'] = $outputObject;
            if (!empty($this->getButton())) {
                $payload['message']['content']['mediaTemplate']['buttons'] = $this->getButton();
            }
        } else {
            $payload['message']['content']['shorten_url'] = false;
            $payload['message']['content']['type'] = $this->mediaType;
            $payload['message']['content']['template']['templateId'] = $templateId;
            $payload['message']['content']['template']['parameterValues'] = $outputObject;
        }
        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $sms->setPayload($jsonPayload);
        $responses = $this->send($jsonPayload);
        try {
            if (isset($responses['statusCode']) && $responses['statusCode'] == "200") {
                $sms->setStatus(true);
                return true;
            } else {
                $sms->setStatus(false);
                $sms->setReason($response);
                return false;
            }
        } catch (\Exception $e) {
            $sms->setStatus(false);
            $sms->setReason($e->getMessage());
            return false;
            // throw new LocalizedException(__($e->getMessage()));
        } finally {
            $sms->save();
        }
    }

    /**
     * @inheritDoc
     */
    public function resend($payload)
    {
        return $this->send($payload);
    }

    /**
     * @inheritDoc
     */
    protected function send($payload)
    {
        $url = $this->whatsappConfig->getEndpoint();
        $key = $this->whatsappConfig->getKey1();
        $authKey = $this->whatsappConfig->getAuthKey();

        if (empty($url) || empty($key) || empty($authKey)) {
            throw new LocalizedException(__('Missing Some configuration settings.'));
        }
     
        try {
            // Set options for the cURL request
            $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
            $header = [
                'Content-Type' => 'application/json',
                $key => 'Bearer '.$authKey // Add your authentication header if needed
            ];
            $this->curl->setHeaders($header);
            $this->curl->post($url, $payload);
            $response = $this->curl->getBody();
            $responses = json_decode($response, true);
            return $responses;
            // $responses = [];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function sendSms($to, $template, $data, $mediaType = null)
    {
        $this->setToNumber($to);
     
        // Set variables
        $vars = [];
        switch ($template) {
            case self::CUSOMTER_CREATION:
                $this->templateId = $this->customerTemplateConfig->getCustomerCreationTemplateId();
                break;
            case self::CUSOMTER_SCHEME_CREATION:
                $this->templateId = $this->customerTemplateConfig->getSchemeCreationTemplateId();
                break;
            case self::CUSOMTER_PROFILE_UPDATE:
                $this->templateId = $this->customerTemplateConfig->getUpdateTemplateId();
                break;
            case self::PAYMENT_SUCCESS:
                $this->templateId = $this->paymentTemplateConfig->getPaymentSuccessTemplateId();
                break;
            case self::PAYMENT_REMINDER_EMI:
                $this->setMediaType('MEDIA_TEMPLATE');
                $this->setButton("actions", "url", "0", "https://www.google.com/");
                $this->setVars($data);
                $this->templateId = $this->paymentTemplateConfig->getPaymentReminderEmiTemplateId();
                break;
            case self::PAYMENT_SUCCESS_EMI:
                $this->templateId = $this->paymentTemplateConfig->getPaymentSuccessEmiTemplateId();
                break;
            case self::PAYMENT_OVERDUE:
                $this->templateId = $this->paymentTemplateConfig->getPaymentOverdueTemplateId();
                break;
            case self::REDEMPTION_SCHEME:
                $this->templateId = $this->otherTemplateConfig->getRedemptionSchemeTemplateId();
                break;
            case self::REDEMPTION_AUTO:
                $this->templateId = $this->otherTemplateConfig->getRedemptionAutoTemplateId();
                break;
            case self::PRE_CLOSURE:
                $this->templateId = $this->otherTemplateConfig->getPreClosureTemplateId();
                break;
            case 'rt_einvoice':
                $this->setVars($data);
                $this->templateId = 'rt_einvoice';
                break;
            default:
                throw new LocalizedException(__('Invalid template type'));
        }
        return $this->sendWhatsapp();
    }
}
