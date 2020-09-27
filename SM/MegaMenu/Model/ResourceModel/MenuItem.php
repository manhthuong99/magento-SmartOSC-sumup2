<?php

namespace SM\MegaMenu\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @package SM\Menu\Model\ResourceModel
 */
class MenuItem extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mega_menu_item', 'menu_item_id');
    }
}
