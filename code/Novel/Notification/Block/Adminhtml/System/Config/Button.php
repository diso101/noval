<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;

class Button extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Novel_Notification::config/button.phtml';

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('Novel_Notification::config/button.phtml');
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element)
    {
        $element->addClass('button');
        $html = $this->_decorateRowHtml(
            $element,
            '<td class="label"></td><td class="value">' . $this->_getElementHtml($element) . '</td>'
        );
        return $html;
    }

    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * @inheritDoc
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('notification/config/send'); // Custom route for handling the action
    }
}
