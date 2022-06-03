<?php
namespace ELogic\Vendors\Block;

use ELogic\Vendors\Model\Vendor;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    private $vendorModel;

    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        array $data = [],
        ?OutputHelper $outputHelper = null,
    ) {
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data,
            $outputHelper
        );
        $this->vendorModel = ObjectManager::getInstance()->get('\ELogic\Vendors\Model\Vendor');
    }

    protected function _beforeToHtml()
    {
        $result = parent::_beforeToHtml();
        $collection = $this->_getProductCollection();
        $vendorIds = [];
        foreach ($collection as $product) {
            $vendorIds += explode(',', $product->getVendor());
        }
        $vendorsById = [];
        $vendorsCollection = $this->vendorModel->getCollection();
        $vendorsCollection->addFieldToFilter('entity_id', ['in' => $vendorIds]);
        foreach ($vendorsCollection->getItems() as $vendor) {
            $vendorsById[$vendor->getId()] = $vendor;
        }
        foreach ($collection as $product) {
            $vendorsData = [];
            foreach (explode(',', $product->getVendor()) as $vendor_id) {
                if (empty($vendorsById[$vendor_id])) {
                    continue;
                }
                $vendor = $vendorsById[$vendor_id];
                $vendorsData[] = [
                    'name' => $vendor->getName(),
                    'description' => $vendor->getDescription(),
                ];
            }
            $product->setData('vendors_data', $vendorsData);
        }
        return $result;
    }
}
