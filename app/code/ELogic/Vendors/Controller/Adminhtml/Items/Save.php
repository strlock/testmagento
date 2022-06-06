<?php
namespace ELogic\Vendors\Controller\Adminhtml\Items;

use ELogic\Vendors\Model\ImageProcessor;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use \ELogic\Vendors\Controller\Adminhtml\Items as ItemsController;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Throwable;
use Exception;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool as CachePool;

class Save extends ItemsController implements HttpPostActionInterface
{
    protected $eavConfig;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param DirectoryList $directoryList
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param Filesystem $filesystem
     * @param Filesystem\Driver\File $file
     * @param ImageProcessor $imageProcessor
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        Filesystem\Driver\File $file,
        private ImageProcessor $imageProcessor,
        private TypeListInterface $cacheTypeList,
        private CachePool $cacheFrontendPool,

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
                $validate = $vendor->validate();
                if ($validate !== true) {
                    throw new Exception('Invalid data');
                }
                $vendor->save();
                $this->clearCache();
                $this->messageManager->addSuccessMessage(__('You saved the vendor.'));
            } catch (Throwable $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the vendor.'));
            }
        }
        return $resultRedirect->setPath('elogic_vendors/items/index');
    }

    private function clearCache()
    {
        $types = array('block_html','full_page');
        foreach ($types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
