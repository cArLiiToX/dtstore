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
 * Preguntas Product product model
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Model_Pregunta_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('preguntas_products/pregunta_product');
    }

    /**
     * Save data for preguntas product-product relation
     * @access public
     * @param  Preguntas_Products_Model_Pregunta $pregunta
     * @return Preguntas_Products_Model_Pregunta_Product
     * @author Ultimate Module Creator
     */
    public function savePreguntaRelation($pregunta)
    {
        $data = $pregunta->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->savePreguntaRelation($pregunta, $data);
        }
        return $this;
    }

    /**
     * get products for preguntas product
     *
     * @access public
     * @param Preguntas_Products_Model_Pregunta $pregunta
     * @return Preguntas_Products_Model_Resource_Pregunta_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($pregunta)
    {
        $collection = Mage::getResourceModel('preguntas_products/pregunta_product_collection')
            ->addPreguntaFilter($pregunta);
        return $collection;
    }
}
