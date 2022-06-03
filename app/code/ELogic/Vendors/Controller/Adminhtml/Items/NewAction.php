<?php
namespace ELogic\Vendors\Controller\Adminhtml\Items;

use Magento\Framework\App\Action\HttpGetActionInterface;

class NewAction extends \ELogic\Vendors\Controller\Adminhtml\Items implements HttpGetActionInterface
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
