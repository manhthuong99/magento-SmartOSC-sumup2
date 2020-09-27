<?php

namespace SM\MegaMenu\Block;

use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\View\Element\Template;
use SM\MegaMenu\Model\ResourceModel\MenuItem\CollectionFactory as MenuItemCollection;

class TopMenu extends \Magento\Theme\Block\Html\Topmenu
{
    protected $_registry;
    protected $_storeManager;

    function __construct(
        \Magento\Framework\Registry $registry,
        Template\Context $context,
        MenuItemCollection $menuItemCollection,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Escaper $escaper,
        array $data = [])
    {
        $this->_storeManager = $storeManager;
        $this->_escaper = $escaper;
        $this->_registry = $registry;
        $this->menuItemCollection = $menuItemCollection;
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
    }

    public function getMenuItem()
    {
        $a = $this->menuItemCollection->create()->addFieldToFilter("status", "1");
        return $a->getData();
    }
    public function getCategoryTree($categories, $parent_id)
    {
        $out = "";
        $cate_child = array();
        foreach ($categories as $key => $item) {
            if ($item->getParentId() == $parent_id) {
                $cate_child[] = $item;
//                unset($categories->$key);
            }
        }
        if ($cate_child) {
            $out .= "<ul>";
            foreach ($cate_child as $key => $item) {
                $out .= '<li class="has-sub"><a href="' . $item->getUrl() . '">' . $item->getName() . "</a>";
                $out .= $this->getCategoryTree($categories, $item->getId());
                $out .= '</li>';
            }
            $out .= '</ul>';
        }
        return $out;
    }
    public function getChildMenu($parentId){
        $objectManager = $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryFactory = $objectManager->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
        $categories = $categoryFactory->create()
            ->addAttributeToSelect('*')
            ->setStore($this->_storeManager->getStore());
        return  $this->getCategoryTree($categories,$parentId);
    }
}
