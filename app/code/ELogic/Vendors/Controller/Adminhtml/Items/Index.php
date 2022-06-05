<?php
namespace ELogic\Vendors\Controller\Adminhtml\Items;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use ELogic\Vendors\Controller\Adminhtml\Items as ItemsController;

class Index extends ItemsController implements HttpGetActionInterface
{
    /**
     * Items list.
     *
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('ELogic_Vendors::test');
        $resultPage->getConfig()->getTitle()->prepend(__('Vendors'));
        $resultPage->addBreadcrumb(__('Test'), __('Test'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}
