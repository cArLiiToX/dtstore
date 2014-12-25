<?php
/**
 * Banners_Nikolai extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Banners
 * @package        Banners_Nikolai
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Admin search model
 *
 * @category    Banners
 * @package     Banners_Nikolai
 * @author      Ultimate Module Creator
 */
class Banners_Nikolai_Model_Adminhtml_Search_Bannerdt extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Banners_Nikolai_Model_Adminhtml_Search_Bannerdt
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('banners_nikolai/bannerdt_collection')
            ->addFieldToFilter('nombre', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $bannerdt) {
            $arr[] = array(
                'id'          => 'bannerdt/1/'.$bannerdt->getId(),
                'type'        => Mage::helper('banners_nikolai')->__('Banner DT Store'),
                'name'        => $bannerdt->getNombre(),
                'description' => $bannerdt->getNombre(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/nikolai_bannerdt/edit',
                    array('id'=>$bannerdt->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
