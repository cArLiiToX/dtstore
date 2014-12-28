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
/**
 * Amazon Product attribute API V2 model
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Model_Amazon_Attribute_Api_V2 extends Amazon_Products_Model_Amazon_Attribute_Api
{
    /**
     * Create new amazon product attribute
     *
     * @access public
     * @param array $data input data
     * @return integer
     * @author Ultimate Module Creator
     */
    public function create($data)
    {
        $helper = Mage::helper('api');
        $helper->v2AssociativeArrayUnpacker($data);
        Mage::helper('api')->toArray($data);
        return parent::create($data);
    }

    /**
     * Update product attribute
     *
     * @access public
     * @param string|integer $attribute attribute code or ID
     * @param array $data
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function update($attribute, $data)
    {
        $helper = Mage::helper('api');
        $helper->v2AssociativeArrayUnpacker($data);
        Mage::helper('api')->toArray($data);
        return parent::update($attribute, $data);
    }

    /**
     * Add option to select or multiselect attribute
     *
     * @access public
     * @param  integer|string $attribute attribute ID or code
     * @param  array $data
     * @return bool
     * @author Ultimate Module Creator
     */
    public function addOption($attribute, $data)
    {
        Mage::helper('api')->toArray($data);
        return parent::addOption($attribute, $data);
    }

    /**
     * Get full information about attribute with list of options
     *
     * @access public
     * @param integer|string $attribute attribute ID or code
     * @return array
     * @author Ultimate Module Creator
     */
    public function info($attribute)
    {
        $result = parent::info($attribute);
        if (!empty($result['additional_fields'])) {
            $keys = array_keys($result['additional_fields']);
            foreach ($keys as $key) {
                $result['additional_fields'][] = array(
                    'key'   => $key,
                    'value' => $result['additional_fields'][$key]
                );
                unset($result['additional_fields'][$key]);
            }
        }
        return $result;
    }
}
