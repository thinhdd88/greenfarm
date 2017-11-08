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
 * Sidebar Recentcomments block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Sidebar_Recentcomments extends Mage_Core_Block_Template
{
    /**
     * set comments
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Recentcomments
     */
    public function getComments()
    {
        $storeId[] = Mage::app()->getStore()->getStoreId();
        $postModel = Mage::getModel('flexibleblog/post')->getCollection();
        $postModel->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) );
        $postModel->addFieldToFilter('post_status', array( 'eq' => 1 ));
        if (!Mage::app()->isSingleStoreMode()) {
            $postModel = $postModel->addFieldToFilter('post_store_view', array('finset' => $storeId ));
        }

        $arrPost = array();
        foreach($postModel as $post)
        {
            $arrPost[] = $post->getPostId();
        }
        $collection = '';
        if(count($arrPost)>0)
        {
            $collection = Mage::getModel('flexibleblog/comment')->getCollection();
            $collection->addFieldToFilter('comment_status', array( 'eq' => 1 ));
            $collection->addFieldToFilter('post_id', array('in', $arrPost ) );
            $count = (Mage::helper('flexibleblog')->getRecentCommentsWidgetEntries() && Mage::helper('flexibleblog')->enableRecentCommentsWidget()) ? Mage::helper('flexibleblog')->getRecentCommentsWidgetEntries():5;
            $collection->getSelect()
                        ->limit($count)
                        ->reset('order')
                        ->order('comment_created_time DESC');
        }
        return $collection;
    }

    /**
     * set post
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Recentcomments
     */
    public function getPost($post_id)
    {
        $storeId[]  = Mage::app()->getStore()->getStoreId();
        $collection = Mage::getModel('flexibleblog/post')->getCollection();
        $collection->addFieldToFilter('post_status', array( 'eq' => 1 ));
        $collection->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) ); 
        $collection->addFieldToFilter('post_id', array('eq' => $post_id));
        if (!Mage::app()->isSingleStoreMode()) {
            $collection = $collection->addFieldToFilter( 'post_store_view', array( 'finset' => $storeId ) );
        }
        $collection = $collection->getFirstItem();
        $url        = Mage::helper('flexibleblog')->customBlogUrl();
        $posturl    = Mage::getUrl($url). $collection->getPostUrlKey().'.html';
        $this->setPostUrl($posturl);
        $this->setPostTitle($collection->getPostTitle());
        return $this;
    }
}