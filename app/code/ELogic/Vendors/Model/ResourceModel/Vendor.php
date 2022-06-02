<?php
namespace ELogic\Vendors\Model\ResourceModel;

class Vendor extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('elogic_vendors', 'entity_id');
    }
}
