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
 * Banner DT Store model
 *
 * @category    Banners
 * @package     Banners_Nikolai
 * @author      Ultimate Module Creator
 */
class Banners_Nikolai_Model_Bannerdt extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'banners_nikolai_bannerdt';
    const CACHE_TAG = 'banners_nikolai_bannerdt';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'banners_nikolai_bannerdt';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'bannerdt';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('banners_nikolai/bannerdt');
    }

    /**
     * before save banner dt store
     *
     * @access protected
     * @return Banners_Nikolai_Model_Bannerdt
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save banner dt store relation
     *
     * @access public
     * @return Banners_Nikolai_Model_Bannerdt
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['onclick'] = 'javascript:void(0);';

        return $values;
    }
    
}
