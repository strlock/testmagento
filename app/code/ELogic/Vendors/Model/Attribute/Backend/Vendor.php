<?php

namespace ELogic\Vendors\Model\Attribute\Backend;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

class Vendor extends AbstractBackend
{
    public function validate($object)
    {
        //$value = $object->getData($this->getAttribute()->getAttributeCode());
        return true;
    }

    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        $data = $object->getData($attributeCode);
        if (!is_array($data)) {
            $data = [];
        }
        $object->setData($attributeCode, implode(',', $data) ?: null);
        if (!$object->hasData($attributeCode)) {
            $object->setData($attributeCode, null);
        }
        return $this;
    }
}
