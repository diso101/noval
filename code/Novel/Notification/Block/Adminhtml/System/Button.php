<?php
declare(strict_types=1);
/**
 * PwC India
 *
 * @category Magento
 * @package  Novel_Notification
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Block\Adminhtml\System;

use Magento\Framework\View\Element\AbstractBlock;

class Button extends AbstractBlock
{
    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml(): string
    {
        // Extract values from row data
        // Create the button HTML with the template ID as its text
        $buttonHtml = '<button type="button" class="ajax-button" > Test
        </button>';

        // Add JavaScript for button click
        $buttonHtml .= '<script>
            require(["jquery"], function($) {
                $(document).off("click", ".ajax-button").on("click", ".ajax-button", function() {
                    var $button = $(this);
                    var $row = $button.closest("tr");
                    var rowId = $row.attr("id");
                    var templateId = rowId+"_template_id";
                    var templateId = $("#"+templateId).val();
                    var param = rowId+"_param";
                    var param = $("#"+param).val();
                    var templateType = rowId+"_template_type";
                    var templateType = $("#"+templateType).val();
                    var to = $("#novel_notification_template_to_test").val();

                    $.ajax({
                        url: "' . $this->getAjaxUrl() . '",
                        type: "POST",
                        data: {
                            template_id: templateId,
                            param: param,
                            template_type: templateType,
                            to: to
                        },
                        success: function(response) {
                            alert(response.message);
                        },
                        error: function(xhr, status, error) {
                            alert("Error: " + xhr.responseText);
                        }
                    });
                });
            });
        </script>';

        return $buttonHtml;
    }

    /**
     * Get the AJAX URL for button click
     *
     * @return string
     */
    private function getAjaxUrl(): string
    {
        return $this->getUrl('notification/config/ajaxsms'); // Adjust to your correct route
    }

    /**
     * Get the row ID for this button
     *
     * @return string
     */
    private function getRowId(): string
    {
        // This method should return the actual row ID for the button
        // Adjust this logic as necessary for your context
        return 'row-id-placeholder'; // Replace with actual logic to get row ID
    }
}
