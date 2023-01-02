<?php

namespace Jo\Ker\Ui\Component\Laptop\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class LaptopActions extends Column
{
    protected $url;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $url
     * @param array $components
     * @param array $data
     */
    public function __construct(ContextInterface   $context,
                                UiComponentFactory $uiComponentFactory,
                                UrlInterface       $url,
                                array              $components = [],
                                array              $data = [])
    {
        $this->url = $url;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['laptop_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->url->getUrl('joker/laptop/edit', ['id' => $item['laptop_id']]),
                        'label' => __('Edit'),
                        'hidden' => false
                    ];

                    /*$item[$name]['delete'] =
                        ['href' => $this->url->getUrl('joker/laptop/delete', ['id' => $item['laptop_id']]),
                            'label' => __('Delete'),
                            'hidden' => false
                        ];
                    $item[$name]['view'] = [
                        'href' => $this->url->getUrl('joker/laptop/view', ['id' => $item['laptop_id']]),
                        'label' => __('View'),
                        'target' => '_blank',
                    ];*/
                }
            }
        }
        return parent::prepareDataSource($dataSource); // TODO: Change the autogenerated stub
    }
}