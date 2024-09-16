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

class Delay implements OptionSourceInterface
{
    public const HOUR  = '3600';
    public const MINUTES  = '60';

    /**
     * Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::HOUR  => __('Hour'),
            self::MINUTES  => __('Minutes')
        ];
    }
}
