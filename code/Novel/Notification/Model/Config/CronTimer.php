<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Model\Config;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ValueFactory;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

class CronTimer extends \Magento\Framework\App\Config\Value
{
    public const CRON_STRING_PATH = 'groups/cron_config/fields/time/value';

    public const CRON_MODEL_PATH = 'groups/cron_config/fields/frequency/value';

    /**
     * @var Collection
     */
    protected $configValueFactory;

    /**
     * @var Collection
     */
    protected $runModelPath = '';

    /**
     * Get Details
     *
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param ValueFactory $configValueFactory
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param string $runModelPath
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        $runModelPath = '',
        array $data = []
    ) {
        $this->runModelPath = $runModelPath;
        $this->configValueFactory = $configValueFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * Get Payment Url
     *
     * @return $this
     */
    public function afterSave()
    {
        $time = $this->getData(self::CRON_STRING_PATH);
        $frequency = $this->getData(self::CRON_MODEL_PATH);
        $hr = '*';
        $min = '*';
        if ($frequency == \Novel\Notification\Model\Config\Frequency::TIME && $time[1] > 0) {
            $min = '*/'.$time[1];
        } elseif ($frequency == \Novel\Notification\Model\Config\Frequency::TIME && $time[0] > 0) {
            $hr = '*/'.$time[0];
        } else {
            $hr = $time[0];
            $min = $time[1];
        }

        $cronExprArray = [
            // @codingStandardsIgnoreLine
            $min,
            // @codingStandardsIgnoreLine
            $hr,
            $frequency == \Novel\Notification\Model\Config\Frequency::MONTH ? '1' : '*',
            '*',
            $frequency == \Novel\Notification\Model\Config\Frequency::WEEKLY ? '1' : '*',
        ];
        $cronExprString = join(' ', $cronExprArray);
        if ($time[0] == 0 && $time[1] == 1) {
            $cronExprString = '* * * * *';
        }

        try {
            $this->configValueFactory->create()->load(
                self::CRON_STRING_PATH,
                'path'
            )->setValue(
                $cronExprString
            )->setPath(
                self::CRON_STRING_PATH
            )->save();
            $this->configValueFactory->create()->load(
                self::CRON_MODEL_PATH,
                'path'
            )->setValue(
                $this->runModelPath
            )->setPath(
                self::CRON_MODEL_PATH
            )->save();
        } catch (\Exception $e) {
            // @codingStandardsIgnoreLine
            throw new \Exception(__('We can\'t save the cron expression.'));
        }

        return parent::afterSave();
    }
}
