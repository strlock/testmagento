<?php

namespace ELogic\Vendors\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    public function __construct(private EavSetupFactory $eavSetupFactory)
    {
        //
    }
    
    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            Product::ENTITY,
            'vendor',
            [
                'group' => 'General',
                'type' => 'text',
                'label' => 'Product Vendor',
                'input' => 'multiselect',
                'source' => 'ELogic\Vendors\Model\Attribute\Source\Vendor',
                'frontend' => 'ELogic\Vendors\Model\Attribute\Frontend\Vendor',
                'backend' => 'ELogic\Vendors\Model\Attribute\Backend\Vendor',
                'required' => false,
                'sort_order' => 50,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'visible' => true,
                'is_html_allowed_on_front' => true,
                'visible_on_front' => true,
            ]
        );
    }
}
