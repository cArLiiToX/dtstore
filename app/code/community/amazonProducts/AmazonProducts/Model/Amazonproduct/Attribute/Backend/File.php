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
 * Admin backend source model for files
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Model_Amazonproduct_Attribute_Backend_File extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{

    /**
     * Save uploaded file and set its name to amazon product object
     *
     * @access public
     * @param Varien_Object $object
     * @return null
     * @author Ultimate Module Creator
     */
    public function afterSave($object)
    {
        $value = $object->getData($this->getAttribute()->getName());
        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()
                ->saveAttribute($object, $this->getAttribute()->getName());
            return;
        }

        $path = Mage::helper('amazonproducts_amazonproducts/amazonproduct')->getFileBaseDir();

        try {
            $uploader = new Varien_File_Uploader($this->getAttribute()->getName());
            //set allowed file extensions if you need
            //$uploader->setAllowedExtensions(array('mp4', 'mov', 'f4v', 'flv'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save($path);
            $object->setData($this->getAttribute()->getName(), $result['file']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (Exception $e) {
            if ($e->getCode() != 666) {
                //throw $e;
            }
            return;
        }
    }
}
