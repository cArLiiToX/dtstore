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
 * Preguntas Product list on product page block
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Block_Catalog_Product_List_Pregunta extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of preguntas products
     *
     * @access protected
     * @return Preguntas_Products_Model_Resource_Pregunta_Collection
     * @author Ultimate Module Creator
     */
    public function getPreguntaCollection()
    {
        if (!$this->hasData('pregunta_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('preguntas_products/pregunta_collection')
                ->addFieldToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('pregunta_collection', $collection);
        }
        return $this->getData('pregunta_collection');
    }
}
