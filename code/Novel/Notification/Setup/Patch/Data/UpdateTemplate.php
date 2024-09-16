<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Setup\Patch\Data;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterfaceFactory;

class UpdateTemplate implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var BlockInterfaceFactory
     */
    private $blockFactory;

    /**
     * @param ModuleDataSetupInterface $setup
     * @param WriterInterface $configWriter
     * @param BlockRepositoryInterface $blockRepository
     * @param BlockInterfaceFactory $blockFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        WriterInterface $configWriter,
        BlockRepositoryInterface $blockRepository,
        BlockInterfaceFactory $blockFactory
    ) {
        $this->setup = $setup;
        $this->configWriter = $configWriter;
        $this->blockRepository = $blockRepository;
        $this->blockFactory = $blockFactory;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {

        $configData = [
            "overdue_payment_igqp" => [
                "template_id" => "overdue_payment_igqp",
                "fields" => "name,amount,month",
                "param" => "3",
                "template_type" => "TEMPLATE"
            ],
            "overdue_payment_iggp" => [
                "template_id" => "overdue_payment_iggp",
                "fields" => "name,amount,month",
                "param" => "3",
                "template_type" => "TEMPLATE"
            ],
            "igqp_payment_confrimation" => [
                "template_id" => "igqp_payment_confrimation",
                "fields" => "name",
                "param" => "1",
                "template_type" => "TEMPLATE"
            ],
            "iggp_payment_confrimation" => [
                "template_id" => "iggp_payment_confrimation",
                "fields" => "name",
                "param" => "1",
                "template_type" => "TEMPLATE"
            ],
            "emi_reminder_iggp" => [
                "template_id" => "emi_reminder_iggp",
                "fields" => "name,amount,month",
                "param" => "3",
                "template_type" => "TEMPLATE"
            ],
            "emi_reminder_igqp" => [
                "template_id" => "emi_reminder_igqp",
                "fields" => "name,amount,month",
                "param" => "3",
                "template_type" => "TEMPLATE"
            ],
            "igqp_payment_success" => [
                "template_id" => "igqp_payment_success_",
                "fields" => "name",
                "param" => "1",
                "template_type" => "TEMPLATE"
            ],
            "iggp_payment_success" => [
                "template_id" => "iggp_payment_success_",
                "fields" => "name",
                "param" => "1",
                "template_type" => "TEMPLATE"
            ],
            "customer_creation_esign_step" => [
                "template_id" => "customer_creation_esign_step",
                "fields" => "name",
                "param" => "1",
                "template_type" => "TEMPLATE"
            ],
            "customer_creation_kycstep" => [
                "template_id" => "customercreation_kycstep",
                "fields" => "name",
                "param" => "1",
                "template_type" => "TEMPLATE"
            ],
            "profileupdateiggp" => [
                "template_id" => "profileupdateiggp",
                "fields" => "name,amount,month",
                "param" => "1",
                "template_type" => "TEMPLATE"
            ],
            "profileupdateigqp" => [
                "template_id" => "profileupdateigqp",
                "fields" => "name",
                "param" => "1",
                "template_type" => "TEMPLATE"
            ],
            "iggpedemption" => [
                "template_id" => "iggpedemption",
                "fields" => "name,amount,accoutno",
                "param" => "3",
                "template_type" => "TEMPLATE"
            ],
            "igqpedemption" => [
                "template_id" => "igqpedemption",
                "fields" => "name,amount,accoutno",
                "param" => "3",
                "template_type" => "TEMPLATE"
            ],
            "iggpautoredemption" => [
                "template_id" => "iggpautoredemption",
                "fields" => "name,amount",
                "param" => "2",
                "template_type" => "TEMPLATE"
            ],
            "igqpautoredemption" => [
                "template_id" => "igqpautoredemption",
                "fields" => "name,amount",
                "param" => "2",
                "template_type" => "TEMPLATE"
            ],
            "iggppreclosure" => [
                "template_id" => "iggppreclosure",
                "fields" => "name,amount",
                "param" => "2",
                "template_type" => "TEMPLATE"
            ],
            "igqppreclosure" => [
                "template_id" => "igqppreclosure",
                "fields" => "name,amount",
                "param" => "2",
                "template_type" => "TEMPLATE"
            ]
        ];

        // Serialize data if needed
        $serializedData = json_encode($configData);

        // Save configuration value
        $this->configWriter->save(
            'novel_notification/template/templates',
            $serializedData,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0
        );
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
