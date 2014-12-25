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
 * Preguntas Product - product relation model
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Model_Resource_Pregunta_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Ultimate Module Creator
     */
    protected function  _construct()
    {
        $this->_init('preguntas_products/pregunta_product', 'rel_id');
    }
    /**
     * Save preguntas product - product relations
     *
     * @access public
     * @param Preguntas_Products_Model_Pregunta $pregunta
     * @param array $data
     * @return Preguntas_Products_Model_Resource_Pregunta_Product
     * @author Ultimate Module Creator
     */
    public function savePreguntaRelation($pregunta, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('pregunta_id=?', $pregunta->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'pregunta_id' => $pregunta->getId(),
                    'product_id'    => $productId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - preguntas product relations
     *
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return Preguntas_Products_Model_Resource_Pregunta_Product
     * @@author Ultimate Module Creator
     */
    public function saveProductRelation($product, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $preguntaId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'pregunta_id' => $preguntaId,
                    'product_id'    => $product->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
