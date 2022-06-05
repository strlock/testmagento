<?php
namespace ELogic\Vendors\Controller\Adminhtml\Items;

use Magento\Framework\App\Action\HttpGetActionInterface;
use ELogic\Vendors\Controller\Adminhtml\Items as ItemsController;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class NewAction extends ItemsController implements HttpGetActionInterface
{
    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
