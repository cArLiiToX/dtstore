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
 * Preguntas Product model
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Model_Pregunta extends Mage_Core_Model_Abstract {

    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'preguntas_products_pregunta';
    const CACHE_TAG = 'preguntas_products_pregunta';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'preguntas_products_pregunta';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'pregunta';
    protected $_productInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct() {
        parent::_construct();
        $this->_init('preguntas_products/pregunta');
    }

    /**
     * before save preguntas product
     *
     * @access protected
     * @return Preguntas_Products_Model_Pregunta
     * @author Ultimate Module Creator
     */
    protected function _beforeSave() {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save preguntas product relation
     *
     * @access public
     * @return Preguntas_Products_Model_Pregunta
     * @author Ultimate Module Creator
     */
    protected function _afterSave() {
        $this->getProductInstance()->savePreguntaRelation($this);

        //var_dump($this);

        $sql = 'SELECT * from preguntas_products_pregunta_product where pregunta_id = ' . $this->_data["entity_id"];
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $Producto = $write->query($sql);

        $isSecure = Mage::app()->getStore()->isCurrentlySecure(); 
        $URL_SITE = Mage::getBaseUrl('web', $isSecure);
        while ($row = $Producto->fetch()) {

            $product = Mage::getModel('catalog/product')->load($row['product_id']);
            $url = explode('/',rtrim($product->getProductUrl(),"/"));
            
            if ($this->_data["respuesta"]) {
                $toAddresses = array('nikolaisan@hotmail.com', 'carlos@xng.bz', $this->_data["email"]);

                $from = 'nikolaisan@hotmail.com';
                // $this->_data["name"] . ' te envio una pregunta acerca del producto: <br /><b>' . $this->_data["name"] . '</b><br /><br /><br /><b>Pregunta:</b> ' . $_REQUEST['message'] . ' <br /><br /><b>Enviada el:</b> ' . date('Y-m-d H:i:s');
                $html = "La pregunta que has realizado en " . $product->getName() . " ya ha sido respondida. Puedes ver la respuesta dando click <a href='" . $URL_SITE.end($url) . ".html'>Aquí</a>";
                
// multiple recipients



                $subject = 'Han respondido tu pregunta sobre el producto ' . $product->getName() . ' - DT Store!';
                $message = $html;

                $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
                $headers .= "From: $from\r\n";
//$headers .= "BCC: mark@besthdeaths.com\r\n";

                foreach ($toAddresses as $to) {
                    if (mail($to, $subject, $message, $headers)) {
                        //echo "OK - sent message to {$to}";
                    }
                }
            }
        }


     
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return Preguntas_Products_Model_Pregunta_Product
     * @author Ultimate Module Creator
     */
    public function getProductInstance() {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('preguntas_products/pregunta_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedProducts() {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return Preguntas_Products_Resource_Pregunta_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedProductsCollection() {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }

}
