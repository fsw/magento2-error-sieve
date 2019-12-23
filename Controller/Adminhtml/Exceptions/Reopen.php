<?php
namespace Fsw\ErrorSieve\Controller\Adminhtml\Exceptions;

use Fsw\ErrorSieve\Model\Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Reopen extends Action
{
    /** @var Exception */
    protected $exception;

    public function __construct(
        Context $context,
        Exception $exception
    ) {
        $this->exception = $exception;
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
        if ($this->exception->getId()) {
            $this->exception->setData('status', Exception::STATUS_NEW);
            $this->exception->save();
        }
        $this->messageManager->addSuccessMessage("done");
        return $this->_redirect('fsw_errorsieve/*');
    }
}
