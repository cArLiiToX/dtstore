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

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media').DS.'amazonproduct'.DS.'file';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'amazonproduct'.'/'.'file';
    }

    /**
     * get amazonproduct attribute source model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Ultimate Module Creator
     */
     public function getAttributeSourceModelByInputType($inputType)
     {
         $inputTypes = $this->getAttributeInputTypes();
         if (!empty($inputTypes[$inputType]['source_model'])) {
             return $inputTypes[$inputType]['source_model'];
         }
         return null;
     }

    /**
     * get attribute input types
     *
     * @access public
     * @param string $inputType
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array'
            ),
            'boolean'     => array(
                'source_model'  => 'eav/entity_attribute_source_boolean'
            ),
            'file'          => array(
                'backend_model' => 'amazonproducts_amazonproducts/amazonproduct_attribute_backend_file'
            ),
            'image'          => array(
                'backend_model' => 'amazonproducts_amazonproducts/amazonproduct_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    /**
     * get amazonproduct attribute backend model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Ultimate Module Creator
     */
    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    /**
     * filter attribute content
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author Ultimate Module Creator
     */
    public function amazonproductAttribute($amazonproduct, $attributeHtml, $attributeName)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
            amazonProducts_AmazonProducts_Model_Amazonproduct::ENTITY,
            $attributeName
        );
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }

    /**
     * get the template processor
     *
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author Ultimate Module Creator
     */
    protected function _getTemplateProcessor()
    {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }
}
