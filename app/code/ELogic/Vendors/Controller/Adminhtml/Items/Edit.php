<?php
namespace ELogic\Vendors\Controller\Adminhtml\Items;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use ELogic\Vendors\Controller\Adminhtml\Items as ItemsController;

class Edit extends ItemsController implements HttpGetActionInterface
{
    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $vendor = $this->_objectManager->create('ELogic\Vendors\Model\Vendor');
        if ($id) {
            $vendor->load($id);
            if (!$vendor->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('elogic_vendors/*');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $vendor->addData($data);
        }
        $this->_coreRegistry->register('vendor', $vendor);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('items_items_edit');
        $this->_view->renderLayout();
    }
}
