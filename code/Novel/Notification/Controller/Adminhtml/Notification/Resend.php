<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_SchemeMaster
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
        $sms = $this->smsModel->create()->load($notificationId, 'entity_id');
        $newSms = $this->smsModel->create();
        $newSms->setSourceId($sms->getSourceId());
        $newSms->setMessage($sms->getMessage());
        $newSms->setTo($sms->getTo());

        try {
            $newSms->setPayload($sms->getPayload());
            $responses = $this->sendNotification->resend($sms->getPayload());
            if (isset($responses['statusCode']) && $responses['statusCode'] == "200") {
                $newSms->setStatus(true);
                $this->messageManager->addSuccessMessage(__('Notification has been resent successfully.'));
            } else {
                $newSms->setStatus(false);
                $newSms->setReason(json_encode($responses));
                $this->messageManager->addErrorMessage(__('Failed to resend notification: ') . ($responses['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $newSms->setStatus(false);
            $newSms->setReason($e->getMessage());
            $this->messageManager->addErrorMessage(__('An error occurred while resending the notification: ') . $e->getMessage());
        } finally {
            $newSms->save();
        }

        // Redirect to the notification index page
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('notification/notification/index');
        return $resultRedirect;
    }
}
