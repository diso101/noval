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

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class MailHelper extends AbstractHelper
{
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Get Details
     *
     * @param Context $context
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $state
     * @param OrderFactory $orderFactory
     * @param ScopeConfigInterface $scopeConfig
     * @return mixed
     */
    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $state,
        OrderFactory $orderFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
        $this->orderFactory = $orderFactory;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Get Details
     *
     * @param Order $order
     * @param string $templateId
     * @return mixed
     */
    public function sendEmail($order, $templateId)
    {
        $templateId = 'prescription_notification'; // template id

        $fromEmail = $this->scopeConfig->getValue(
            'trans_email/ident_general/email',
            ScopeInterface::SCOPE_STORE
        );

        $fromName = $this->scopeConfig->getValue(
            'trans_email/ident_general/name',
            ScopeInterface::SCOPE_STORE
        );

        try {
            $toEmail = $order->getCustomerEmail();
            $toName = $order->getCustomerName();
            $orderStatus = $order->getStatus();
            $orderid = $order->getIncrementId();

            // Get customer information
            $customerName = $order->getCustomerName();
            $customerEmail = $order->getCustomerEmail();
            $billingAddress = $order->getBillingAddress();
            $subject = "Your Prescription Status Update"; // You can change this to the desired subject

            $prescriptionId = $this->scopeConfig->getValue(
                'notification/notifi/prescription_head', // Update with your actual configuration path
                ScopeInterface::SCOPE_STORE
            );

            $notApprovedMessage = $this->scopeConfig->getValue(
                'notification/notifi/prescription_Body', // Update with your actual configuration path
                ScopeInterface::SCOPE_STORE
            );

            // template variables pass here
            $templateVars = [
                'order' => $orderid,
                'orderStatus' => $orderStatus,
                'customerName' => $customerName,
                'customerEmail' => $customerEmail,
                'billingAddress' => $billingAddress,
                'prescriptionId' => $prescriptionId,
                'notApprovedMessage' => $notApprovedMessage,
                'prescriptionStatus' => "Prescription Status: Canceled", // New line for prescription status
            ];

            $storeId = $this->storeManager->getStore()->getId();
            $from = ['email' => $fromEmail, 'name' => $fromName];
            $this->inlineTranslation->suspend();

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $storeId
            ];
            $transport = $this->transportBuilder->setTemplateIdentifier($templateId, $storeScope)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($from)
                ->addTo($toEmail, $toName)
                ->getTransport();
            $transport->getMessage()->setSubject($subject);
            $transport->sendMessage();
                    $this->inlineTranslation->resume();
        } catch (\Exception $e) {
               return false;
        }
    }
}
