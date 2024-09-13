<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Block\Adminhtml\System;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Novel\Notification\Block\Adminhtml\System\Button;

class Ranges extends AbstractFieldArray
{
    /**
     * @var Button
     */
    private $button;

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'template_id',
            ['label' => __('Template Id'), 'class' => 'required-entry']
        );
        $this->addColumn('fields', ['label' => __('Fields'), 'class' => 'required-entry']);
        $this->addColumn('param', ['label' => __('Param'), 'class' => 'required-entry', 'style'=>'width:50px']);
        $this->addColumn('template_type', ['label' => __('Template Type'), 'class' => 'required-entry',
        'style'=>'width:100px']);
        
        $this->addColumn('test', [
            'label' => __('Test'),
            'renderer' => $this->getButtonRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        // Pass the row data to the renderer
        $this->getButtonRenderer()->setRowData($row->getData());
    }

    /**
     * @inheritDoc
     */
    private function getButtonRenderer()
    {
        if (!$this->button) {
            $this->button = $this->getLayout()->createBlock(
                Button::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->button;
    }
}
