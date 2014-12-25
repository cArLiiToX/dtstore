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
 * Product helper
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Helper_Product extends Preguntas_Products_Helper_Data
{

    /**
     * get the selected preguntas products for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedPreguntas(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedPreguntas()) {
            $preguntas = array();
            foreach ($this->getSelectedPreguntasCollection($product) as $pregunta) {
                $preguntas[] = $pregunta;
            }
            $product->setSelectedPreguntas($preguntas);
        }
        return $product->getData('selected_preguntas');
    }

    /**
     * get preguntas product collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Preguntas_Products_Model_Resource_Pregunta_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedPreguntasCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('preguntas_products/pregunta_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
