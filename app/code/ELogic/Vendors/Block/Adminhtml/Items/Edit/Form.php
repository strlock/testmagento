<?php
namespace ELogic\Vendors\Block\Adminhtml\Items\Edit;

use \Magento\Backend\Block\Widget\Form\Generic as FormGeneric;

class Form extends FormGeneric
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('vendors_items_form');
        $this->setTitle(__('Item Information'));
    }

    protected function _prepareForm()
    {
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('elogic_vendors/items/save'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ]
        ]);
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
