<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\MegaMenu\Controller\Adminhtml\Menu;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends \SM\MegaMenu\Controller\Adminhtml\Menu implements HttpPostActionInterface
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('menu_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\SM\MegaMenu\Model\Menu::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the menu.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('mega/menu/edit', ['menu_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a menu to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
