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
 * Preguntas Product list block
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author Ultimate Module Creator
 */
class Preguntas_Products_Block_Pregunta_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $preguntas = Mage::getResourceModel('preguntas_products/pregunta_collection')
                         ->addFieldToFilter('status', 1);
        $preguntas->setOrder('name', 'asc');
        $this->setPreguntas($preguntas);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Preguntas_Products_Block_Pregunta_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'preguntas_products.pregunta.html.pager'
        )
        ->setCollection($this->getPreguntas());
        $this->setChild('pager', $pager);
        $this->getPreguntas()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
