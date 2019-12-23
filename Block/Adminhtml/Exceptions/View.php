<?php
namespace Fsw\ErrorSieve\Block\Adminhtml\Exceptions;

use Fsw\ErrorSieve\Model\Exception;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Model\UrlFactory;
use Magento\Backend\Model\UrlInterface;

class View extends Template
{
    protected $_template = 'exception-details.phtml';

    /** @var UrlInterface */
    protected $url;


    /** @var Exception */
    protected $exception;

    /**
     * View constructor.
     * @param Context $context
     * @param UrlFactory $backendUrlFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlFactory $backendUrlFactory,
        array $data = []
    ) {
        $this->url = $backendUrlFactory->create();
        parent::__construct($context, $data);
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setException(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getBackUrl()
    {
        return $this->url->getUrl('fsw_errorsieve/exceptions/index');
    }
}
