<?php
namespace SM\MegaMenu\Model\Source;
class ListCmsPage implements \Magento\Framework\Option\ArrayInterface
{
    protected  $_blockModel;

    /**
     * @param \Magento\Cms\Model\Page $blockModel
     */
    public function __construct(
        \Magento\Cms\Model\Page $blockModel
    ) {
        $this->_groupModel = $blockModel;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->_groupModel->getCollection();
        $blocks = [];
        $blocks[] = ['value' => '', 'label' => __('-- Please Select CMS Page --')];
        foreach ($collection as $_block) {
            $blocks[] = [
                'value' => $_block->getIdentifier(),
                'label' => addslashes($_block->getTitle())
            ];
        }
        return $blocks;
    }
}