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
 * Post Comment block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Post_Comment extends Pixlogix_Flexibleblog_Block_Abstract
{
    
    public function _toHtml()
    {
        //To change comment template 
        if( Mage::helper('flexibleblog')->enableComment() == 2 ){
            $this->setTemplate('flexibleblog/post/detail/disqus-comment.phtml');
        }
        if( Mage::helper('flexibleblog')->enableComment() != 0 ){
            return parent::_toHtml();
        }
    }
    /**
     * set comment Action to submit frontend data
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Comment
     */
    public function getCommentAction()
    {
        $formAction = $this->getUrl( 'flexibleblog/index/submit', array('_secure'=>true) );
        return $formAction;
    }

    /**
     * set comment list
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Comment
     */
    public function getCommentList($postId){
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
                if( Mage::helper('flexibleblog')->enableComment() == 1 ){
                    return  Mage::getModel('flexibleblog/comment')->getCommentList($postId);
                }
            }
        }
    }

    /**
     * To retrive comment form on post detail page
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Comment
     */
    public function getCommentForm( $postId )
    {
        $postId = $postId ? $postId : $this->getRequest()->getParam('id');
        return $this->getLayout()->createBlock('flexibleblog/post_comment_form')->setData('post_id', $postId)->setTemplate('flexibleblog/post/detail/comment-form.phtml')->toHtml();
    }
    
    public function getDisqusUsername()
    {
        return Mage::helper('flexibleblog')->getDisqusUsername();
    }
}