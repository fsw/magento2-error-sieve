<?php
namespace Fsw\ErrorSieve\Ui\Exceptions\Index;

use Fsw\ErrorSieve\Model\Exception;
use Magento\Ui\Component\Listing\Columns\Column;

class StatusColumn extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        $dataSource = parent::prepareDataSource($dataSource);
        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }
        $fieldName = $this->getData('name');
        $sourceFieldName = 'status';
        foreach ($dataSource['data']['items'] as &$item) {
            if (!empty($item[$sourceFieldName])) {
                $status = $item[$sourceFieldName];
                $statusClasses = [
                    Exception::STATUS_NEW => 'critical',
                    Exception::STATUS_RECURRING => 'critical',
                    Exception::STATUS_ACKNOWLEDGED => 'major',
                    Exception::STATUS_FIX_PENDING => 'notice',
                    Exception::STATUS_FIX_DEPLOYED => 'minor'
                ];
                $item[$fieldName] = "<span class=\"grid-severity-{$statusClasses[$status]}\"><span>" . Exception::STATUS_MAP[$status] . "</span></span>";
            }
        }
        return $dataSource;
    }
}
