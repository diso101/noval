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
            'customercreationkycstep' => [
                'template_id' => 'customercreationkycstep',
                'fields' => 'name',
                'param' => '1',
                'template_type' => 'TEMPLATE'
            ],
            'customercreationesignstep' => [
                'template_id' => 'customercreationesignstep',
                'fields' => 'name',
                'param' => '1',
                'template_type' => 'TEMPLATE'
            ],
            'iggppaymentsuccess' => [
                'template_id' => 'iggppaymentsuccess',
                'fields' => 'name',
                'param' => '1',
                'template_type' => 'TEMPLATE'
            ],
            'igqppaymentsuccess' => [
                'template_id' => 'igqppaymentsuccess',
                'fields' => 'name',
                'param' => '1',
                'template_type' => 'TEMPLATE'
            ],
            'emireminderiggp' => [
                'template_id' => 'emireminderiggp',
                'fields' => 'name,amount,month',
                'param' => '3',
                'template_type' => 'TEMPLATE'
            ],
            'emireminderigqp' => [
                'template_id' => 'emireminderigqp',
                'fields' => 'name,amount,month',
                'param' => '3',
                'template_type' => 'TEMPLATE'
            ],
            'iggppaymentconfrimation' => [
                'template_id' => 'iggppaymentconfrimation',
                'fields' => 'name',
                'param' => '1',
                'template_type' => 'TEMPLATE'
            ],
            'igqppaymentconfrimation' => [
                'template_id' => 'igqppaymentconfrimation',
                'fields' => 'name',
                'param' => '1',
                'template_type' => 'TEMPLATE'
            ],
            'overduepaymentiggp' => [
                'template_id' => 'overduepaymentiggp',
                'fields' => 'name,amount,month',
                'param' => '3',
                'template_type' => 'TEMPLATE'
            ],
            'overduepaymentigqp' => [
                'template_id' => 'overduepaymentigqp',
                'fields' => 'name,amount,month',
                'param' => '3',
                'template_type' => 'TEMPLATE'
            ],
            'profileupdateiggp' => [
                'template_id' => 'profileupdateiggp',
                'fields' => 'name,amount,month',
                'param' => '3',
                'template_type' => 'TEMPLATE'
            ],
            'profileupdateigqp' => [
                'template_id' => 'profileupdateigqp',
                'fields' => 'name',
                'param' => '1',
                'template_type' => 'TEMPLATE'
            ],
            'iggpedemption' => [
                'template_id' => 'iggpedemption',
                'fields' => 'name,amount,accoutno',
                'param' => '3',
                'template_type' => 'TEMPLATE'
            ],
            'igqpedemption' => [
                'template_id' => 'igqpedemption',
                'fields' => 'name,amount,accoutno',
                'param' => '3',
                'template_type' => 'TEMPLATE'
            ],
            'iggpautoredemption' => [
                'template_id' => 'iggpautoredemption',
                'fields' => 'name,amount',
                'param' => '2',
                'template_type' => 'TEMPLATE'
            ],
            'igqpautoredemption' => [
                'template_id' => 'igqpautoredemption',
                'fields' => 'name,amount',
                'param' => '2',
                'template_type' => 'TEMPLATE'
            ],
            'iggppreclosure' => [
                'template_id' => 'iggppreclosure',
                'fields' => 'name,amount',
                'param' => '2',
                'template_type' => 'TEMPLATE'
            ],
            'igqppreclosure' => [
                'template_id' => 'igqppreclosure',
                'fields' => 'name,amount',
                'param' => '2',
                'template_type' => 'TEMPLATE'
            ],
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
