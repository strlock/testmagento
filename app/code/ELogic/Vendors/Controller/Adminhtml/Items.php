<?php
namespace ELogic\Vendors\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Backend\App\Action;
use Magento\Framework\Filesystem\Driver\File;

abstract class Items extends Action
{
    protected Registry $_coreRegistry;
    protected ForwardFactory $resultForwardFactory;
    protected PageFactory $resultPageFactory;
    protected UploaderFactory $uploaderFactory;
    protected AdapterFactory $adapterFactory;
    protected Filesystem $filesystem;
    protected DirectoryList $directoryList;

    /**
     * Initialize Group Controller
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param DirectoryList $directoryList
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param Filesystem $filesystem
     * @param File $file
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
        File $file
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->directoryList = $directoryList;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * Initiate action
     *
     * @return $this
     */
    protected function _initAction(): static
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('ELogic_Vendors::items')->_addBreadcrumb(__('Items'), __('Items'));
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('ELogic_Vendors::items');
    }

    /**
     * @return int
     */
    protected function resolveVendorId() : int
    {
        $vendorId = (int)$this->getRequest()->getParam('id', false);
        return $vendorId ?: (int)$this->getRequest()->getParam('entity_id', false);
    }

    /**
     * @param $getRootInstead
     * @return mixed
     */
    protected function _initVendor($getRootInstead = false): mixed
    {
        $vendorId = $this->resolveVendorId();
        $vendor = $this->_objectManager->create(\ELogic\Vendors\Model\Vendor::class);
        if ($vendorId) {
            $vendor->load($vendorId);
        }
        $this->_coreRegistry->register('vendor', $vendor);
        return $vendor;
    }
}
