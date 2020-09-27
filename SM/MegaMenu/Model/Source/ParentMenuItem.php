<?php


namespace SM\MegaMenu\Model\Source;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Option\ArrayInterface;
use SM\MegaMenu\Model\MenuItemFactory;


class ParentMenuItem implements ArrayInterface
{
    /**
     * @var MenuItemFactory
     */
    public $_menuFactory;

    public function __construct(
        MenuItemFactory $categoriesFactory
    )
    {
        $this->_menuFactory = $categoriesFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = ['value' => '0', 'label' => __('ROOT')];
        foreach ($this->getMenuItem() as $value => $categories) {
            $options[] = [
                'value' => $value,
                'label' => $categories->getName()
            ];
        }

        return $options;
    }

    /**Collection
     * @return AbstractCollection
     */
    public function getMenuItem()
    {
        return $this->_menuFactory->create()->getCollection()->addFieldToFilter('status', '1');
    }
}
