<?php
namespace ELogic\Vendors\Block\Search;

use ELogic\Vendors\Helper\Product as ProductHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\CatalogSearch\Helper\Data;
use Magento\Framework\View\Element\Template\Context;
use Magento\Search\Model\QueryFactory;

class Result extends \Magento\CatalogSearch\Block\Result
{
    /**
     * @param Context $context
     * @param Resolver $layerResolver
     * @param Data $catalogSearchData
     * @param QueryFactory $queryFactory
     * @param ProductHelper $productHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Resolver $layerResolver,
        Data $catalogSearchData,
        QueryFactory $queryFactory,
        private ProductHelper $productHelper,
        array $data = []
    ) {
        parent::__construct($context, $layerResolver, $catalogSearchData, $queryFactory, $data);
    }

    protected function _getProductCollection()
    {
        parent::_getProductCollection();
        $this->productHelper->addVendorsDataToProductsCollection($this->productCollection);
        return $this->productCollection;
    }
}
