<?php

namespace SM\MegaMenu\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
//use SM\MegaMenu\Api\Data\MenuInterface;


/**
 * @package SM\Menu\Model\ResourceModel
 */
class Menu extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mega_menu', 'menu_id');
    }
//    protected function _afterLoad(AbstractModel $menu)
//    {
//        /* @var MenuInterface $menu */
//
//        $menu->setStoreIds($this->getStoreIds($menu));
//        return parent::_afterLoad($menu);
//    }
//    private function getStoreIds(MenuInterface $model)
//    {
//        $connection = $this->getConnection();
//
//        $select = $connection->select()->from(
//            $this->getTable('mega_menu_store'),
//            'store_id'
//        )->where(
//            'menu_id = ?',
//            (int)$model->getId()
//        );
//
//        return $connection->fetchCol($select);
//    }
//    protected function _afterSave(AbstractModel $value)
//    {
//        /* @var MenuInterface $value */
//        $this->saveStoreIds($value);
//
//        return parent::_afterSave($value);
//    }
//    private function saveStoreIds(\SM\MegaMenu\Model\Menu $model)
//    {
//        $connection = $this->getConnection();
//
//        $table = $this->getTable('mega_menu_store');
//
//        if (!$model->getStoreIds()) {
//            return $this;
//        }
//
//        $storeIds = $model->getStoreIds();
//        $oldStoreIds = $this->getStoreIds($model);
//
//        $insert = array_diff((array)$storeIds, $oldStoreIds);
//        $delete = array_diff($oldStoreIds, (array)$storeIds);
//
//        if (!empty($insert)) {
//            $data = [];
//            foreach ($insert as $storeId) {
//                if (empty($storeId)) {
//                    continue;
//                }
//
//                $data[] = [
//                    'store_id' => (int)$storeId,
//                    'menu_id' => (int)$model->getId(),
//                ];
//            }
//
//            if ($data) {
//                $connection->insertMultiple($table, $data);
//            }
//        }
//
//        if (!empty($delete)) {
//            foreach ($delete as $storeId) {
//                $where = ['menu_id = ?' => (int)$model->getId(), 'store_id = ?' => (int)$storeId];
//                $connection->delete($table, $where);
//            }
//        }
//
//        return $this;
//    }
}
