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
 * Amazon Product - product controller
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class amazon_products_Amazon Products_Adminhtml_Amazon products_Amazonproduct_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{

    /**
     * amazon products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function amazonproductsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * amazon products json action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function amazonproductsJsonAction()
    {
        $product = $this->_initProduct();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock(
                'amazon_products_amazon products/adminhtml_catalog_product_edit_tab_amazonproduct'
            )
            ->getAmazonproductChildrenJson($this->getRequest()->getParam('amazonproduct'))
        );
    }
}
