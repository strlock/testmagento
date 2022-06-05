<?php
namespace ELogic\Vendors\Controller\Adminhtml\Items;

use ELogic\Vendors\Model\ImageProcessor;
use Magento\Framework\App\Action\HttpPostActionInterface;
use \ELogic\Vendors\Controller\Adminhtml\Items as ItemsController;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Throwable;

class Save extends ItemsController implements HttpPostActionInterface
{
    protected $eavConfig;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param DirectoryList $directoryList
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param Filesystem $filesystem
     * @param Filesystem\Driver\File $file
     * @param ImageProcessor $imageProcessor
     */
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
        private ImageProcessor $imageProcessor,
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

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws FileSystemException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $vendor = $this->_initVendor();
        if (!$vendor) {
            return $resultRedirect->setPath('elogic_vendors/items/index');
        }
        $vendorPostData = $this->getRequest()->getPostValue();
        $this->imageProcessor->process($vendorPostData);
        if ($vendorPostData) {
            $vendor->addData($vendorPostData);
            try {
                $vendor->save();
                $this->messageManager->addSuccessMessage(__('You saved the vendor.'));
            } catch (Throwable $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the vendor.'));
            }
        }
        return $resultRedirect->setPath('elogic_vendors/items/index');
    }
}
