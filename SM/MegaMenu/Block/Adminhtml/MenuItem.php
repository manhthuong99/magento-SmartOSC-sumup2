<?php
namespace SM\MegaMenu\Block\Adminhtml;

class MenuItem extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {
        $this->_controller = 'adminhtml_menuitem';
        $this->_blockGroup = 'SM_MegaMenu';
        $this->_headerText = __('Menu Item');
        $this->_addButtonLabel = __('Create New Menu Item');
        parent::_construct();
    }
}