<?php
namespace ELogic\Vendors\Block;

use ELogic\Vendors\Helper\Product as ProductHelper;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use Magento\Catalog\Block\Product\ListProduct as CatalogListProductBlock;

class ListProduct extends CatalogListProductBlock
{
    /**
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $urlHelper
     * @param ProductHelper $productHelper
     * @param array $data
     * @param OutputHelper|null $outputHelper
     */
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

    /**
     * @return ListProduct
     */
    protected function _beforeToHtml()
    {
        $result = parent::_beforeToHtml();
        $this->productHelper->addVendorsDataToProductsCollection($this->_productCollection);
        return $result;
    }
}
