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
 * Amazon Product - product controller
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Amazon_Products_Adminhtml_Products_Amazon_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('Amazon_Products');
    }

    /**
     * amazon products in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function amazonsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.amazon')
            ->setProductAmazons($this->getRequest()->getPost('product_amazons', null));
        $this->renderLayout();
    }

    /**
     * amazon products grid in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function amazonsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.amazon')
            ->setProductAmazons($this->getRequest()->getPost('product_amazons', null));
        $this->renderLayout();
    }
}
