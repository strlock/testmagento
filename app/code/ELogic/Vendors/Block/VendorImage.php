<?php
namespace ELogic\Vendors\Block;
class VendorImage extends \Magento\Framework\View\Element\Template
{
    protected $_product = null;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        private \Magento\Framework\Registry $_coreRegistry,
        private \ELogic\Vendors\Model\Vendor $vendorModel,
        private \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
    }

    public function getProductVendorImages()
    {
        $result = [];
        if (empty($this->_product)) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        foreach (explode(',', $this->_product->getVendor()) as $vendor_id) {
            $this->vendorModel->load($vendor_id);
            $result[] = $mediaUrl.$this->vendorModel->getImage();
        }
        return $result;
    }
}
