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
 * Sidebar Tagcloud block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Sidebar_Tagcloud extends Mage_Core_Block_Template
{
    /**
     * set tags
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Tagcloud
     */
    public function getTags()
    {
        $collection = Mage::getModel('flexibleblog/tag')->getCollection();
        $collection->getSelect()
                ->reset('order')
                ->order('tag_name ASC');
        return $collection;
    }

    /**
     * set tag url
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Tagcloud
     */
    public function getTagUrl($key)
    {
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        return Mage::getUrl($url.'/tag/'.$key);
    }

    /**
     * set tag count
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Tagcloud
     */
    public function getTagCount($tagId)
    {
        $collection = Mage::getModel('flexibleblog/post')->getCollection();
        $collection->addFieldToFilter('post_tags', array( 'finset' => $tagId ));
        $collection->addFieldToFilter('post_status', array( 'eq' => 1 ));
        $count = $collection->count();
        if($count <= 5){
            return 10;
        } else if($count >= 6 && $count <= 10){
            return 12;
        } else if($count >= 11 && $count <= 15){
            return 14;
        } else if($count >= 16 && $count <= 20){
            return 16;
        } else if($count >= 21 && $count <= 25){
            return 18;
        } else if($count >= 26 && $count <= 30){
            return 20;
        } else if($count >= 31){
            return 22;
        } else {
            return '';
        }
    }
}