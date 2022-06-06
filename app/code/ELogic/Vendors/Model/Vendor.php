<?php
namespace ELogic\Vendors\Model;

use Magento\Framework\Model\AbstractModel;

class Vendor extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('ELogic\Vendors\Model\ResourceModel\Vendor');
    }

    public function validate()
    {
        if (empty($this->getName())) {
            return false;
        }
        if (empty($this->getDescription())) {
            return false;
        }
        return true;
    }
}
