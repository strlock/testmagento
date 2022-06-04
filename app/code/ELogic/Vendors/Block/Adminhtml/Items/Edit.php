<?php
namespace ELogic\Vendors\Block\Adminhtml\Items;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_items';
        $this->_blockGroup = 'ELogic_Vendors';
        parent::_construct();
    }

    public function getHeaderText()
    {
        $vendor = $this->_coreRegistry->registry('vendor');
        if ($vendor->getId()) {
            return __("Edit Item '%1'", $this->escapeHtml($vendor->getName()));
        } else {
            return __('New Item');
        }
    }
}
