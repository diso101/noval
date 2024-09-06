<?php
namespace Novel\Notification\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;

class Button extends Field
{
    protected $_template = 'Novel_Notification::config/button.phtml';

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('Novel_Notification::config/button.phtml');
        }
        return $this;
    }

    public function render(AbstractElement $element)
    {
        $element->addClass('button');
        $html = $this->_decorateRowHtml($element, '<td class="label"></td><td class="value">' . $this->_getElementHtml($element) . '</td>');
        return $html;
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    public function getAjaxUrl()
    {
        return $this->getUrl('notification/config/send'); // Custom route for handling the action
    }
}
