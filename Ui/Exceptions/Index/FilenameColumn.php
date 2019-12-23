<?php

namespace Fsw\ErrorSieve\Ui\Exceptions\Index;

use Fsw\ErrorSieve\Model\Exception;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

/**
 * @api
 * @since 101.0.0
 */
class FilenameColumn extends Column
{

    /** @var DirectoryList */
    protected $directoryList;

    public function __construct(
        DirectoryList $directoryList,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->directoryList = $directoryList;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @since 101.0.0
     */
    public function prepareDataSource(array $dataSource)
    {
        $dataSource = parent::prepareDataSource($dataSource);

        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            if (!empty($item[$fieldName])) {
                $filename = $item[$fieldName];
                $prefix = $this->directoryList->getRoot();
                if (substr($filename, 0, strlen($prefix)) == $prefix) {
                    $filename = substr($filename, strlen($prefix));
                }
                $item[$fieldName] = $filename;
            }
        }

        return $dataSource;
    }
}
