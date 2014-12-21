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
 * Amazon Product helper
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Helper_Amazonproduct extends Mage_Core_Helper_Abstract
{
    const AMAZONPRODUCT_ROOT_ID = 1;
    /**
     * get the root id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getRootAmazonproductId()
    {
        return self::AMAZONPRODUCT_ROOT_ID;
    }
}
