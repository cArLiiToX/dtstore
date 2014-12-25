<?php
/**
 * Preguntas_Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Preguntas
 * @package        Preguntas_Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Preguntas Product admin controller
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Adminhtml_Products_PreguntaController extends Preguntas_Products_Controller_Adminhtml_Products
{
    /**
     * init the preguntas product
     *
     * @access protected
     * @return Preguntas_Products_Model_Pregunta
     */
    protected function _initPregunta()
    {
        $preguntaId  = (int) $this->getRequest()->getParam('id');
        $pregunta    = Mage::getModel('preguntas_products/pregunta');
        if ($preguntaId) {
            $pregunta->load($preguntaId);
        }
        Mage::register('current_pregunta', $pregunta);
        return $pregunta;
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
        $this->_title(Mage::helper('preguntas_products')->__('Preguntas'))
             ->_title(Mage::helper('preguntas_products')->__('Preguntas Products'));
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
     * edit preguntas product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $preguntaId    = $this->getRequest()->getParam('id');
        $pregunta      = $this->_initPregunta();
        if ($preguntaId && !$pregunta->getId()) {
            $this->_getSession()->addError(
                Mage::helper('preguntas_products')->__('This preguntas product no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getPreguntaData(true);
        if (!empty($data)) {
            $pregunta->setData($data);
        }
        Mage::register('pregunta_data', $pregunta);
        $this->loadLayout();
        $this->_title(Mage::helper('preguntas_products')->__('Preguntas'))
             ->_title(Mage::helper('preguntas_products')->__('Preguntas Products'));
        if ($pregunta->getId()) {
            $this->_title($pregunta->getName());
        } else {
            $this->_title(Mage::helper('preguntas_products')->__('Add preguntas product'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new preguntas product action
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
     * save preguntas product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('pregunta')) {
            try {
                $pregunta = $this->_initPregunta();
                $pregunta->addData($data);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $pregunta->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $pregunta->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('preguntas_products')->__('Preguntas Product was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $pregunta->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setPreguntaData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('preguntas_products')->__('There was a problem saving the preguntas product.')
                );
                Mage::getSingleton('adminhtml/session')->setPreguntaData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('preguntas_products')->__('Unable to find preguntas product to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete preguntas product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $pregunta = Mage::getModel('preguntas_products/pregunta');
                $pregunta->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('preguntas_products')->__('Preguntas Product was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('preguntas_products')->__('There was an error deleting preguntas product.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('preguntas_products')->__('Could not find preguntas product to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete preguntas product - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $preguntaIds = $this->getRequest()->getParam('pregunta');
        if (!is_array($preguntaIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('preguntas_products')->__('Please select preguntas products to delete.')
            );
        } else {
            try {
                foreach ($preguntaIds as $preguntaId) {
                    $pregunta = Mage::getModel('preguntas_products/pregunta');
                    $pregunta->setId($preguntaId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('preguntas_products')->__('Total of %d preguntas products were successfully deleted.', count($preguntaIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('preguntas_products')->__('There was an error deleting preguntas products.')
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
        $preguntaIds = $this->getRequest()->getParam('pregunta');
        if (!is_array($preguntaIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('preguntas_products')->__('Please select preguntas products.')
            );
        } else {
            try {
                foreach ($preguntaIds as $preguntaId) {
                $pregunta = Mage::getSingleton('preguntas_products/pregunta')->load($preguntaId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d preguntas products were successfully updated.', count($preguntaIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('preguntas_products')->__('There was an error updating preguntas products.')
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
        $this->_initPregunta();
        $this->loadLayout();
        $this->getLayout()->getBlock('pregunta.edit.tab.product')
            ->setPreguntaProducts($this->getRequest()->getPost('pregunta_products', null));
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
        $this->_initPregunta();
        $this->loadLayout();
        $this->getLayout()->getBlock('pregunta.edit.tab.product')
            ->setPreguntaProducts($this->getRequest()->getPost('pregunta_products', null));
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
        $fileName   = 'pregunta.csv';
        $content    = $this->getLayout()->createBlock('preguntas_products/adminhtml_pregunta_grid')
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
        $fileName   = 'pregunta.xls';
        $content    = $this->getLayout()->createBlock('preguntas_products/adminhtml_pregunta_grid')
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
        $fileName   = 'pregunta.xml';
        $content    = $this->getLayout()->createBlock('preguntas_products/adminhtml_pregunta_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('preguntas_products/pregunta');
    }
}
