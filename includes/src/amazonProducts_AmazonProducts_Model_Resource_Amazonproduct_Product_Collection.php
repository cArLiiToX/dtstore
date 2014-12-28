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
 * Amazon Product - product relation resource model collection
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Product_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('amazonproducts_amazonproducts/amazonproduct_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add amazon product filter
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct | int $amazonproduct
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Product_Collection
     * @author Ultimate Module Creator
     */
    public function addAmazonproductFilter($amazonproduct)
    {
        if ($amazonproduct instanceof amazonProducts_AmazonProducts_Model_Amazonproduct) {
            $amazonproduct = $amazonproduct->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.amazonproduct_id = ?', $amazonproduct);
        return $this;
    }
}
