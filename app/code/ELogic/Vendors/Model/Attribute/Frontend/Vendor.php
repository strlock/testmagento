<?php

namespace ELogic\Vendors\Model\Attribute\Frontend;

use Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;


class Vendor extends AbstractFrontend
{
    public function getValue(DataObject $object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        $vendorIds = explode(',', $value);
        $vendorsCollection = ObjectManager::getInstance()->create(\ELogic\Vendors\Model\Vendor::class)->getCollection();
        $vendorsCollection->addFieldToFilter('entity_id', ['in' => $vendorIds]);
        foreach ($vendorsCollection as $vendor) {
            $vendorNames[] = $vendor->getName();
        }
        return '<b>'.implode(', ', $vendorNames).'</b>';
    }
}
