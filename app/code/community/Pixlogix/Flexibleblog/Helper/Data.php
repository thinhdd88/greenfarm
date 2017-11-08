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
 * Flexibleblog data helper
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * To make identifier key from title
     */
    public function makeUrlKey($postTitle,$key='')
    {
        if(!isset($key) || empty($key))
        {
            $urlKey = Mage::getSingleton('catalog/product')->formatUrlKey($postTitle);
            return $urlKey;
        }
        else 
        {
            $urlKey = trim(str_replace(' ','-',$key));
            return $urlKey;
        }
    }

    /**
     * To enable or disable Flexibleblog extension
     */
    public function enableFlexibleblog()
    {
        return Mage::getStoreConfig('flexibleblog_options/general/enable');
    }

    /**
     * To enable or disable jQuery library file
     */
    public function enabledjQuery()
    {
        return Mage::getStoreConfig('flexibleblog_options/general/enable_jquery');
    }

    /**
     * To enable or disable Blog top link
     */
    public function enableTopLink()
    {
        return Mage::getStoreConfig('flexibleblog_options/general/enable_top_link');
    }

    /**
     * To enable or disable Blog footer link
     */
    public function enableFooterLink()
    {
        return Mage::getStoreConfig('flexibleblog_options/general/enable_footer_link');
    }

    /**
     * To enable or disable Blog breadcrums link
     */
    public function enableBreadcrumbs()
    {
        return Mage::getStoreConfig('flexibleblog_options/general/enable_breadcrumbs');
    }

    /**
     * set post per page
     */
    public function getPostPerPage()
    {
        return Mage::getStoreConfig('flexibleblog_options/post_list/posts_per_page');
    }

    /**
     * To enable or disable Search Widget
     */
    public function enableSearchWidget()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/search');
    }

    /**
     * set Search Widget Position
     */
    public function getSearchWidgetPosition()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/search_position');
    }

    /**
     * To set custom blog url
     */
    public function customBlogUrl()
    {
        $blogUrl = Mage::getStoreConfig('flexibleblog_options/general/blog_url') ? Mage::getStoreConfig('flexibleblog_options/general/blog_url') : 'blog';
        return $blogUrl;
    }
    
    /**
     * To set custom blog url
     */
    public function blogTitle()
    {
        $blogTitle = Mage::getStoreConfig('flexibleblog_options/general/blog_title') ? Mage::getStoreConfig('flexibleblog_options/general/blog_title') : 'Blog';
        return $blogTitle;
    }

    /**
     * To enable or disable Categories Widget
     */
    public function enableCategoriesWidget()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/categories');
    }

    /**
     * set Categories Widget Position
     */
    public function getCategoriesWidgetPosition()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/categories_position');
    }

    /**
     * set Categories Widget Entries
     */
    public function getCategoriesWidgetEntries()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/categories_latest_number');
    }

    /**
     * To enable or disable Archives Widget
     */
    public function enableArchivesWidget()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/archives');
    }

    /**
     * set Archives Widget Position
     */
    public function getArchivesWidgetPosition()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/archives_position');
    }

    /**
     * set Archives Widget Entries
     */
    public function getArchivesWidgetEntries()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/archives_latest_number');
    }

    /**
     * To enable or disable Recent Post Widget
     */
    public function enableRecentPostWidget()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/recent_posts');
    }

    /**
     * set Recent Post Widget Position
     */
    public function getRecentPostWidgetPosition()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/recent_posts_position');
    }

    /**
     * set Recent Post Widget Entries
     */
    public function getRecentPostWidgetEntries()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/recent_posts_latest_number');
    }

    /**
     * To enable or disable Recent Comments Widget
     */
    public function enableRecentCommentsWidget()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/recent_comments');
    }

    /**
     * set Recent Comments Widget Position
     */
    public function getRecentCommentsWidgetPosition()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/recent_comments_position');
    }

    /**
     * set Recent Comments Widget Entries
     */
    public function getRecentCommentsWidgetEntries()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/recent_comments_latest_number');
    }

    /**
     * To enable or disable TagCloud Widget
     */
    public function enableTagCloudWidget()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/tag_cloud');
    }

    /**
     * set TagCloud Widget Position
     */
    public function getTagCloudWidgetPosition()
    {
        return Mage::getStoreConfig('flexibleblog_options/sidebar/tag_cloud_position');
    }

    /**
     * To enable or disable Comment
     */
    public function enableComment()
    {
        return Mage::getStoreConfig('flexibleblog_options/comments/enable');
    }
    
    /**
     * To enable or disable Comment
     */
    public function getDisqusUsername()
    {
        if( $this->enableComment() != 2  )
        {
            return;
        }
        return Mage::getStoreConfig('flexibleblog_options/comments/enable_disqus_username');
    }

    /**
     * set Blog Layout
     */
    public function getBlogLayout()
    {
        return Mage::getStoreConfig('flexibleblog_options/general/blog_page_layout');
    }

    /**
     * To enable or disable Auto Approve Comment
     */
    public function enableAutoApproveComment()
    {
        return Mage::getStoreConfig('flexibleblog_options/comments/enable_auto_approve');
    }

    /**
     * Comment enable for login users only or not
     */
    public function loginOnlyComment()
    {
        if(Mage::getStoreConfig('flexibleblog_options/comments/enable_loggedin_users'))
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn())
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
        else
        {
            return 0;
        }
    }

    /**
     * set Admin Name on comment email
     */
    public function getAdminName()
    {
        return Mage::getStoreConfig('flexibleblog_options/comments/admin_name');
    }

    /**
     * set Email Template Id on comment email
     */
    public function getEmailTemplateId()
    {
        return Mage::getStoreConfig('flexibleblog_options/comments/admin_email_template');
    }

    /**
     * set Email Subject on comment email
     */
    public function getEmailSubject()
    {
            return Mage::getStoreConfig('flexibleblog_options/comments/admin_email_subject');
    }

    /**
     * set Receiver Email on comment email
     */
    public function getReceiverEmail()
    {
        return Mage::getStoreConfig('flexibleblog_options/comments/admin_email_address');
    }

    /**
     * To enable or disable Social Share
     */
    public function enableSocialShare()
    {
        return Mage::getStoreConfig('flexibleblog_options/posts/enable_social_share');
    }

    /**
     * To enable or disable Author Info
     */
    public function enableAuthorInfo()
    {
        return Mage::getStoreConfig('flexibleblog_options/posts/enable_author_info');
    }

    /**
     * Image Size for listing page
     */
    public function imageSize()
    {
        $imageSize  = Mage::getStoreConfig('flexibleblog_options/post_list/image_size');
        $arrImage   = array('width'=>'','height'=>'');
        if($imageSize)
        {
            if(strpos($imageSize,'x'))
            {
                $arrImageSize = explode('x',$imageSize);
            }
            else
            {
                $arrImageSize[0] = $imageSize;
            }
            $arrImage['width']=$arrImageSize[0];
            if( strpos($imageSize,'x') && $arrImageSize[1])
            {
                $arrImage['height']=$arrImageSize[1];
            }
        }
        return $arrImage;
    }
        
    //Latest post widget image size
    public function latestPostImageSize()
    {
        $imageSize  = Mage::getStoreConfig('flexibleblog_options/latest_posts_widget/post_image_size');
        $arrImage   = array('width'=>'','height'=>'');
        if($imageSize)
        {
            if(strpos($imageSize,'x'))
            {
                $arrImageSize = explode('x',$imageSize);
            }
            else
            {
                $arrImageSize[0] = $imageSize;
            }
            $arrImage['width']=$arrImageSize[0];
            if( strpos($imageSize,'x') && $arrImageSize[1])
            {
                $arrImage['height']=$arrImageSize[1];
            }
        }
        return $arrImage;
    }

    /**
     * Post Image Size for detail page
     */
    public function postImageSize()
    {
        $imageSize  = Mage::getStoreConfig('flexibleblog_options/posts/post_image_size');
        $arrImage   = array('width'=>'0','height'=>'0');
        if($imageSize)
        {
            $arrImageSize = explode('x',$imageSize);
            $arrImage['width']=$arrImageSize[0];
            if($arrImageSize[1])
            {
                $arrImage['height']=$arrImageSize[1];
            }
        }
        return $arrImage;
    }

    /**
     * image resize
     */
    public function resizeImage($imageName, $width=NULL, $height=NULL, $imagePath=NULL)
    {
        if($imageName){
            $folderpath     = Mage::getBaseDir('media') . DS .'flexibleblog';
            $full_path      = $folderpath.'/'.$imageName;
            $imagePath      = str_replace("/", DS, $imagePath);
            $imagePathFull  = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $imagePath . DS . $imageName;
            if($width == NULL && $height == NULL) {
                $width  = 900;
                $height = 900; 
            }
            $resizePath     = $width . 'x' . $height;
            $resizeImageName= Mage::getModel('flexibleblog/image')->getImageFilename($imageName,$width,$height);
            $resizePathFull = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $imagePath . DS . "resize" . DS . $resizeImageName;
            if (file_exists($imagePathFull) && !file_exists($resizePathFull)) {
                $imageObj = new Varien_Image($imagePathFull);
                $imageObj->constrainOnly(TRUE);
                if($height)
                {
                    $imageObj->keepAspectRatio(false);
                }
                else
                {
                    $imageObj->keepAspectRatio(true);
                }
                $imageObj->keepFrame(FALSE);
                $imageObj->resize($width, $height);
                $imageObj->save($resizePathFull);
            }
            $imagePath = str_replace(DS, "/", $imagePath);
            return Mage::getBaseUrl("media") . $imagePath . "/resize/" . $resizeImageName;
        }
    }

    /**
     * To set blog page layout xml
     */
    public function getBlogLayoutXml($layout)
    {
        $rootLayout = '';
        if(Mage::app()->getRequest()->getModuleName() == 'blog' )
        {
            switch($layout)
            {
                case 'empty':
                    $rootLayout ='<reference name="root"><action method="setTemplate"><template>page/empty.phtml</template></action></reference>';
                    break;
                case 'one_column':
                    $rootLayout ='<reference name="root"><action method="setTemplate"><template>page/1column.phtml</template></action></reference>';
                    break;
                case 'two_columns_left':
                    $rootLayout ='<reference name="root"><action method="setTemplate"><template>page/2columns-left.phtml</template></action></reference>';
                    break;
                case 'two_columns_right':
                    $rootLayout ='<reference name="root"><action method="setTemplate"><template>page/2columns-right.phtml</template></action></reference>';
                    break;
                case 'three_columns':
                    $rootLayout ='<reference name="root"><action method="setTemplate"><template>page/3columns.phtml</template></action></reference>';
                    break;
                default:
                    $rootLayout ='<reference name="root"><action method="setTemplate"><template>page/2columns-right.phtml</template></action></reference>';
            }
        }
        return $rootLayout;
    }

    /**
     * To return store code by store id
     */
    public function getStoreIdByCode($fromstore){
        if($fromstore){
            return Mage::getModel('core/store')->load($store, 'code')->getId();
        }else{
            return false;
        }
    }
    
    /**
     * To return blog route
     */
    public function getRoute($store = null)
    {
        $route = $this->customBlogUrl();
        return $route;
    }

    /**
     * send response when store change
     */
    public function ifStoreChangedRedirect()
    {
        $path           = Mage::app()->getRequest()->getPathInfo();
        $currentRoute   = Mage::getStoreConfig('flexibleblog_options/general/blog_url');
        $fromStore      = Mage::app()->getRequest()->getParam('___from_store');
        if ($fromStore) {
            $fromStoreId= $this->getStoreIdByCode($fromStore);
            $fromRoute  = $this->getRoute($fromStoreId);
            $url        = preg_replace("#$fromRoute#si", $currentRoute, $path, 1);
            $url        = Mage::getBaseUrl() . ltrim($url, '/');

            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($url)
                ->sendResponse();
            return;
        }
    }
}