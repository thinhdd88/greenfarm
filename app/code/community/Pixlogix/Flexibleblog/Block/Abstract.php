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
 * Abstract block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

abstract class Pixlogix_Flexibleblog_Block_Abstract extends Mage_Core_Block_Template
{
    const XML_PATH_POST_CHARACTER_LIMIT = 'flexibleblog_options/post_list/character_limit';

    /**
     * set post url
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getPostUrl($key)
    {
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        return Mage::getUrl($url).$key.'.html';
    }
    public function getRequestPostId()
    {
        return Mage::app()->getRequest()->getParam('id');
    }
    public function getPostUrlById($Id)
    {
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        $collection = Mage::getModel('flexibleblog/post')
                    ->getCollection()
                    ->addFieldToFilter('post_status', array('eq' => 1 ))
                    ->addFieldToFilter('post_id', array('eq' => $Id ))
                    ->getFirstItem();
        $key = $collection->getPostUrlKey();
        return Mage::getUrl($url).$key.'.html';
    }
    
    public function getPostUrlKeyById($Id)
    {
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        $collection = Mage::getModel('flexibleblog/post')
                    ->getCollection()
                    ->addFieldToFilter('post_status', array('eq' => 1 ))
                    ->addFieldToFilter('post_id', array('eq' => $Id ))
                    ->getFirstItem();
        $key = $collection->getPostUrlKey();
        return $key;
    }

    /**
     * set category url
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getCategoryUrl($key)
    {
       return Mage::getModel('flexibleblog/category')->getCategoryUrl($key);
    }

    /**
     * set tag url
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getTagUrl($key)
    {
        return Mage::getModel('flexibleblog/tag')->getTagUrl($key);
    }

    /**
     * set author url
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getAuthorUrl($authorId)
    {
        if($authorId)
        {
            $collection     = Mage::getModel('flexibleblog/author')->load($authorId, 'author_id');
            $authorUrlKey   = $collection->getAuthorUrlKey();
            $url            = Mage::helper('flexibleblog')->customBlogUrl();
            return Mage::getUrl($url.'/author/'.$authorUrlKey);
        }
        else
        {
            return '';
        }
    }

    /**
     * set author name
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getAuthorName($authorId)
    {
        if($authorId)
        {
            $collection = Mage::getModel('flexibleblog/author')->load($authorId, 'author_id');
            $authorName = $collection->getAuthorName();
            return $authorName;
        }
        else
        {
            return '';
        }
    }

    /**
     * set post categories list
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getPostCategoryList($categories)
    {
        $categoryList = Mage::getModel('flexibleblog/category')->getCategoryPost($categories);
	return $categoryList;
    }

    /**
     * set post tag list
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getPostTagList($tags)
    {
        $tagIds         = explode(',', $tags);
        $collection     = Mage::getModel('flexibleblog/tag')->getCollection();
        $collection->addFieldToFilter( 'tag_id', array( 'in' => $tagIds ) );
        $countcollection= $collection->count();
        $_tagList       = '';
        $i              = 1;
        foreach($collection as $tag)
        {
            $_tagList .= '<li><a href="'.$this->getTagUrl($tag->getTagUrlKey()).'">'.$tag->getTagName().'</a>';
            $i++;
            $_tagList .= '</li>';
        }
        $_tagList = rtrim($_tagList, ',');
        return $_tagList;
    }

    /**
     * set post image
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getPostImage($postImage)
    {
        if($postImage){
            return Mage::getModel('flexibleblog/image')->getPostImagePath($postImage);
        }
    }
    
    /**
     * set post thumb
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getPostThumb($postId,$arrOption='')
    {
        return Mage::getModel('flexibleblog/image')->getPostThumb($postId,$arrOption='');
    }
    
    /**
     * set post thumb
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getLatestPostThumb($postId,$arrOption='')
    {
        return Mage::getModel('flexibleblog/image')->getLatestPostThumb($postId,$arrOption='');
    }

    /**
     * set post excerpt
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getPostExcerpt($postId)
    {
        $post_char_limit = Mage::getStoreConfig(self::XML_PATH_POST_CHARACTER_LIMIT);
        $default_limit   = 250;

        $collection = Mage::getModel('flexibleblog/post')
                ->getCollection()
                ->addFieldToFilter('post_status', array('eq' => 1 ))
                ->addFieldToFilter('post_id', array('eq' => $postId ))
                ->getFirstItem();

        $short_desc     = $collection->getPostShortDescription();
        $description    = $collection->getPostDescription();
        if($short_desc){
            $excerpt    = Mage::helper('cms')->getBlockTemplateProcessor()->filter($short_desc);
        }else{
            $limit      = $post_char_limit ? $post_char_limit : $default_limit;
            $excerpt    = substr( strip_tags( preg_replace('#<script(.*?)>(.*?)</script>#is', '', Mage::helper('cms')->getBlockTemplateProcessor()->filter($description)) ), 0, $limit );
        }
        return strip_tags($excerpt).' [...]';
    }

    /**
     * set comment comment
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getCommentCount($postId)
    {
        $commentcount = Mage::getModel('flexibleblog/comment')->getCollection()
                            ->addFieldToFilter('post_id', $postId)
                            ->addFieldToFilter('comment_status', '1');
        if($commentcount->count()){
            return $commentcount->count();
        } else {
            return 0;
        }
    }

    /**
     * set comment comment
     *
     * @return Pixlogix_Flexibleblog_Block_Abstract
     */
    public function getDisqusScript()
    {
        if( Mage::helper('flexibleblog')->enableComment() == 2 ){
            if( $disqusId = Mage::helper('flexibleblog')->getDisqusUsername() ){
                return '<script id="dsq-count-scr" src="//'.$disqusId.'.disqus.com/count.js" async></script>';
            }
        }
    }
    
    public function getComments($postId)
    {
        if( Mage::helper('flexibleblog')->enableComment() == 1  )
        {
            if( $this->getCommentCount($postId) > 1 )
            {
                return $this->__('%s Comments', $this->getCommentCount($postId));
            }
            else
            {
                return $this->__('%s Comment', $this->getCommentCount($postId));
            }
        }
        else if(Mage::helper('flexibleblog')->enableComment() == 2  )
        {
            return '<span class="disqus-comment-count" data-disqus-identifier="'.$this->getPostUrlKeyById($postId).'">0 Comment</span>';
        }
    }
}