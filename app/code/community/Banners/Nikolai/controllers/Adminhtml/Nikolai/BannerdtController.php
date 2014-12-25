<?php
/**
 * Banners_Nikolai extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Banners
 * @package        Banners_Nikolai
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Banner DT Store admin controller
 *
 * @category    Banners
 * @package     Banners_Nikolai
 * @author      Ultimate Module Creator
 */
class Banners_Nikolai_Adminhtml_Nikolai_BannerdtController extends Banners_Nikolai_Controller_Adminhtml_Nikolai
{
    /**
     * init the banner dt store
     *
     * @access protected
     * @return Banners_Nikolai_Model_Bannerdt
     */
    protected function _initBannerdt()
    {
        $bannerdtId  = (int) $this->getRequest()->getParam('id');
        $bannerdt    = Mage::getModel('banners_nikolai/bannerdt');
        if ($bannerdtId) {
            $bannerdt->load($bannerdtId);
        }
        Mage::register('current_bannerdt', $bannerdt);
        return $bannerdt;
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
        $this->_title(Mage::helper('banners_nikolai')->__('Banners DT Store'))
             ->_title(Mage::helper('banners_nikolai')->__('Banners DT Store'));
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
     * edit banner dt store - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $bannerdtId    = $this->getRequest()->getParam('id');
        $bannerdt      = $this->_initBannerdt();
        if ($bannerdtId && !$bannerdt->getId()) {
            $this->_getSession()->addError(
                Mage::helper('banners_nikolai')->__('This banner dt store no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getBannerdtData(true);
        if (!empty($data)) {
            $bannerdt->setData($data);
        }
        Mage::register('bannerdt_data', $bannerdt);
        $this->loadLayout();
        $this->_title(Mage::helper('banners_nikolai')->__('Banners DT Store'))
             ->_title(Mage::helper('banners_nikolai')->__('Banners DT Store'));
        if ($bannerdt->getId()) {
            $this->_title($bannerdt->getNombre());
        } else {
            $this->_title(Mage::helper('banners_nikolai')->__('Add banner dt store'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new banner dt store action
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
     * save banner dt store - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('bannerdt')) {
            try {
                $bannerdt = $this->_initBannerdt();
                $bannerdt->addData($data);
                $imageName = $this->_uploadAndGetName(
                    'image',
                    Mage::helper('banners_nikolai/bannerdt_image')->getImageBaseDir(),
                    $data
                );
                $bannerdt->setData('image', $imageName);
                $bannerdt->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('banners_nikolai')->__('Banner DT Store was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $bannerdt->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setBannerdtData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('banners_nikolai')->__('There was a problem saving the banner dt store.')
                );
                Mage::getSingleton('adminhtml/session')->setBannerdtData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('banners_nikolai')->__('Unable to find banner dt store to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete banner dt store - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $bannerdt = Mage::getModel('banners_nikolai/bannerdt');
                $bannerdt->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('banners_nikolai')->__('Banner DT Store was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('banners_nikolai')->__('There was an error deleting banner dt store.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('banners_nikolai')->__('Could not find banner dt store to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete banner dt store - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $bannerdtIds = $this->getRequest()->getParam('bannerdt');
        if (!is_array($bannerdtIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('banners_nikolai')->__('Please select banners dt store to delete.')
            );
        } else {
            try {
                foreach ($bannerdtIds as $bannerdtId) {
                    $bannerdt = Mage::getModel('banners_nikolai/bannerdt');
                    $bannerdt->setId($bannerdtId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('banners_nikolai')->__('Total of %d banners dt store were successfully deleted.', count($bannerdtIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('banners_nikolai')->__('There was an error deleting banners dt store.')
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
        $bannerdtIds = $this->getRequest()->getParam('bannerdt');
        if (!is_array($bannerdtIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('banners_nikolai')->__('Please select banners dt store.')
            );
        } else {
            try {
                foreach ($bannerdtIds as $bannerdtId) {
                $bannerdt = Mage::getSingleton('banners_nikolai/bannerdt')->load($bannerdtId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d banners dt store were successfully updated.', count($bannerdtIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('banners_nikolai')->__('There was an error updating banners dt store.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Seccion Banner change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massSeccionAction()
    {
        $bannerdtIds = $this->getRequest()->getParam('bannerdt');
        if (!is_array($bannerdtIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('banners_nikolai')->__('Please select banners dt store.')
            );
        } else {
            try {
                foreach ($bannerdtIds as $bannerdtId) {
                $bannerdt = Mage::getSingleton('banners_nikolai/bannerdt')->load($bannerdtId)
                    ->setSeccion($this->getRequest()->getParam('flag_seccion'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d banners dt store were successfully updated.', count($bannerdtIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('banners_nikolai')->__('There was an error updating banners dt store.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
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
        $fileName   = 'bannerdt.csv';
        $content    = $this->getLayout()->createBlock('banners_nikolai/adminhtml_bannerdt_grid')
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
        $fileName   = 'bannerdt.xls';
        $content    = $this->getLayout()->createBlock('banners_nikolai/adminhtml_bannerdt_grid')
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
        $fileName   = 'bannerdt.xml';
        $content    = $this->getLayout()->createBlock('banners_nikolai/adminhtml_bannerdt_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('banners_nikolai/bannerdt');
    }
}
