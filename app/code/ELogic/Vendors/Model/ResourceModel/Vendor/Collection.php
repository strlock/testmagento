<?php
namespace ELogic\Vendors\Model\ResourceModel\Vendor;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'ELogic\Vendors\Model\Vendor',
            'ELogic\Vendors\Model\ResourceModel\Vendor'
        );
    }
}
