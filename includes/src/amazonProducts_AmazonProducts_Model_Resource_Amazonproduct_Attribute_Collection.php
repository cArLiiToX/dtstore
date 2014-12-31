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
 * Amazon Product attribute collection model
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Attribute_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Collection
{
    /**
     * init attribute select
     *
     * @access protected
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Attribute_Collection
     * @author Ultimate Module Creator
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()))
            ->where(
                'main_table.entity_type_id=?',
                Mage::getModel('eav/entity')->setType('amazonproducts_amazonproducts_amazonproduct')->getTypeId()
            )
            ->join(
                array('additional_table' => $this->getTable('amazonproducts_amazonproducts/eav_attribute')),
                'additional_table.attribute_id=main_table.attribute_id'
            );
        return $this;
    }

    /**
     * set entity type filter
     *
     * @access public
     * @param string $typeId
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }

    /**
     * Specify filter by "is_visible" field
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function addVisibleFilter()
    {
        return $this->addFieldToFilter('additional_table.is_visible', 1);
    }

    /**
     * Specify filter by "is_editable" field
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function addEditableFilter()
    {
        return $this->addFieldToFilter('additional_table.is_editable', 1);
    }
}
