<?php
/**
 * amazon_products_Amazon Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       amazon_products
 * @package        amazon_products_Amazon Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Amazon Product admin controller
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Adminhtml_Amazon Products_AmazonproductController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Initialize requested amazon product and put it into registry.
     * Root amazon product can be returned, if inappropriate store/amazon product is specified
     *
     * @access protected
     * @param bool $getRootInstead
     * @return amazon_products_Amazon Products_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _initAmazonproduct($getRootInstead = false)
    {
        $this->_title($this->__('Amazon Products'))
             ->_title($this->__('Manage Amazon Products'));
        $amazonproductId = (int) $this->getRequest()->getParam('id', false);
        $storeId    = (int) $this->getRequest()->getParam('store');
        $amazonproduct = Mage::getModel('amazon_products_amazon products/amazonproduct');
        $amazonproduct->setStoreId($storeId);

        if ($amazonproductId) {
            $amazonproduct->load($amazonproductId);
            if ($storeId) {
                $rootId = Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId();
                if (!in_array($rootId, $amazonproduct->getPathIds())) {
                    // load root amazon product instead wrong one
                    if ($getRootInstead) {
                        $amazonproduct->load($rootId);
                    } else {
                        $this->_redirect('*/*/', array('_current'=>true, 'id'=>null));
                        return false;
                    }
                }
            }
        }

        if ($activeTabId = (string) $this->getRequest()->getParam('active_tab_id')) {
            Mage::getSingleton('admin/session')->setAmazonproductActiveTabId($activeTabId);
        }

        Mage::register('amazonproduct', $amazonproduct);
        Mage::register('current_amazonproduct', $amazonproduct);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $amazonproduct;
    }

    /**
     * index action
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->_forward('edit');
    }

    /**
     * Add new amazon product form
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function addAction()
    {
        Mage::getSingleton('admin/session')->unsAmazonproductActiveTabId();
        $this->_forward('edit');
    }

    /**
     * Edit amazon product page
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $params['_current'] = true;
        $redirect = false;

        $storeId = (int) $this->getRequest()->getParam('store');
        $parentId = (int) $this->getRequest()->getParam('parent');
        $_prevStoreId = Mage::getSingleton('admin/session')
            ->getAmazonproductLastViewedStore(true);

        if (!empty($_prevStoreId) && !$this->getRequest()->getQuery('isAjax')) {
            $params['store'] = $_prevStoreId;
            $redirect = true;
        }

        $amazonproductId = (int) $this->getRequest()->getParam('id');
        $_prevAmazonproductId = Mage::getSingleton('admin/session')
            ->getLastEditedAmazonproduct(true);


        if ($_prevAmazonproductId
            && !$this->getRequest()->getQuery('isAjax')
            && !$this->getRequest()->getParam('clear')) {
             $this->getRequest()->setParam('id', $_prevAmazonproductId);
        }

        if ($redirect) {
            $this->_redirect('*/*/edit', $params);
            return;
        }

        if ($storeId && !$amazonproductId && !$parentId) {
            $store = Mage::app()->getStore($storeId);
            $_prevAmazonproductId = (int)Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId();
            $this->getRequest()->setParam('id', $_prevAmazonproductId);
        }

        if (!($amazonproduct = $this->_initAmazonproduct())) {
            return;
        }

        $this->_title($amazonproductId ? $amazonproduct->getName() : $this->__('New Amazon Product'));

        $data = Mage::getSingleton('adminhtml/session')->getAmazonproductData(true);
        if (isset($data['amazonproduct'])) {
            $amazonproduct->addData($data['amazonproduct']);
        }

        /**
         * Build response for ajax request
         */
        if ($this->getRequest()->getQuery('isAjax')) {
            $breadcrumbsPath = $amazonproduct->getPath();
            if (empty($breadcrumbsPath)) {
                $breadcrumbsPath = Mage::getSingleton('admin/session')->getAmazonproductDeletedPath(true);
                if (!empty($breadcrumbsPath)) {
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    if (count($breadcrumbsPath) <= 1) {
                        $breadcrumbsPath = '';
                    } else {
                        array_pop($breadcrumbsPath);
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }

            Mage::getSingleton('admin/session')
                ->setAmazonproductLastViewedStore($this->getRequest()->getParam('store'));
            Mage::getSingleton('admin/session')
                ->setLastEditedAmazonproduct($amazonproduct->getId());
            $this->loadLayout();

            $eventResponse = new Varien_Object(
                array(
                    'content' => $this->getLayout()->getBlock('amazonproduct.edit')->getFormHtml()
                        . $this->getLayout()->getBlock('amazonproduct.tree')
                        ->getBreadcrumbsJavascript($breadcrumbsPath, 'editingAmazonproductBreadcrumbs'),
                    'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
                )
            );

            Mage::dispatchEvent(
                'amazonproduct_prepare_ajax_response',
                array(
                    'response' => $eventResponse,
                    'controller' => $this
                )
            );

            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode($eventResponse->getData())
            );

            return;
        }

        $this->loadLayout();
        $this->_setActiveMenu('amazon_products_amazon products/amazonproduct');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
            ->setContainerCssClass('amazonproducts');

        $this->_addBreadcrumb(
            Mage::helper('amazon_products_amazon products')->__('Manage Amazon Products'),
            Mage::helper('catalog')->__('Manage Amazon Products')
        );

        $block = $this->getLayout()->getBlock('catalog.wysiwyg.js');
        if ($block) {
            $block->setStoreId($storeId);
        }

        $this->renderLayout();
    }

    /**
     * WYSIWYG editor action for ajax request
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function wysiwygAction()
    {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'adminhtml/catalog_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );

        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * Get tree node (Ajax version)
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function amazonproductsJsonAction()
    {
        if ($this->getRequest()->getParam('expand_all')) {
            Mage::getSingleton('admin/session')->setAmazonproductIsTreeWasExpanded(true);
        } else {
            Mage::getSingleton('admin/session')->setAmazonproductIsTreeWasExpanded(false);
        }
        if ($amazonproductId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $amazonproductId);

            if (!$amazonproduct = $this->_initAmazonproduct()) {
                return;
            }
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('amazon_products_amazon products/adminhtml_amazonproduct_tree')
                    ->getTreeJson($amazonproduct)
            );
        }
    }

    /**
     * Amazon Product save
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if (!$amazonproduct = $this->_initAmazonproduct()) {
            return;
        }

        $storeId = $this->getRequest()->getParam('store');
        $refreshTree = 'false';
        if ($data = $this->getRequest()->getPost()) {
            $amazonproduct->addData($data['amazonproduct']);
            if (!$amazonproduct->getId()) {
                $parentId = $this->getRequest()->getParam('parent');
                if (!$parentId) {
                    $parentId = Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId();
                }
                $parentAmazonproduct = Mage::getModel('amazon_products_amazon products/amazonproduct')->load($parentId);
                $amazonproduct->setPath($parentAmazonproduct->getPath());
            }

            /**
             * Process "Use Config Settings" checkboxes
             */
            if ($useConfig = $this->getRequest()->getPost('use_config')) {
                foreach ($useConfig as $attributeCode) {
                    $amazonproduct->setData($attributeCode, null);
                }
            }

            $amazonproduct->setAttributeSetId($amazonproduct->getDefaultAttributeSetId());

            Mage::dispatchEvent(
                'amazon_products_amazon products_amazonproduct_prepare_save',
                array(
                    'amazonproduct' => $amazonproduct,
                    'request' => $this->getRequest()
                )
            );

            $amazonproduct->setData("use_post_data_config", $this->getRequest()->getPost('use_config'));

            try {
                $products = $this->getRequest()->getPost('amazonproduct_products', -1);
                if ($products != -1) {
                    $productData = array();
                    parse_str($products, $productData);
                    $products = array();
                    foreach ($productData as $id => $position) {
                        $products[$id]['position'] = $position;
                    }
                    $amazonproduct->setProductsData($productData);
                }
                /**
                 * Check "Use Default Value" checkboxes values
                 */
                if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                    foreach ($useDefaults as $attributeCode) {
                        $amazonproduct->setData($attributeCode, false);
                    }
                }

                /**
                 * Unset $_POST['use_config'] before save
                 */
                $amazonproduct->unsetData('use_post_data_config');

                $amazonproduct->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('amazon_products_amazon products')->__('The amazon product has been saved.')
                );
                $refreshTree = 'true';
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                    ->setAmazonproductData($data);
                $refreshTree = 'false';
            }
        }
        $url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $amazonproduct->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );
    }

    /**
     * Move amazon product action
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function moveAction()
    {
        $amazonproduct = $this->_initAmazonproduct();
        if (!$amazonproduct) {
            $this->getResponse()->setBody(
                Mage::helper('amazon_products_amazon products')->__('Amazon Product move error')
            );
            return;
        }
        $parentNodeId   = $this->getRequest()->getPost('pid', false);
        $prevNodeId     = $this->getRequest()->getPost('aid', false);

        try {
            $amazonproduct->move($parentNodeId, $prevNodeId);
            $this->getResponse()->setBody("SUCCESS");
        } catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        } catch (Exception $e) {
            $this->getResponse()->setBody(
                Mage::helper('amazon_products_amazon products')->__('Amazon Product move error')
            );
            Mage::logException($e);
        }

    }

    /**
     * Delete amazon product action
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            try {
                $amazonproduct = Mage::getModel('amazon_products_amazon products/amazonproduct')->load($id);
                Mage::dispatchEvent(
                    'amazon_products_amazon products_controller_amazonproduct_delete',
                    array('amazonproduct' => $amazonproduct)
                );

                Mage::getSingleton('admin/session')->setAmazonproductDeletedPath($amazonproduct->getPath());

                $amazonproduct->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('amazon_products_amazon products')->__('The amazon product has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('amazon_products_amazon products')->__('An error occurred while trying to delete the amazon product.')
                );
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('_current'=>true, 'id'=>null)));
    }

    /**
     * Tree Action
     * Retrieve amazon product tree
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function treeAction()
    {
        $storeId = (int) $this->getRequest()->getParam('store');
        $amazonproductId = (int) $this->getRequest()->getParam('id');

        if ($storeId) {
            if (!$amazonproductId) {
                $store = Mage::app()->getStore($storeId);
                $rootId = Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId();
                $this->getRequest()->setParam('id', $rootId);
            }
        }

        $amazonproduct = $this->_initAmazonproduct();

        $block = $this->getLayout()->createBlock('amazon_products_amazon products/adminhtml_amazonproduct_tree');
        $root  = $block->getRoot();
        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode(
                array(
                    'data' => $block->getTree(),
                    'parameters' => array(
                        'text'         => $block->buildNodeName($root),
                        'draggable'    => false,
                        'allowDrop'    => ($root->getIsVisible()) ? true : false,
                        'id'           => (int) $root->getId(),
                        'expanded'     => (int) $block->getIsWasExpanded(),
                        'store_id'     => (int) $block->getStore()->getId(),
                        'amazonproduct_id' => (int) $amazonproduct->getId(),
                        'root_visible' => (int) $root->getIsVisible()
                    )
                )
            )
        );
    }

   /**
    * Build response for refresh input element 'path' in form
    *
    * @access public
    * @author Ultimate Module Creator
    */
    public function refreshPathAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            $amazonproduct = Mage::getModel('amazon_products_amazon products/amazonproduct')->load($id);
            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode(
                    array(
                       'id' => $id,
                       'path' => $amazonproduct->getPath(),
                    )
                )
            );
        }
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('amazon_products_amazon products/amazonproduct');
    }

    /**
     * get the products grid
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsgridAction()
    {
        if (!$amazonproduct = $this->_initAmazonproduct()) {
            return;
        }
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock(
                'amazon_products_amazon products/adminhtml_amazonproduct_edit_tab_product',
                'amazonproduct.product.grid'
            )
            ->toHtml()
        );
    }
}
