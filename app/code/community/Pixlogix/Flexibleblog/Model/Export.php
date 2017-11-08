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
 * Flexibleblog Category model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Model_Export extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleblog/export');
    }
    
    //to get csv record string
    public function exportRecords( $postCollection ){
        $column =''; 
        $list=array();

        //CSV column name
        $list[] = array('id', 'store_ids', 'title', 'post_url','status','description','short_description','categories','tags','enable_comments','image','publish_date','author','page_layout','custom_layout_update','meta_title','meta_keywords','meta_description');
        $column .= $columnTitle;
        if( $postCollection->count() ){
            foreach( $postCollection as $post ){
                $postStoreView      = $post->getPostStoreView();
                $postTitle          = $post->getPostTitle();
                $postUrlKey         = $post->getPostUrlKey();
                $postStatus         = ( $post->getPostStatus() == 1 ) ? 'Enabled' : 'Disabled';
                $postDescription    = $post->getPostDescription();
                $postShortDescription = $post->getPostShortDescription();

                $postCategories     = ''; //post category
                $strCategories      = $post->getPostCategories();
                if($strCategories){
                    $arrCategories = explode( ',',$strCategories );
                    $postCategory = array();
                    foreach( $arrCategories as $category ){
                        if($category){
                            $categoryID     = trim( $category );
                            $postCategory[] = Mage::getModel( 'flexibleblog/category' )->getCollection()
                                                ->addFieldToFilter( 'category_id', array( 'eq' => $categoryID ) )
                                                ->getFirstItem()
                                                ->getCategoryTitle();
                        }
                    }
                    if( count( $postCategory ) ){
                        $postCategories = implode( ',',$postCategory );
                    }
                }

                $postTags     = ''; //post tags
                $strTags      = $post->getPostTags();
                if($strTags){
                    $arrTags = explode( ',',$strTags );
                    $postTag = array();
                    foreach( $arrTags as $tag )
                    {
                        if( $tag )
                        {
                            $tagID     = trim( $tag );
                            $postTag[] = Mage::getModel( 'flexibleblog/tag' )->getCollection()
                                            ->addFieldToFilter( 'tag_id', array( 'eq' => $tagID ) )
                                            ->getFirstItem()
                                            ->getTagName();
                        }
                    }

                    if( count( $postTag ) ){
                        $postTags = implode( ',',$postTag );
                    }
                }
                $postComments       = ( $post->getPostAllowComments() == 1 ) ? 'Enabled' : 'Disabled';

                $arrStoreView      =  ( $post->getPostStoreView() || !$post->getPostStoreView() != 0 ) ? explode( ',',$post->getPostStoreView() ) : array(0) ;
                if( !Mage::app()->isSingleStoreMode() && count( $arrStoreView ) && !in_array( '0',$arrStoreView ) )
                {
                    $arrStore = array();
                    foreach( $arrStoreView as $storeId )
                    {
                        $storeId = trim( $storeId );
                        if( $storeId != '' )
                        {
                            $arrStore[] = Mage::app()->getStore( $storeId )->getCode();
                        }
                    }

                    if( count( $arrStore ) )
                    {
                        $postStoreView  = implode( ',', $arrStore );
                    }
                }
                else if( !Mage::app()->isSingleStoreMode() && count( $arrStoreView ) && in_array(0,$arrStoreView) )
                {
                    $siteStores= array();
                    $allStores = Mage::app()->getStores();
                    foreach ($allStores as $_eachStoreId => $val)
                    {
                        $SiteStores[] = Mage::app()->getStore($_eachStoreId)->getCode();
                    }
                    $postStoreView = implode( ',',$SiteStores );
                }
                else
                {
                    $postStoreView = '';
                }
                $postImage          = $post->getPostImage();
                $postPublishDate    = date( 'Y-m-d H:i:s',strtotime( $post->getPostPublishDate() ) );
                $authorID           = $post->getPostAuthor();

                //To get author name using author id
                $postAuthor         = Mage::getModel( 'flexibleblog/author' )->getCollection()
                                        ->addFieldToFilter( 'author_id', array( 'eq' => $authorID ) )
                                        ->getFirstItem()->getAuthorUrlKey();
                $postCustomLayout   = '';
                $layout             = $post->getPostCustomLayout();
                switch($layout)
                {
                    case 'empty':
                        $postCustomLayout = 'Empty';
                        break;
                    case 'one_column':
                        $postCustomLayout = '1 column';
                        break;
                    case 'two_columns_left':
                        $postCustomLayout = '2 columns with left bar';
                        break;
                    case 'two_columns_right':
                        $postCustomLayout = '2 columns with right bar';
                        break;
                    case 'three_columns':
                        $postCustomLayout = '3 columns';
                        break;
                }
                $postCustomLayoutUpdate = $post->getPostCustomLayoutUpdate();
                $postMetaTitle      = $post->getPostMetaTitle();
                $postMetaKeyword    =  $post->getPostMetaKeyword();
                $postMetaDescription= $post->getPostMetaDescription();

                //Add posts data to array for csv
                $list[]=array(
                    $post->getPostId(),
                    $postStoreView,
                    $postTitle,
                    $postUrlKey,
                    $postStatus,
                    $postDescription,
                    $postShortDescription,
                    $postCategories,
                    $postTags,
                    $postComments,
                    $postImage,
                    $postPublishDate,
                    $postAuthor,
                    $postCustomLayout,
                    $postCustomLayoutUpdate,
                    $postMetaTitle,
                    $postMetaKeyword,
                    $postMetaDescription
                    );
           }
        }
        return $list;
    }
}