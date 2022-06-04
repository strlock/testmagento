<?php
namespace ELogic\Vendors\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\ObjectManager;

class Product extends AbstractHelper
{
    public function addVendorsDataToProductsCollection(&$collection) {
        $vendorIds = [];
        foreach ($collection as $product) {
            if (!empty($product->getVendor())) {
                $vendorIds += explode(',', $product->getVendor());
            }
        }
        $vendorsById = [];
        $vendorsCollection = ObjectManager::getInstance()->get('\ELogic\Vendors\Model\Vendor')->getCollection();
        $vendorsCollection->addFieldToFilter('entity_id', ['in' => $vendorIds]);
        foreach ($vendorsCollection->getItems() as $vendor) {
            $vendorsById[$vendor->getId()] = $vendor;
        }
        foreach ($collection as $product) {
            $vendorsData = [];
            if (empty($product->getVendor())) {
                continue;
            }
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
    }
}
