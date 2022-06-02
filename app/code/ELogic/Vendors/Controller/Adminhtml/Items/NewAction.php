<?php
namespace ELogic\Vendors\Controller\Adminhtml\Items;

class NewAction extends \ELogic\Vendors\Controller\Adminhtml\Items
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
