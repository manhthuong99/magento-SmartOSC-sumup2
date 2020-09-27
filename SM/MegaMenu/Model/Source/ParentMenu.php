<?php


namespace SM\MegaMenu\Model\Source;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Option\ArrayInterface;
use SM\MegaMenu\Model\MenuFactory;


class ParentMenu implements ArrayInterface
{
    /**
     * @var MenuFactory
     */
    public $_menuFactory;

    public function __construct(
        MenuFactory $categoriesFactory
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
        foreach ($this->getMenu() as $value => $categories) {
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
    public function getMenu()
    {
        return $this->_menuFactory->create()->getCollection()->addFieldToFilter('status', '1');
    }
}
