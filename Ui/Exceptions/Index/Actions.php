<?php
namespace Fsw\ErrorSieve\Ui\Exceptions\Index;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    /** @var UrlInterface */
    private $urlBuilder;

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

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = [
                    'view'   => [
                        'href'  => $this->urlBuilder->getUrl('fsw_errorsieve/exceptions/view', [
                            'id' => $item['id'],
                        ]),
                        'label' => __('View'),
                    ],
                    /*'delete' => [
                        'href'    => $this->urlBuilder->getUrl('report/email/delete', [
                            EmailInterface::ID => $item[EmailInterface::ID],
                        ]),
                        'label'   => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete email?'),
                        ],
                    ],*/
                ];
            }
        }

        return $dataSource;
    }
}
