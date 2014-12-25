<?php
/**
 * amazonProducts_AmazonProducts extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       amazonProducts
 * @package        amazonProducts_AmazonProducts
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Amazon Product admin controller
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Adminhtml_Amazonproducts_AmazonproductController extends amazonProducts_AmazonProducts_Controller_Adminhtml_AmazonProducts
{
    /**
     * init amazon product
     *
     * @access protected
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _initAmazonproduct()
    {
        $amazonproductId = (int) $this->getRequest()->getParam('id', false);
        $amazonproduct = Mage::getModel('amazonproducts_amazonproducts/amazonproduct');
        if ($amazonproductId) {
            $amazonproduct->load($amazonproductId);
        } else {
            $amazonproduct->setData($amazonproduct->getDefaultValues());
        }
        if ($activeTabId = (string) $this->getRequest()->getParam('active_tab_id')) {
            Mage::getSingleton('admin/session')->setAmazonproductActiveTabId($activeTabId);
        }
        Mage::register('amazonproduct', $amazonproduct);
        Mage::register('current_amazonproduct', $amazonproduct);
        return $amazonproduct;
    }

    /**
     * default action
     *
     * @access public
     * @return void
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
     * @return void
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
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $params['_current'] = true;
        $redirect = false;
        $parentId = (int) $this->getRequest()->getParam('parent');
        $amazonproductId = (int) $this->getRequest()->getParam('id');
        $_prevAmazonproductId = Mage::getSingleton('admin/session')->getLastEditedAmazonproduct(true);
        if ($_prevAmazonproductId &&
            !$this->getRequest()->getQuery('isAjax') &&
            !$this->getRequest()->getParam('clear')) {
            $this->getRequest()->setParam('id', $_prevAmazonproductId);
        }
        if ($redirect) {
            $this->_redirect('*/*/edit', $params);
            return;
        }
        if (!($amazonproduct = $this->_initAmazonproduct())) {
            return;
        }
        $this->_title($amazonproductId ? $amazonproduct->getName() : $this->__('New Amazon Product'));
        $data = Mage::getSingleton('adminhtml/session')->getAmazonproductData(true);
        if (isset($data['amazonproduct'])) {
            $amazonproduct->addData($data['amazonproduct']);
        }
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
            Mage::getSingleton('admin/session')->setLastEditedAmazonproduct($amazonproduct->getId());
            $this->loadLayout();
            $eventResponse = new Varien_Object(
                array(
                    'content' => $this->getLayout()->getBlock('amazonproduct.edit')->getFormHtml().
                        $this->getLayout()->getBlock('amazonproduct.tree')->getBreadcrumbsJavascript(
                            $breadcrumbsPath,
                            'editingAmazonproductBreadcrumbs'
                        ),
                    'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
                )
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($eventResponse->getData()));
            return;
        }
        $this->loadLayout();
        $this->_title(Mage::helper('amazonproducts_amazonproducts')->__('Amazon Products'))
             ->_title(Mage::helper('amazonproducts_amazonproducts')->__('Amazon Products'));
        $this->_setActiveMenu('amazonproducts_amazonproducts/amazonproduct');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
            ->setContainerCssClass('amazonproduct');

        $this->_addBreadcrumb(
            Mage::helper('amazonproducts_amazonproducts')->__('Manage Amazon Products'),
            Mage::helper('amazonproducts_amazonproducts')->__('Manage Amazon Products')
        );
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * Get tree node (Ajax version)
     *
     * @access public
     * @return void
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
                $this->getLayout()->createBlock('amazonproducts_amazonproducts/adminhtml_amazonproduct_tree')
                    ->getTreeJson($amazonproduct)
            );
        }
    }

    /**
     * Move amazon product action
     * @access public
     * @author Ultimate Module Creator
     */
    public function moveAction()
    {
        $amazonproduct = $this->_initAmazonproduct();
        if (!$amazonproduct) {
            $this->getResponse()->setBody(
                Mage::helper('amazonproducts_amazonproducts')->__('Amazon Product move error')
            );
            return;
        }
        $parentNodeId   = $this->getRequest()->getPost('pid', false);
        $prevNodeId = $this->getRequest()->getPost('aid', false);
        try {
            $amazonproduct->move($parentNodeId, $prevNodeId);
            $this->getResponse()->setBody("SUCCESS");
        } catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        } catch (Exception $e) {
            $this->getResponse()->setBody(
                Mage::helper('amazonproducts_amazonproducts')->__('Amazon Product move error')
            );
            Mage::logException($e);
        }
    }

    /**
     * Tree Action
     * Retrieve amazon product tree
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function treeAction()
    {
        $amazonproductId = (int) $this->getRequest()->getParam('id');
        $amazonproduct = $this->_initAmazonproduct();
        $block = $this->getLayout()->createBlock('amazonproducts_amazonproducts/adminhtml_amazonproduct_tree');
        $root  = $block->getRoot();
        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode(
                array(
                    'data' => $block->getTree(),
                    'parameters' => array(
                        'text'          => $block->buildNodeName($root),
                        'draggable'     => false,
                        'allowDrop'     => ($root->getIsVisible()) ? true : false,
                        'id'            => (int) $root->getId(),
                        'expanded'      => (int) $block->getIsWasExpanded(),
                        'amazonproduct_id' => (int) $amazonproduct->getId(),
                        'root_visible'  => (int) $root->getIsVisible()
                    )
                )
            )
        );
    }

    /**
     * Build response for refresh input element 'path' in form
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function refreshPathAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            $amazonproduct = Mage::getModel('amazonproducts_amazonproducts/amazonproduct')->load($id);
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
     * Delete amazon product action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            try {
                $amazonproduct = Mage::getModel('amazonproducts_amazonproducts/amazonproduct')->load($id);
                Mage::getSingleton('admin/session')->setAmazonproductDeletedPath($amazonproduct->getPath());

                $amazonproduct->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('amazonproducts_amazonproducts')->__('The amazon product has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('amazonproducts_amazonproducts')->__('An error occurred while trying to delete the amazon product.')
                );
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                Mage::logException($e);
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('_current'=>true, 'id'=>null)));
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
        return Mage::getSingleton('admin/session')->isAllowed('amazonproducts_amazonproducts/amazonproduct');
    }

    /**
     * Amazon Product save action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if (!$amazonproduct = $this->_initAmazonproduct()) {
            return;
        }
        $refreshTree = 'false';
        if ($data = $this->getRequest()->getPost('amazonproduct')) {
            $amazonproduct->addData($data);
            if (!$amazonproduct->getId()) {
                $parentId = $this->getRequest()->getParam('parent');
                if (!$parentId) {
                    $parentId = Mage::helper('amazonproducts_amazonproducts/amazonproduct')->getRootAmazonproductId();
                }
                $parentAmazonproduct = Mage::getModel('amazonproducts_amazonproducts/amazonproduct')->load($parentId);
                $amazonproduct->setPath($parentAmazonproduct->getPath());
            }
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

                $amazonproduct->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('amazonproducts_amazonproducts')->__('The amazon product has been saved.')
                );
                $refreshTree = 'true';
            }
            catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage())->setAmazonproductData($data);
                Mage::logException($e);
                $refreshTree = 'false';
            }
        }
        $url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $amazonproduct->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );
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
                'amazonproducts_amazonproducts/adminhtml_amazonproduct_edit_tab_product',
                'amazonproduct.product.grid'
            )
            ->toHtml()
        );
    }
}
