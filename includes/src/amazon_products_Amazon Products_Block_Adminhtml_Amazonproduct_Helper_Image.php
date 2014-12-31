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
 * Amazon Product image field renderer helper
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Block_Adminhtml_Amazonproduct_Helper_Image extends Varien_Data_Form_Element_Image
{
    /**
     * get the url of the image
     *
     * @access protected
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('amazon_products_amazon products/amazonproduct_image')->getImageBaseUrl().
                $this->getValue();
        }
        return $url;
    }
}
