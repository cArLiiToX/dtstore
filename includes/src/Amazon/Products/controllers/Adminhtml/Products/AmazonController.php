<?php
/**
 * Amazon_Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Amazon
 * @package        Amazon_Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Amazon Product admin controller
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Adminhtml_Products_AmazonController extends Amazon_Products_Controller_Adminhtml_Products
{
    /**
     * init the amazon product
     *
     * @access protected
     * @return Amazon_Products_Model_Amazon
     */
    protected function _initAmazon()
    {
        $amazonId  = (int) $this->getRequest()->getParam('id');
        $amazon    = Mage::getModel('amazon_products/amazon');
        if ($amazonId) {
            $amazon->load($amazonId);
        }
        Mage::register('current_amazon', $amazon);
        return $amazon;
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
        $this->loadLayout();
        $this->_title(Mage::helper('amazon_products')->__('Amazon Products'))
             ->_title(Mage::helper('amazon_products')->__('Amazon Products'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit amazon product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $amazonId    = $this->getRequest()->getParam('id');
        $amazon      = $this->_initAmazon();
        if ($amazonId && !$amazon->getId()) {
            $this->_getSession()->addError(
                Mage::helper('amazon_products')->__('This amazon product no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getAmazonData(true);
        if (!empty($data)) {
            $amazon->setData($data);
        }
        Mage::register('amazon_data', $amazon);
        $this->loadLayout();
        $this->_title(Mage::helper('amazon_products')->__('Amazon Products'))
             ->_title(Mage::helper('amazon_products')->__('Amazon Products'));
        if ($amazon->getId()) {
            $this->_title($amazon->getName());
        } else {
            $this->_title(Mage::helper('amazon_products')->__('Add amazon product'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new amazon product action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save amazon product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('amazon')) {
            try {
                $amazon = $this->_initAmazon();
                $amazon->addData($data);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $amazon->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $amazon->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('amazon_products')->__('Amazon Product was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $amazon->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setAmazonData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('amazon_products')->__('There was a problem saving the amazon product.')
                );
                Mage::getSingleton('adminhtml/session')->setAmazonData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('amazon_products')->__('Unable to find amazon product to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete amazon product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $amazon = Mage::getModel('amazon_products/amazon');
                $amazon->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('amazon_products')->__('Amazon Product was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('amazon_products')->__('There was an error deleting amazon product.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('amazon_products')->__('Could not find amazon product to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete amazon product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $amazonIds = $this->getRequest()->getParam('amazon');
        if (!is_array($amazonIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('amazon_products')->__('Please select amazon products to delete.')
            );
        } else {
            try {
                foreach ($amazonIds as $amazonId) {
                    $amazon = Mage::getModel('amazon_products/amazon');
                    $amazon->setId($amazonId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('amazon_products')->__('Total of %d amazon products were successfully deleted.', count($amazonIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('amazon_products')->__('There was an error deleting amazon products.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $amazonIds = $this->getRequest()->getParam('amazon');
        if (!is_array($amazonIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('amazon_products')->__('Please select amazon products.')
            );
        } else {
            try {
                foreach ($amazonIds as $amazonId) {
                $amazon = Mage::getSingleton('amazon_products/amazon')->load($amazonId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d amazon products were successfully updated.', count($amazonIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('amazon_products')->__('There was an error updating amazon products.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsAction()
    {
        $this->_initAmazon();
        $this->loadLayout();
        $this->getLayout()->getBlock('amazon.edit.tab.product')
            ->setAmazonProducts($this->getRequest()->getPost('amazon_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsgridAction()
    {
        $this->_initAmazon();
        $this->loadLayout();
        $this->getLayout()->getBlock('amazon.edit.tab.product')
            ->setAmazonProducts($this->getRequest()->getPost('amazon_products', null));
        $this->renderLayout();
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'amazon.csv';
        $content    = $this->getLayout()->createBlock('amazon_products/adminhtml_amazon_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'amazon.xls';
        $content    = $this->getLayout()->createBlock('amazon_products/adminhtml_amazon_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'amazon.xml';
        $content    = $this->getLayout()->createBlock('amazon_products/adminhtml_amazon_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
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
        return Mage::getSingleton('admin/session')->isAllowed('amazon_products/amazon');
    }
}
