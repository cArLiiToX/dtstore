<?php
/**
 * amazon_products_Amazon Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       amazon_products
 * @package        amazon_products_Amazon Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
 /*
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
Mage::getModel('amazon_products_amazon products/amazonproduct')
    ->load(1)
    ->setParentId(0)
    ->setPath(1)
    ->setLevel(0)
    ->setPosition(0)
    ->setChildrenCount(0)
    ->setName('ROOT')
    ->setInitialSetupFlag(true)
    ->setAttributeSetId(Mage::getModel('amazon_products_amazon products/amazonproduct')->getDefaultAttributeSetId())
    ->save();
