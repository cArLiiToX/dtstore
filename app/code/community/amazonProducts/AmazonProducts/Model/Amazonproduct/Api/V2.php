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
class amazonProducts_AmazonProducts_Model_Amazonproduct_Api_V2 extends amazonProducts_AmazonProducts_Model_Amazonproduct_Api
{
    /**
     * Amazon Product info
     *
     * @access public
     * @param int $amazonproductId
     * @return object
     * @author Ultimate Module Creator
     */
    public function info($amazonproductId)
    {
        $result = parent::info($amazonproductId);
        $result = Mage::helper('api')->wsiArrayPacker($result);
        foreach ($result->products as $key => $value) {
            $result->products[$key] = array('key' => $key, 'value' => $value);
        }
        return $result;
    }
}
