<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\MegaMenu\Controller\Adminhtml\MenuItem;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use SM\MegaMenu\Model\MenuItem;
use SM\MegaMenu\Model\MenuItemFactory;
use SM\MegaMenu\Model\ResourceModel\MenuItem\CollectionFactory as MenuItemCollection;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;


class Save extends \SM\MegaMenu\Controller\Adminhtml\MenuItem implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var MenuItemFactory
     */
    private $menuItemCollection;
    private $MenuItemFactory;
    protected $_storeManager;
    protected $categoryRepository;
    protected $cacheTypeList;
    protected $cacheFrontendPool;

    /**
     * @param TypeListInterface $cacheTypeList
     * @param Pool $cacheFrontendPool
     * @param MenuItemCollection $menuItemCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\CategoryRepository $categoryRepository
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param MenuItemFactory|null $MenuItemFactory
     */
    public function __construct(
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        MenuItemCollection $menuItemCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        MenuItemFactory $MenuItemFactory = null
    )
    {
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->menuItemCollection = $menuItemCollection;
        $this->_storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->dataPersistor = $dataPersistor;
        $this->MenuItemFactory = $MenuItemFactory
            ?: ObjectManager::getInstance()->get(MenuItemFactory::class);
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
            $id = $this->getRequest()->getPost('menu_item_id');
            $name = $this->getRequest()->getPost('name');
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = MenuItem::STATUS_ENABLED;
            }
            if (empty($data['menu_item_id'])) {
                $data['menu_item_id'] = null;
                $imgName = $this->getRequest()->getParam('image');
                if ($this->getRequest()->getParam('image') != null) {
                    $data['image'] = $imgName[0]['url'];
                }
            }
            if ($this->checkNameIsDuplicate($id, $name)) {
                $this->messageManager->addErrorMessage(__('This menu name already exists.'));
                return $resultRedirect->setPath('*/*/index');
            }

            $data['sub_width'] = $data['width_left'] + $data['width_content'] + $data['width_right'] + 20;

            if ($data['sub_width'] == 20) {
                $data['sub_width'] = 400;
            }
            if ($data['category']) {
                $data['link'] = $this->getCategoryUrl($data['category']);
            }
            $id = $this->getRequest()->getParam('menu_item_id');
            if ($id) {
                try {
                    //                    $model = $this->blockRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This menu item no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $model = $this->MenuItemFactory->create();
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the menu item.'));
                $this->dataPersistor->clear('menu_item');
                $this->flushCache();
                return $this->processCategoriesReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the menu item.'));
            }

            $this->dataPersistor->set('menu_item', $data);
            return $resultRedirect->setPath('mega/menuitem/edit', ['menu_item_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process and set the block return
     *
     * @param MenuItem $model
     * @param array $data
     * @param ResultInterface $resultRedirect
     * @return ResultInterface
     */
    private function processCategoriesReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect === 'continue') {
            $resultRedirect->setPath('mega/menuitem/edit', ['menu_item_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->MenuItemFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setStatus(MenuItem::STATUS_DISABLED);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the menu item.'));
            $this->dataPersistor->set('menu', $data);
            $resultRedirect->setPath('mega/menuitem/edit', ['menu_item_id' => $id]);
        }
        return $resultRedirect;
    }

    public function getCategoryUrl($categoryId)
    {
        $category = $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
        return $category->getUrl();
    }

    public function checkNameIsDuplicate($id, $name)
    {
        $check = true;
        $collection = $this->menuItemCollection->create()
            ->addFieldToFilter('name', $name);
        if (count($collection)) {
            $collection = $this->menuItemCollection->create()
                ->addFieldToFilter('name', $name)
                ->addFieldToFilter('menu_item_id', $id);
            if (count($collection)) {
                $check = false;
            } else return $check;
        } else {
            $check = false;
        }
        return $check;
    }

    public function flushCache()
    {
        $_types = [
            'config',
            'layout',
            'block_html',
            'collections',
            'reflection',
            'db_ddl',
            'eav',
            'config_integration',
            'config_integration_api',
            'full_page',
            'translate',
            'config_webservice'
        ];

        foreach ($_types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}

