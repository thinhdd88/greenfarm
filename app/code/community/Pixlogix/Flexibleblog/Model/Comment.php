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
 * Flexibleblog Comment model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Model_Comment extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleblog/comment');
    }

    /**
     * To filter Post Name by post Id on Comments Grid
     *
     * @return Pixlogix_Flexibleblog_Model_Comment
     */
    public function filterPostNameColumn($value)
    {
        $collection = Mage::getModel('flexibleblog/post')->getCollection();
        $collection = $collection->addFieldToFilter('post_title', array('like' => '%'.$value.'%'));
        foreach($collection as $p)
        {
            $postIds[] = $p['post_id'];
        }
        if(count($postIds)):
            foreach($postIds as $pIds):
                $postCollection = Mage::getModel('flexibleblog/comment')->getCollection()->addFieldToFilter('post_id',$pIds);
                foreach($postCollection as $post):
                    $postIds[] = $post->getPostId();
                endforeach;
            endforeach;
        endif;
        return $postIds;
    }
    
    /**
     * set Comment List (root level comment)
     *
     * @return Pixlogix_Flexibleblog_Model_Comment
     */
    public function getCommentList($postId)
    {
        $commentModel = Mage::getModel('flexibleblog/comment')->getCollection()
                        ->addFieldToFilter('post_id', $postId)
                        ->addFieldToFilter('comment_status', '1')
                        ->addFieldToFilter('comment_parent_id', '0')
                        ->setOrder('comment_created_time', 'ASC');
        if($commentModel->count()){
            return $commentModel;
        }else{
            return 0;
        }
    }

    
    /**
     * set Comment Child (Recursive function to view child comment of any root level comment)
     *
     * @return Pixlogix_Flexibleblog_Model_Comment
     */
    public function getCommentChild($commentId, $level)
    {
        $commentModel = Mage::getModel('flexibleblog/comment')->getCollection()
                        ->addFieldToFilter('comment_status', '1')
                        ->addFieldToFilter('comment_parent_id', $commentId)
                        ->setOrder('comment_created_time', 'ASC');
        if($commentModel->count()){
            $level++;
            $maxcommentLevel    = 5;
            $levelClass         = ($level <= $maxcommentLevel) ? 'level-'.$level: 'level-5';
            if($level <= $maxcommentLevel){ //To prevent appending ul when comments rich to max level 
                $commentSection  = '';
                $commentSection .='<ul class="'.$levelClass.'">';
            }

            foreach($commentModel as $comment){
                $commentSection .= '<li class="comment-'.$comment->getCommentId().'">
                    <div class="user-comment" id="comment-<?php echo $comment->getCommentId();?>">
                        <div class="post-by">
                            <span>'.Mage::helper('core')->__('%s says :',$comment->getCommentName()).'</span>
                            <div class="posted-on">'.Mage::helper('core')->__('%s at %s',Mage::getModel('core/date')->date('M d, Y', strtotime($comment->getCommentCreatedTime())),Mage::getModel('core/date')->date('h:i A', strtotime($comment->getCommentCreatedTime()))).'</div>
                        </div>
                        <div class="comment-description">'.$comment->getCommentDescription().'</div>
                        <div class="reply-'.$comment->getCommentId().'">
                            <a href="#" class="reply-comment" data-comment-id="'.$comment->getCommentId().'">'.Mage::helper('core')->__('Reply').'</a>
                            <a href="#" class="cancel-reply-comment" style="display:none;">'.Mage::helper('core')->__('Cancel Reply').'</a>
                        </div>
                    </div>'.
                    Mage::getModel('flexibleblog/comment')->getCommentChild($comment->getCommentId(), $level)
                .'</li>';
            }
            if($level <= $maxcommentLevel){//To prevent appending ul when comments rich to max level 
                $commentSection .= '</ul>';
            }
        }
        return $commentSection;
    }

    /**
     * set Comment Post Title
     *
     * @return Pixlogix_Flexibleblog_Model_Comment
     */
    public function getCommentPostTitle($postId)
    {
        $postModel = Mage::getModel('flexibleblog/post')
                    ->getCollection()
                    ->addFieldToFilter('post_status', '1')
                    ->addFieldToFilter('post_id', $postId)
                    ->getFirstItem();
        return '<a href="'.Mage::getUrl('blog').$postModel->getPostUrlKey().'.html'.'">'.$postModel->getPostTitle().'</a>';
    }
}