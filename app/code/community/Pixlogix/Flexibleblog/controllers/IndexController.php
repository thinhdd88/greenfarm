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
 * Blog Index Controller
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_IndexController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_BLOG_TITLE           = 'flexibleblog_options/general/blog_title';
    const XML_PATH_BLOG_META_KEYWORDS   = 'flexibleblog_options/general/meta_keywords';
    const XML_PATH_BLOG_META_DESCRIPTION= 'flexibleblog_options/general/meta_description';

    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::helper('flexibleblog')->enableFlexibleblog()) {
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
        }
        Mage::helper('flexibleblog')->ifStoreChangedRedirect();
    }

    /**
     * index Action
     *
     * @return post list page
     */
    public function indexAction()
    {
        $isEnable = Mage::helper('flexibleblog')->enableFlexibleblog();
        if( !$isEnable )
        {
            $this->_forward('NoRoute');
            return;
        }
        $title              = Mage::getStoreConfig(self::XML_PATH_BLOG_TITLE);
        $meta_keywords      = Mage::getStoreConfig(self::XML_PATH_BLOG_META_KEYWORDS);
        $meta_description   = Mage::getStoreConfig(self::XML_PATH_BLOG_META_DESCRIPTION);
        $this->loadLayout();
        $meta_title         = $title ? $title : $this->__('Blog');

        $head               = $this->getLayout()->getBlock('head');
        if ($head){
            $head->setTitle($meta_title);
            $head->setDescription($meta_description);
            $head->setKeywords($meta_keywords);
        }

        $url                = Mage::helper('flexibleblog')->customBlogUrl();
        
        if(Mage::helper('flexibleblog')->enableBreadcrumbs())
        {
            $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
            $breadcrumbs->addCrumb('home',
                array(
                    'label' => Mage::helper('flexibleblog')->__('Home'),
                    'title' => Mage::helper('flexibleblog')->__('Home'),
                    'link'  => Mage::getBaseUrl()
                )
            );

            $breadcrumbs->addCrumb($url,
                array(
                    'label' => Mage::helper('flexibleblog')->__($meta_title),
                    'title' => Mage::helper('flexibleblog')->__($meta_title)
                )
            );
        }
        $this->renderLayout();
    }

    /**
     * view Action
     *
     * @return Blog detail page
     */
    public function viewAction()
    {
        $id         = $this->getRequest()->getParam('id');
        $url        = $this->getRequest()->getParam('url');
	
        $collection = Mage::getModel('flexibleblog/post')->getCollection();
        if(!Mage::app()->isSingleStoreMode())
        {
            $storeId    = Mage::app()->getStore()->getStoreId();
            $collection = $collection->addFieldToFilter( 'post_store_view',array('finset' => array( $storeId ) ) );
        }
        $collection->addFieldToFilter('post_status', array('eq' => 1 ));

        if(!empty($url))
        {
            $collection = $collection->addFieldToFilter('post_url_key', array('eq' => $url ))->getFirstItem();
            $id = $this->getRequest()->setParam('id',$collection->getPostId());
        }
        else
        {
            $collection = $collection->addFieldToFilter('post_id', array('eq' => $id ))->getFirstItem();
        }

        $_isBlogEnabled = Mage::helper('flexibleblog')->enableFlexibleblog();
        if( !$collection->getData() || !$_isBlogEnabled )
        {
            $this->_forward('NoRoute');
            return;
        }

        $postId     = $collection->getPostId();
        $postUrlKey = $collection->getPostUrlKey();

        $this->loadLayout();

        $blog_title = Mage::getStoreConfig(self::XML_PATH_BLOG_TITLE);
        $meta_title = $blog_title ? $blog_title : $this->__('Blog');
        $postTitle  = $collection->getPostMetaTitle() ? $collection->getPostMetaTitle().' - '.$meta_title : $collection->getPostTitle().' - '.$meta_title;
        $head       = $this->getLayout()->getBlock('head');

        $head->setTitle($this->__($postTitle));
        if($collection->getPostMetaKeyword())
        {
            $head->setKeywords($this->__($collection->getPostMetaKeyword()));
        }
        if($collection->getPostMetaDescription())
        {
            $head->setDescription($this->__($collection->getPostMetaDescription()));
        }
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        //Check breadcrum enable or not
        if(Mage::helper('flexibleblog')->enableBreadcrumbs())
        {
                $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
                $breadcrumbs->addCrumb('home',
                        array(
                                'label' => Mage::helper('flexibleblog')->__('Home'),
                                'title' => Mage::helper('flexibleblog')->__('Home'),
                                'link'  => Mage::getBaseUrl()
                        )
                );

                $breadcrumbs->addCrumb($url,
                        array(
                                'label' => Mage::helper('flexibleblog')->__($meta_title),
                                'title' => Mage::helper('flexibleblog')->__($meta_title),
                                'link'  => Mage::getUrl($url)
                        )
                );

                $breadcrumbs->addCrumb('post',
                        array(
                                'label' => Mage::helper('flexibleblog')->__($collection->getPostTitle()),
                                'title' => Mage::helper('flexibleblog')->__($collection->getPostTitle())
                        )
                );
        }
        $this->renderLayout();
    }

    /**
     * submit Action
     *
     * @return Comment form submission on Blog detail page
     */
    public function submitAction()
    {
        ob_start();
        $post = $this->getRequest()->getPost();
        if ( $post ) {
            try
            {
                $commentData = new Varien_Object();
                $commentData->setData($post);

                $error = false;
                $formValid = '';

                if (!Zend_Validate::is(trim($post['comment_name']) , 'NotEmpty')) {
                    $error = true;
                    $formValid .= $this->__("Name is a required field.").'<br>';
                }
                if (!Zend_Validate::is(trim($post['comment_email']), 'EmailAddress')) {
                    $error = true;
                    $formValid .= $this->__("Please enter a valid email address. For example johndoe@domain.com.").'<br>';
                }
                if (!Zend_Validate::is(trim($post['comment_description']), 'NotEmpty')) {
                    $error = true;
                    $formValid .= $this->__("Comment is a required field.").'<br>';
                }
                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                    $formValid .= $this->__("You are a gutless robot.").'<br>';
                }

                /**
                * Redirect to form if form not validate
                **/
                if($formValid != '')
                {
                    Mage::getSingleton('core/session')->addError($this->__($formValid));
                    $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
                    return;
                }

                if ($error) {
                    throw new Exception();
                }

                //Call comment model
                $commentModel           = Mage::getModel('flexibleblog/comment');
		$helper                 = Mage::helper('flexibleblog');
                $post['comment_status'] = $helper->enableAutoApproveComment() ? '1' : '2';
		$comment_status         = $helper->enableAutoApproveComment() ? $this->__('Approved') : $this->__('Pending Approval');
                $comment_ip             = Mage::helper('core/http')->getRemoteAddr();
                $post['comment_ip']     = $comment_ip;
                $saveComment            = $commentModel->setData($post)
                                                        ->setCommentCreatedTime(NOW())
                                                        ->save();

                if($helper->enableComment())
                {
                        $emailTemplate  = Mage::getModel('core/email_template');
                        $storeId        = Mage::app()->getStore()->getId();
                        $templateId     = Mage::helper('flexibleblog')->getEmailTemplateId();
                        $receiverId     = Mage::helper('flexibleblog')->getReceiverEmail();
                        $subject        = Mage::helper('flexibleblog')->getEmailSubject() ? Mage::helper('flexibleblog')->getEmailSubject() : $this->__('Received a new comment');
                        $sender         = array( 'name' => $commentData->getCommentName(), 'email' => $commentData->getCommentEmail() );
                        $replyTo        = $commentData->getCommentEmail();
                        $emailTemplateVariables = array(
                                                        'post_name'     => $commentModel->getCommentPostTitle($commentData->getPostId()),
                                                        'sender_name'   => $commentData->getCommentName(),
                                                        'sender_email'  => $commentData->getCommentEmail(),
                                                        'website'       => $commentData->getCommentWebsite(),
                                                        'description'   => $commentData->getCommentDescription(),
                                                        'comment_status'=> $comment_status,
                                                        'sender_ip'     => $comment_ip
                                                    );
                        $timezone = Mage::getStoreConfig('general/locale/timezone');
                        date_default_timezone_set($timezone);

                        $emailTemplate->setTemplateSubject($subject);

                        $emailTemplate = $emailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId));
                        $emailTemplate->setReplyTo($replyTo)->sendTransactional($templateId, $sender, $receiverId, Mage::helper('flexibleblog')->getAdminName(), $emailTemplateVariables, $storeId);
                }
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('flexibleblog')->__("Your comment has been submitted and is awaiting approval"));
                $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
            }
            catch (Exception $e)
            {
                Mage::getSingleton('core/session')->addError($this->__('Unable to submit your request. Please, try again later'));
                $this->_redirectUrl(Mage::helper('core/http')->getHttpReferer(true));
                return;
            }
        }
    }
}