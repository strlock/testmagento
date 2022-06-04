<?php
namespace ELogic\Vendors\Block\Adminhtml\Items\Edit;

use Magento\Backend\Block\Template;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Registry;

/**
 * Back button configuration provider
 */
abstract class AbstractButton extends Template implements ButtonProviderInterface
{
    /**
     * @param Template\Context $context
     * @param UrlInterface $urlBuilder
     * @param Registry $registry
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        Template\Context $context,
        protected UrlInterface $urlBuilder,
        protected Registry $registry,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null,
    ) {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    public function getVendor()
    {
        return $this->registry->registry('vendor');
    }
}
