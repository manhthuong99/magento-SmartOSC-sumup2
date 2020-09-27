<?php
namespace SM\MegaMenu\Block\Adminhtml;

class Menu extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {
        $this->_controller = 'adminhtml_menu';
        $this->_blockGroup = 'SM_MegaMenu';
        $this->_headerText = __('Menus');
        $this->_addButtonLabel = __('Create New Menu');
        parent::_construct();
    }
}