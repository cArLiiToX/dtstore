<?php
/**
 * amazonProducts_AmazonProducts extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       amazonProducts
 * @package        amazonProducts_AmazonProducts
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Amazon Product admin attribute block
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Block_Adminhtml_Amazonproduct_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_amazonproduct_attribute';
        $this->_blockGroup = 'amazonproducts_amazonproducts';
        $this->_headerText = Mage::helper('amazonproducts_amazonproducts')->__('Manage Amazon Product Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('amazonproducts_amazonproducts')->__('Add New Amazon Product Attribute')
        );
    }
}
