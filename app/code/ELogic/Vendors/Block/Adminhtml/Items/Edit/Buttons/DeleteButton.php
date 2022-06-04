<?php

namespace ELogic\Vendors\Block\Adminhtml\Items\Edit\Buttons;

use ELogic\Vendors\Block\Adminhtml\Items\Edit\AbstractButton;
use JetBrains\PhpStorm\ArrayShape;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class DeleteButton extends AbstractButton implements ButtonProviderInterface
{
    /**
     * Delete button
     *
     * @return array
     */
    public function getButtonData()
    {
        $vendor = $this->getVendor();
        $vendorId = (int)$vendor?->getId();
        if (!empty($vendorId)) {
            return [
                'id' => 'delete',
                'label' => __('Delete'),
                'on_click' => "deleteConfirm('" .__('Are you sure you want to delete this vendor?') ."', '".$this->getDeleteUrl()."', {data: {}})",
                'class' => 'delete',
                'sort_order' => 10
            ];
        }
    }

    /**
     * @param array $args
     * @return string
     */
    public function getDeleteUrl(array $args = []): string
    {
        $params = array_merge($this->getDefaultUrlParams(), $args);
        return $this->getUrl('elogic_vendors/*/delete', $params);
    }

    /**
     * @return array
     */
    #[ArrayShape(['_current' => "bool", '_query' => "null[]"])]
    protected function getDefaultUrlParams()
    {
        return ['_current' => true, '_query' => ['isAjax' => null]];
    }
}
