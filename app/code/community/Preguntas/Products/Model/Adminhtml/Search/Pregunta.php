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
 * Admin search model
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Model_Adminhtml_Search_Pregunta extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Preguntas_Products_Model_Adminhtml_Search_Pregunta
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('preguntas_products/pregunta_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $pregunta) {
            $arr[] = array(
                'id'          => 'pregunta/1/'.$pregunta->getId(),
                'type'        => Mage::helper('preguntas_products')->__('Preguntas Product'),
                'name'        => $pregunta->getName(),
                'description' => $pregunta->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/products_pregunta/edit',
                    array('id'=>$pregunta->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
