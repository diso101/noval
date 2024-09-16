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

use Magento\Framework\Data\OptionSourceInterface;

class Frequency implements OptionSourceInterface
{
    public const TIME  = 'T';
    public const DATE  = 'D';
    public const WEEKLY = 'W';
    public const MONTH = 'M';

    /**
     * Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::TIME  => __('Time'),
            self::DATE  => __('Date'),
            self::WEEKLY => __('Weekly'),
            self::MONTH => __('Month')
        ];
    }
}
