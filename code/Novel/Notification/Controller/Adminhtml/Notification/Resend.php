<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Controller\Adminhtml\Notification;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Novel\Notification\Model\SmsModelFactory;
use Novel\Notification\Model\SendNotification;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Resend extends Action
{
    /**
     * @var SmsModelFactory
     */
    protected $smsModel;

    /**
     * @var SendNotification
     */
    protected $sendNotification;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param SmsModelFactory $smsModel
     * @param SendNotification $sendNotification
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        SmsModelFactory $smsModel,
        SendNotification $sendNotification,
        ManagerInterface $messageManager,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->smsModel = $smsModel;
        $this->sendNotification = $sendNotification;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $notificationId = $this->getRequest()->getParam('notification_id');
        $method = $this->getRequest()->getParam('method');
        $postData = $this->getRequest()->getPostValue();

        if (empty($notificationId)) {
            $notificationId = $this->getRequest()->getParam('entity_id');
        }

        $sms = $this->smsModel->create()->load($notificationId, 'entity_id');

        if ($this->getRequest()->getParam('button') == 'save' ||
        $this->getRequest()->getParam('button') == 'save_and_send') {
            $data = $this->getRequest()->getParams();
            $value = $sms->getData();
            $differences = array_diff_assoc($data, $value);
            $updatedParams = array_intersect_key($differences, $value);
            foreach ($updatedParams as $key => $param) {
                $sms->setData($key, $param);
            }
            $sms->save();
        }

        if ($method == 'resend') {
            $this->resend($sms);
        } elseif ($method == 'retry') {
            $this->retry($sms);
        }
        // Redirect to the notification index page
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('notification/notification/index');
        return $resultRedirect;
    }

    /**
     * @inheritDoc
     */
    protected function resend($sms)
    {

        $newSms = $this->smsModel->create();
        $newSms->setSourceId($sms->getSourceId());
        $newSms->setMessage($sms->getMessage());
        $newSms->setTo($sms->getTo());

        try {
            $newSms->setPayload($sms->getPayload());
            $responses = $this->sendNotification->resend($sms->getPayload());

            $newSms->setResponse(json_encode($responses));
            if (isset($responses['statusCode']) && $responses['statusCode'] == "200") {
                $newSms->setStatus(true);
                $newSms->setMid($responses['mid']);
                $this->messageManager->addSuccessMessage(__('Notification has been resent successfully.'));
            } else {
                $newSms->setStatus(false);
                $newSms->setReason(json_encode($responses));
                $this->messageManager->addErrorMessage(__('Failed to resend notification: ')
                .($responses['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $newSms->setStatus(false);
            $newSms->setReason($e->getMessage());
            $this->messageManager->addErrorMessage(__('An error occurred while resending the notification: ')
            . $e->getMessage());
        } finally {
            $newSms->save();
        }
    }

    /**
     * @inheritDoc
     */
    public function retry($sms)
    {
        try {
            $responses = $this->sendNotification->resend($sms->getPayload());
            $sms->setResponse(json_encode($responses));
            if (isset($responses['statusCode']) && $responses['statusCode'] == "200") {
                $sms->setStatus(true);
                $sms->setMid($responses['mid']);
                $sms->setReason('');
                $this->messageManager->addSuccessMessage(__('Notification has been sent successfully.'));
            } else {
                $sms->setStatus(false);
                $sms->setReason(json_encode($responses));
                $this->messageManager->addErrorMessage(__('Failed to send notification: ')
                . ($responses['statusDesc'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $sms->setStatus(false);
            $sms->setReason($e->getMessage());
            $this->messageManager->addErrorMessage(__('An error occurred while retrying the notification: ')
            . $e->getMessage());
        } finally {
            $sms->save();
        }
    }
}
