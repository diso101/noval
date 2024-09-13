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

class AjaxSms extends Action
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
        $template = $this->getRequest()->getParam('template_id');
        $param = $this->getRequest()->getParam('param');
        $templateType = $this->getRequest()->getParam('template_type');
        $toNumber = $this->getRequest()->getParam('to');

        // $template = SendNotification::PAYMENT_REMINDER_EMI;
        $paramArray = $this->generateTestArray($param);
        $vars =  $paramArray;

        $result = $this->resultJsonFactory->create();
        try {
            $response = $this->sendNotification->sendSms($toNumber, $template, $vars, $templateType);
            $output = $this->sendNotification->response;
            if ($response) {
                return $result->setData(['success' => true, 'message' => __('Message sent successfully! ')]);
            } else {
                    return $result->setData(['success' => false, 'message' =>
                    __($output['statusDesc'] ?? 'Unknown Error')]);
            }
            
        } catch (\Exception $e) {
            return $result->setData(['success' => false,
            'message' => __($e->getMessage())]);
        }
    }

    /**
     * @inheritDoc
     */
    public function generateTestArray($number)
    {
        $result = [];
        for ($i = 1; $i <= $number; $i++) {
            $result[] = "test" . $i;
        }
        return $result;
    }
}
