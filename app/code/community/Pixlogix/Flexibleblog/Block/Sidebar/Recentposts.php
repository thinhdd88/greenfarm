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
 * Sidebar Recentposts block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Sidebar_Recentposts extends Mage_Core_Block_Template
{
    /**
     * set posts
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Recentposts
     */
    public function getPosts()
    {
        $storeId[]  = Mage::app()->getStore()->getStoreId();
        $collection = Mage::getModel('flexibleblog/post')->getCollection();
        $collection->addFieldToFilter('post_status', array( 'eq' => 1 ));
        $collection->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) );
        if (!Mage::app()->isSingleStoreMode()) {
            $collection = $collection->addFieldToFilter( 'post_store_view', array('finset' => $storeId ) );
        }
	$count      = (Mage::helper('flexibleblog')->getRecentPostWidgetEntries() && Mage::helper('flexibleblog')->enableRecentPostWidget()) ? Mage::helper('flexibleblog')->getRecentPostWidgetEntries() : 5 ;
        $collection->getSelect()->limit($count)
                ->reset('order')
                ->order('post_publish_date DESC');
        return $collection;
    }

    /**
     * set post url
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Recentposts
     */
    public function getPostUrl($key)
    {
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        return Mage::getUrl($url).$key.'.html';
    }
}