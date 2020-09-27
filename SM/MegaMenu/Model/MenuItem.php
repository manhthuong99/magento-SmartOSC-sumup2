<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\MegaMenu\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
//use SM\MegaMenu\Api\Data\MenuItemInterface;

class MenuItem extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'menu_item_b';
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;


    protected $_cacheTag = self::CACHE_TAG;

    protected $_eventPrefix = 'menu_iteam';

    protected function _construct()
    {
        $this->_init(\SM\MegaMenu\Model\ResourceModel\MenuItem::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_'];
    }
}
