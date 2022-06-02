<?php
namespace ELogic\Vendors\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Vendor extends AbstractSource
{
    public function __construct(
        private \ELogic\Vendors\Model\ResourceModel\Vendor\Collection $vendor
    )
    {
        //
    }

    public function getAllOptions(): ?array
    {
        if (empty($this->_options)) {
            foreach ($this->vendor->getItems() as $vendor) {
                $this->_options[] = [
                    'label' => $vendor->getName(),
                    'value' => $vendor->getId(),
                ];
            }
        }
        return $this->_options;
    }
}
