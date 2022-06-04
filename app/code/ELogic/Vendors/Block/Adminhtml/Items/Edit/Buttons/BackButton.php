<?php
namespace ELogic\Vendors\Block\Adminhtml\Items\Edit\Buttons;

use ELogic\Vendors\Block\Adminhtml\Items\Edit\AbstractButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Back button configuration provider
 */
class BackButton extends AbstractButton implements ButtonProviderInterface
{

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
