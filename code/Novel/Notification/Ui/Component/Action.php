<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  HCG_PrescriptionManual
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Ui\Component;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class Action extends \Magento\Ui\Component\Listing\Columns\Column
{
    public const URL_PATH = 'notification/notification/resend';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * YourColumn constructor.
     *
     * @param UrlInterface $urlBuilder
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = [
                    'view' => [
                        'href' => $this->urlBuilder->getUrl(
                            static::URL_PATH,
                            [
                                'notification_id' => $item['entity_id']
                            ]
                        ),
                        'label' => __('Resend')
                    ]
                ];
            }
        }
        return $dataSource;
    }
}
