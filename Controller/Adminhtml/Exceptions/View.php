<?php
namespace Fsw\ErrorSieve\Controller\Adminhtml\Exceptions;

use Fsw\ErrorSieve\Block\Adminhtml\Exceptions\View as ViewBlock;
use Fsw\ErrorSieve\Model\Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends Action
{
    /** @var PageFactory */
    protected $resultPageFactory;

    /** @var Exception */
    protected $exception;

    public function __construct(
        Context $context,
        Exception $exception,
        PageFactory $resultPageFactory
    ) {
        $this->exception = $exception;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Fsw_ErrorSieve::exceptions');
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $this->exception->load($id);
        if (!$this->exception->getId()) {
            $this->messageManager->addError(__('This exception no longer exists.'));
            return $this->_redirect('fsw_errorsieve/*');
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Fsw_ErrorSieve::exceptions');
        $resultPage->getConfig()->getTitle()->prepend($this->exception->getTitle());
        $resultPage->addBreadcrumb(__('Exceptions'), $this->exception->getTitle());

        /** @var ViewBlock $block*/
        $block = $this->_view->getLayout()->getBlock('adminhtml.block.errorsieve.exceptions.view');
        $block->setException($this->exception);
        return $resultPage;
    }
}
