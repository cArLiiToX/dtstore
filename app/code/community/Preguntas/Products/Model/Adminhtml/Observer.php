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
 * Adminhtml observer
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the preguntas product tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Preguntas_Products_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addProductPreguntaBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'preguntas',
                array(
                    'label' => Mage::helper('preguntas_products')->__('Preguntas Products'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/products_pregunta_catalog_product/preguntas',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save preguntas product - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Preguntas_Products_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveProductPreguntaData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('preguntas', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $preguntaProduct = Mage::getResourceSingleton('preguntas_products/pregunta_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
