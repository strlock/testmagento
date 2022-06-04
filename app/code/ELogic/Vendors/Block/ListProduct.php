<?php
namespace ELogic\Vendors\Block;

use ELogic\Vendors\Helper\Product as ProductHelper;
use ELogic\Vendors\Model\Vendor;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        private ProductHelper $productHelper,
        array $data = [],
        ?OutputHelper $outputHelper = null,
    ) {
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data,
            $outputHelper
        );
    }

    protected function _beforeToHtml()
    {
        $result = parent::_beforeToHtml();
        $collection = $this->_getProductCollection();
        $this->productHelper->addVendorsDataToProductsCollection($collection);
        return $result;
    }
}
