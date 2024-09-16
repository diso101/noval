<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Cron;

use Novel\Notification\Model\SmsModelFactory;
use Psr\Log\LoggerInterface;
use Novel\Notification\Controller\Adminhtml\Notification\Resend;

class Runner
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SmsModelFactory
     */
    protected $sms;

    /**
     * @var Resend
     */
    protected $resend;

    /**
     * @param LoggerInterface $logger
     * @param SmsModelFactory $sms
     * @param Resend $resend
     */
    public function __construct(
        LoggerInterface $logger,
        SmsModelFactory $sms,
        Resend $resend
    ) {
        $this->logger = $logger;
        $this->sms = $sms;
        $this->resend = $resend;
    }

    /**
     * Runner of Cron
     *
     * @return void
     */
    public function execute()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/notification.log');
        $this->logCreator = new \Zend_Log();
        $this->logCreator->addWriter($writer);

        $this->logCreator->info("************ Notification Novel Start " . date('Y-m-d H:i:s') . " ************");

        try {
            // Create SMS model
            $smsModel = $this->sms->create();

            // Apply filter logic for status
            $notifications = $smsModel->getCollection()
                ->addFieldToFilter('status', 0); // Example status filter
                $this->logCreator->info(json_encode($notifications->getData()));
            // Loop through filtered notifications and send SMS
            foreach ($notifications as $notification) {
                // Assuming the SMS model has a method to send notifications
                $this->resend->retry($notification);
            }

        } catch (\Exception $e) {
            // Log any exceptions
            $this->logCreator->err("Error during Notification Cron: " . $e->getMessage());
        }

        $this->logCreator->info("************ Notification Novel End " . date('Y-m-d H:i:s') . " ************");
    }
}
