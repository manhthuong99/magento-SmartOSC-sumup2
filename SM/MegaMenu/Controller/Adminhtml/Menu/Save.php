<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\MegaMenu\Controller\Adminhtml\Menu;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use SM\MegaMenu\Model\Menu;
use SM\MegaMenu\Model\MenuFactory;

/**
 * Save CMS Categories action.
 */
class Save extends \SM\MegaMenu\Controller\Adminhtml\Menu implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var MenuFactory
     */
    private $MenuFactory;


    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param MenuFactory|null $MenuFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        MenuFactory $MenuFactory = null
    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->MenuFactory = $MenuFactory
            ?: ObjectManager::getInstance()->get(MenuFactory::class);
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        /* @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Menu::STATUS_ENABLED;
            }
            if (empty($data['menu_id'])) {
                $data['menu_id'] = null;
            }

            /* @var $categories $model */
            $model = $this->MenuFactory->create();
            $id = $this->getRequest()->getParam('menu_id');
            if ($id) {
                try {
                    //                    $model = $this->blockRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This menu no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the menu.'));
                $this->dataPersistor->clear('menu');
                return $this->processCategoriesReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the menu.'));
            }

            $this->dataPersistor->set('menu', $data);
            return $resultRedirect->setPath('mega/menu/edit', ['menu_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process and set the block return
     *
     * @param Menu $model
     * @param array $data
     * @param ResultInterface $resultRedirect
     * @return ResultInterface
     */
    private function processCategoriesReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect === 'continue') {
            $resultRedirect->setPath('mega/menu/edit', ['menu_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->MenuFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setStatus(Menu::STATUS_DISABLED);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the menu.'));
            $this->dataPersistor->set('menu', $data);
            $resultRedirect->setPath('mega/menu/edit', ['menu_id' => $id]);
        }
        return $resultRedirect;
    }
}

