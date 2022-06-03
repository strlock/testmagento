<?php
namespace ELogic\Vendors\Controller\Adminhtml\Items;

use Magento\Framework\App\Action\HttpPostActionInterface;
use \ELogic\Vendors\Controller\Adminhtml\Items;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Save extends Items implements HttpPostActionInterface
{
    protected $eavConfig;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        Filesystem\Driver\File $file,
    ) {
        parent::__construct(
            $context,
            $coreRegistry,
            $resultForwardFactory,
            $resultPageFactory,
            $directoryList,
            $uploaderFactory,
            $adapterFactory,
            $filesystem,
            $file
        );
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $vendor = $this->_initVendor();
        if (!$vendor) {
            return $resultRedirect->setPath('elogic_vendors/items/index');
        }
        $vendorPostData = $this->getRequest()->getPostValue();
        $vendorPostData['image'] = '';
        if ($vendorPostData) {
            $vendor->addData($vendorPostData);
            try {
                $vendor->save();
                $this->messageManager->addSuccessMessage(__('You saved the vendor.'));
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the vendor.'));
            }
        }
        return $resultRedirect->setPath('elogic_vendors/items/index');
    }
}
