<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Model;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Exception\LocalizedException;
use Novel\Notification\Helper\WhatsappConfig;
use Novel\Notification\Helper\Template;
use Novel\Notification\Model\SmsModelFactory;

class SendNotification
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var WhatsappConfig
     */
    protected $whatsappConfig;

    /**
     * @var Template
     */
    protected $template;

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
     * @var array
     */
    public $response;

    /**
     * Constructor
     *
     * @param Curl $curl
     * @param WhatsappConfig $whatsappConfig
     * @param SmsModelFactory $smsModel
     * @param Template $template
     */
    public function __construct(
        Curl $curl,
        WhatsappConfig $whatsappConfig,
        SmsModelFactory $smsModel,
        Template $template
    ) {
        $this->curl = $curl;
        $this->whatsappConfig = $whatsappConfig;
        $this->smsModel = $smsModel;
        $this->template = $template;
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
                $sms->setMid($responses['mid']);
                return true;
            } else {
                $sms->setStatus(false);
                $sms->setReason(isset($responses['statusDesc']) ? $responses['statusDesc']
                : json_encode($responses));
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
            $this->response = $responses;
            return $responses;
            // $responses = [];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function sendSms($to, $template, $data)
    {
        // Validate the 'to' parameter
        if (empty($to)) {
            throw new LocalizedException(__('Invalid recipient mobile number.'));
        }

        // Validate the 'template' parameter
        if (empty($template)) {
            throw new LocalizedException(__('Template ID cannot be empty.'));
        }

        // Validate the 'data' parameter
        if (!is_array($data)) {
            throw new LocalizedException(__('Data must be an array.'));
        }

        $finalTemplate = null;
        $count = 0;

        $templates = json_decode($this->template->getTemplates(), true);
        foreach ($templates as $templatList) {
            if ($templatList['template_id'] == $template) {
                $finalTemplate = $template;
                $count = $templatList['param'];
                $this->setMediaType($templatList['template_type']);
                continue;
            }
        }

        if ($finalTemplate == null) {
            throw new LocalizedException(__('Please add Template id in config'));
        }

        if (count($data) != $count) {
            throw new LocalizedException(__("Need $count params for selected template id"));
        }
    
        // Set recipient number
        $this->setToNumber($to);

        // Set variables
        $this->setVars($data);
        $this->templateId = $finalTemplate;
        // Set a button if needed
        // $this->setButton("actions", "url", "0", "https://www.google.com/");

        // Send the message
        return $this->sendWhatsapp();
    }
}
