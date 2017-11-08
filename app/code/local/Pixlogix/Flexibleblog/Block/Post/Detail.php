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
 * Post Detail block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Post_Detail extends Pixlogix_Flexibleblog_Block_Abstract
{
    /**
     * _prepareLayout for Collection
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Detail
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    /**
     * set post data
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Detail
     */
    public function getPostData($id='')
    {
        $id         = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('flexibleblog/post')
            ->getCollection()
            ->addFieldToFilter('post_status', array('eq' => 1 ))
            ->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) )
            ->addFieldToFilter('post_id', array('eq' => $id ));
        /*if(!Mage::app()->isSingleStoreMode()){
            $storeId    = Mage::app()->getStore()->getStoreId();
            $collection = $collection->addFieldToFilter( 'post_store_view', array('finset'=>0),array('finset' => $storeId) );
        }*/
        $collection = $collection->getFirstItem();
        return $collection;
    }

    /**
     * To retrive socail sharing blog on post detail page
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Detail
     */
    public function socialSharing($postId)
    {
        if( Mage::helper('flexibleblog')->enableSocialShare() )
        {
            $postId = $postId ? $postId : $this->getRequest()->getParam('id');
            return $this->getLayout()->createBlock('flexibleblog/post_socialshare')->setData('social_post_id', $postId)->setTemplate('flexibleblog/post/detail/socialshare.phtml')->toHtml();
        }
    }

    /**
     * set author
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Detail
     */
    public function getAuthor($authorId)
    {
        if( Mage::helper('flexibleblog')->enableAuthorInfo() )
        {
            echo $this->getLayout()->createBlock('flexibleblog/post_author')->setData('author_id', $authorId)->setTemplate('flexibleblog/post/detail/authorbio.phtml')->toHtml();
        }
    }

    /**
     * set comments
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Detail
     */
    /*public function getComments($postId)
    {
        if( Mage::helper('flexibleblog')->enableComment())
        {
            $postId = $postId ? $postId : $this->getRequest()->getParam('id');
            $collection = Mage::getModel('flexibleblog/post')
                            ->getCollection()
                            ->addFieldToFilter('post_status', array('eq' => 1 ))
                            ->addFieldToFilter('post_id', array('eq' => $postId ))
                            ->getFirstItem();
            if($collection->getPostAllowComments() == 1)
            {
                if( Mage::helper('flexibleblog')->enableComment() == 2 ){
                    if( Mage::helper('flexibleblog')->getDisqusUsername() ){
                        echo $this->getLayout()->createBlock('flexibleblog/post_comment')->setData('post_url_key', $collection->getPostUrlKey())->setTemplate('flexibleblog/post/detail/disqus-comment.phtml')->toHtml();
                    }
                    else{
                        echo '<div class="flexible-comment-error"><p>'.$this->__('Something is wrong !').'</p></div>';
                    }

                }
                else{
                    //echo $this->getLayout()->createBlock('flexibleblog/post_comment')->setData('post_id', $postId)->setTemplate('flexibleblog/post/detail/comment.phtml')->toHtml();
                }

            }
        }
    }*/

    /**
     * set image
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Detail
     */
    public function getImage($postId,$arr=array())
    {
        return Mage::getModel('flexibleblog/image')->getImage($postId);
    }

    /**
     * Get Previous Post
     *
     * @param $postId
     * @return mixed
     */
    public function getPreviousPost($postId)
    {
        $collection = Mage::getModel('flexibleblog/post')
            ->getCollection()
            ->addFieldToFilter('post_status', array('eq' => 1 ))
            ->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) )
            ->addFieldToFilter('post_id', array('lteq' => $postId ));
        $collection->getSelect()->order('post_id', 'DESC');
        $collection->getSelect()->limit(1);
        $collection = $collection->getFirstItem();
        return $collection;
    }

    /**
     * Get Next Post
     *
     * @param $postId
     * @return mixed
     */
    public function getNextPost($postId)
    {
        $collection = Mage::getModel('flexibleblog/post')
            ->getCollection()
            ->addFieldToFilter('post_status', array('eq' => 1 ))
            ->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) )
            ->addFieldToFilter('post_id', array('gteq' => $postId ));
        $collection->getSelect()->order('post_id', 'ASC');
        $collection->getSelect()->limit(1);
        $collection = $collection->getFirstItem();
        return $collection;
    }
}