<?php
/**
 * Pixlogix Flexibleblog
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @copyright  Copyright (c) 2016 Pixlogix Flexibleblog (http://www.pixlogix.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sidebar Categories block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Sidebar_Categories extends Mage_Core_Block_Template
{
    /**
     * set categories
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Categories
     */
    public function getCategories()
    {
		
        $collection = Mage::getModel('flexibleblog/category')->getCollection();
        $collection->addFieldToFilter('category_status', array( 'eq' => 1 ));
	$count = (Mage::helper('flexibleblog')->getCategoriesWidgetEntries() && Mage::helper('flexibleblog')->enableCategoriesWidget()) ? Mage::helper('flexibleblog')->getCategoriesWidgetEntries():0;
       	$collection->getSelect()
                ->reset('order')
                ->limit($count)
                ->order('category_created_time DESC')
                ->order('category_title ASC');
        return $collection;
    }

    /**
     * set category url
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Categories
     */
    public function getCategoryUrl($key)
    {
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        return Mage::getUrl($url.'/category/'.$key);
    }

    /**
     * set post count
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Categories
     */
    public function getPostCount($catId)
    {
        $collection = Mage::getModel('flexibleblog/post')->getCollection();
        $collection->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) ); //Check publish date
        $collection->addFieldToFilter('post_categories', array( 'finset' => $catId ));
        if (!Mage::app()->isSingleStoreMode()){
            $storeId    = Mage::app()->getStore()->getStoreId();
            $collection = $collection->addFieldToFilter( 'post_store_view',array('finset' => array( $storeId ) ) );
        }
        $collection->addFieldToFilter('post_status', array( 'eq' => 1 ));
        $collection_count = $collection->count();
        if($count = $collection_count){
            return $count;
        } else {
            return '';
        }
    }
}