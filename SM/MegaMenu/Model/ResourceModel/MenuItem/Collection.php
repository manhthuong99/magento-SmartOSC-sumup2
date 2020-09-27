<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\MegaMenu\Model\ResourceModel\MenuItem;

use Magento\Framework\App\ObjectManager;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'menu_item_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'menu_item';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'menu';


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SM\MegaMenu\Model\MenuItem::class, \SM\MegaMenu\Model\ResourceModel\MenuItem::class);
        $this->_map['fields']['menu_item_id'] = 'menu_item_id';
    }

}
