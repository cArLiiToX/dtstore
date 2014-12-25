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
 * Preguntas Product - product controller
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Preguntas_Products_Adminhtml_Products_Pregunta_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Preguntas_Products');
    }

    /**
     * preguntas products in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function preguntasAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.pregunta')
            ->setProductPreguntas($this->getRequest()->getPost('product_preguntas', null));
        $this->renderLayout();
    }

    /**
     * preguntas products grid in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function preguntasGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.pregunta')
            ->setProductPreguntas($this->getRequest()->getPost('product_preguntas', null));
        $this->renderLayout();
    }
}
