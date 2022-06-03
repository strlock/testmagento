<?php
namespace ELogic\Vendors\Block\Adminhtml\Items\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;

/**
 * Back button configuration provider
 */
class BackButton implements ButtonProviderInterface
{
    /**
     * URL builder
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve button data
     *
     * @return array button configuration
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->urlBuilder->getUrl('elogic_vendors/items/index')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
