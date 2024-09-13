<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Controller\Adminhtml\Config;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Novel\Notification\Model\SendNotification;
use Magento\Backend\App\Action\Context;

class Send extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Novel\Notification\Model\SendNotification
     */
    protected $sendNotification;

    /**
     * Constructor
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param SendNotification $sendNotification
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        SendNotification $sendNotification
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->sendNotification = $sendNotification;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // Ensure it's an AJAX request
        if (!$this->getRequest()->isAjax()) {
            return $this->_redirect('adminhtml/system_config/edit/section/notification_settings');
        }
        $toNumber = $this->getRequest()->getParam('to');
        $template = SendNotification::PAYMENT_REMINDER_EMI;
        $vars = [
            'test1',
            'test2',
            'test3',
            'test4'
        ];

        $result = $this->resultJsonFactory->create();
        try {

            $response = $this->sendNotification->sendSms($toNumber, $template, $vars);
            if ($response) {
                return $result->setData(['success' => true, 'message' => __('Message sent successfully! '
                .json_encode($response))]);
            } else {
                return $result->setData(['success' => false, 'message' =>
                __('Failed to send message. Please try again.')]);
            }
            
        } catch (\Exception $e) {
            return $result->setData(['success' => false,
            'message' => __('Failed to send test message. Please try again.' . $e->getMessage())]);
        }
    }
}
