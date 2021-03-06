<?php
class Magentothem_Newproductslider_Block_Newproductslider extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getNewproductslider()     
    { 
        if (!$this->hasData('newproductslider')) {
            $this->setData('newproductslider', Mage::registry('newproductslider'));
        }
        return $this->getData('newproductslider');
    }
	public function getProducts()
    {
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
		$storeId    = Mage::app()->getStore()->getId();
		$cateids = $this->getConfig('catsid');
		if($cateids){
			$arr_catid = explode(",", $cateids);
			$products = Mage::getResourceModel('catalog/product_collection')
                                ->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.is_in_stock=1', 'left')
				//->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
				//->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', 'is_in_stock=1', '{{table}}.stock_id=1', 'left')
                                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
				->addMinimalPrice()
				->addUrlRewrite()
				->addTaxPercents()
				->addStoreFilter()
                                ->addAttributeToFilter('qty', array("gt" => 0))
				//->addAttributeToFilter('category_id', array('in' => $arr_catid))
				/*->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
				->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
				->addAttributeToSort('news_from_date','desc')
                                ->addAttributeToFilter('is_in_stock', array('eq' => 1))*/;	
                        
                         //Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
		} else {
			$root_cat = Mage::app()->getStore()->getRootCategoryId();
			$products = Mage::getResourceModel('catalog/product_collection')
                                ->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.is_in_stock=1', 'left')
				//->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
				//->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', 'is_in_stock=1', '{{table}}.stock_id=1', 'left')                           
                                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
				->addMinimalPrice()
				->addUrlRewrite()
				->addTaxPercents()
				->addStoreFilter()
                                ->addAttributeToFilter('qty', array("gt" => 0))
				//->addAttributeToFilter('category_id', array('in' => $root_cat))
				/*->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
				->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
				->addAttributeToSort('news_from_date','desc')
                                ->addAttributeToFilter('is_in_stock', array('eq' => 1))*/;
                       // Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                        
		}
		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
        $this->setProductCollection($products);
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('newproductslider');
		if (isset($config['newproductslider_config']) ) {
			$value = $config['newproductslider_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
}