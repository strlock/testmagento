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
}
