<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\MegaMenu\Model\ResourceModel\Menu;

use SM\MegaMenu\Api\Data\MenuIterface;
//use SM\MegaMenu\Helper\Categories;
//use \SM\MegaMenu\Model\ResourceModel\AbstractMenuCollection;
use Magento\Framework\App\ObjectManager;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'menu_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'menu';

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
        $this->_init(\SM\MegaMenu\Model\Menu::class, \SM\MegaMenu\Model\ResourceModel\Menu::class);
        $this->_map['fields']['menu_id'] = 'menu_id';
    }

}
