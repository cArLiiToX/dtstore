<?php
/**
 * Amazon_Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Amazon
 * @package        Amazon_Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
class Amazon_Products_Model_Amazon_Api_V2 extends Amazon_Products_Model_Amazon_Api
{
    /**
     * Amazon Product info
     *
     * @access public
     * @param int $amazonId
     * @return object
     * @author Ultimate Module Creator
     */
    public function info($amazonId)
    {
        $result = parent::info($amazonId);
        $result = Mage::helper('api')->wsiArrayPacker($result);
        foreach ($result->products as $key => $value) {
            $result->products[$key] = array('key' => $key, 'value' => $value);
        }
        return $result;
    }
}
