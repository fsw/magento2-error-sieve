<?php
namespace Fsw\ErrorSieve\Ui\Exceptions\Index;

use Fsw\ErrorSieve\Model\Exception;
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
                ];

                switch ($item['status']) {
                    case Exception::STATUS_NEW;
                    case Exception::STATUS_RECURRING;
                        $actions = ['acknowledge', 'fixpending', 'fixdeployed'];
                        break;
                    case Exception::STATUS_ACKNOWLEDGED;
                    case Exception::STATUS_FIX_PENDING;
                        $actions = ['fixdeployed', 'reopen'];
                        break;
                    case Exception::STATUS_FIX_DEPLOYED;
                        $actions = ['fixpending', 'reopen'];
                        break;
                }
                foreach ($actions as $action) {
                    $item[$this->getData('name')][$action] = [
                        'href'    => $this->urlBuilder->getUrl('fsw_errorsieve/exceptions/' . $action, [
                            'id' => $item['id'],
                        ]),
                        'label'   => $action,
                        'confirm' => [
                            'title' => __('Are you sure?'),
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
