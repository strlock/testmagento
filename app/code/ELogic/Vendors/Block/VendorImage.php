<?php
namespace ELogic\Vendors\Block;

use ELogic\Vendors\Model\Vendor;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Element\Template;

class VendorImage extends Template
{
    protected ?object $_product = null;

    /**
     * @param Context $context
     * @param Registry $_coreRegistry
     * @param Vendor $vendorModel
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        private Registry $_coreRegistry,
        private Vendor $vendorModel,
        private StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getProductVendorImages(): array
    {
        $result = [];
        if (empty($this->_product)) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        if (empty($this->_product->getVendor())) {
            return [];
        }
        $vendorIds = explode(',', $this->_product->getVendor());
        $vendorsCollection = $this->vendorModel->getCollection();
        $vendorsCollection->addFieldToFilter('entity_id', ['in' => $vendorIds]);
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        foreach ($vendorsCollection as $vendor) {
            $imageUrl = $vendor->getImage();
            $imageUrl = preg_replace('#^/media#', '', $imageUrl);
            $result[] = $mediaUrl.$imageUrl;
        }
        return $result;
    }
}
