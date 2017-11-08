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
 * Adminhtml_Flexibleblog_ImportController
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Adminhtml_Flexibleblog_ImportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu( 'flexibleblog/import' )
            ->_addBreadcrumb( Mage::helper( 'adminhtml' )->__( 'Manage Categories' ), Mage::helper( 'adminhtml' )->__( 'Manage Categories' ) );
        return $this;
    }

    // For Index action
    public function indexAction(){
        $this->_title( $this->__( 'Flexible Blog' ) );
        $this->_title( $this->__( 'Import Data' ) );
        Mage::register( 'flexibleblog_data', $flexibleblogModel );

        $this->loadLayout();
        $this->_setActiveMenu( 'flexibleblog/items' );

        $this->_addBreadcrumb( Mage::helper( 'adminhtml' )->__( 'Import Record' ), Mage::helper( 'adminhtml' )->__( 'Import Data' ) );
        $this->_addContent( $this->getLayout()->createBlock( 'flexibleblog/adminhtml_import_edit' ) )
            ->_addLeft( $this->getLayout()->createBlock( 'flexibleblog/adminhtml_import_edit_tabs' ) );
        $this->renderLayout();
    }

    //Import csv file
    public function saveAction(){
        $postData = $this->getRequest()->getPost();
        $filename = $_FILES['import_file']['name'];
        $filepath = $_FILES['import_file']['tmp_name'];
        try {

            if( $filename != '' ){ //Check file not null

                //To retrive file extension
                $arrFile        = explode( '.',$filename ); 
                $arrReverse     = array_reverse( $arrFile );
                $fileExt        = $arrReverse[0];
                $arrAllowedFile = array( 'csv' );

                if( in_array( $fileExt,$arrAllowedFile ) ) { //Check file extension

                    //Check csv sheet to identify error
                    if( ( $handle = fopen( $_FILES['import_file']['tmp_name'], 'r' ) ) !== FALSE ){

                        $counter        = 0;
                        $errorCounter   = 0;
                        $arrColumn      = array();
                        $errorTitleRowIds= array();
                        $errorAuthorRowIds= array();

                        while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) { //csv loop start

                            //First column of csv
                            if($counter == 0){
                                $arrColumn = $data;
                                $counter++;
                                if( !array_search( 'title',$arrColumn ) || !array_search( 'author',$arrColumn ) )
                                {
                                    Mage::getSingleton( 'adminhtml/session' )->addError( $this->__( 'Something is wrong, Please check your csv file format' ) );
                                    $this->_redirect( '*/*/' );
                                    return;
                                }
                                continue; //To skip first column record to import
                            }

                            //check title value
                            if( $key = array_search( 'title',$arrColumn ) ){
                                if( $data[$key] ==  '' ){
                                    $errorCounter++;
                                    $errorTitleRowIds[] = $counter; //store csv row no for error
                                }
                            }

                            //check author value
                            if( $key = array_search( 'author',$arrColumn ) ){
                                if( $data[$key] ==  ''){
                                    $errorCounter++;
                                    $errorAuthorRowIds[] = $counter; //store csv row no for error
                                }else{
                                    $author_check = Mage::getModel( 'flexibleblog/author' )->getCollection()
                                                ->addFieldToFilter( 'author_url_key', array( 'eq' => $data[$key]) );
                                    if( $author_check->count() == 0 ){
                                        $errorCounter++;
                                        $errorAuthorRowIds[] = $counter; //store csv row no for error
                                    }
                                }
                            }

                            $counter++;
                            if( $counter >= 100 ){ //Break loop when no of errors exceed limit
                                break;
                            }
                        }
                        fclose($handle);

                        if($errorCounter > 0){

                            $errorTitleStrIds   = implode( ',', $errorTitleRowIds );
                            $errorAuthorStrIds  = implode( ',', $errorAuthorRowIds );

                            $errorMsg           = 'CSV file contain ' . $errorCounter . ' error(s). Check below mention error(s) <br/>';

                            if( count( $errorTitleRowIds ) > 0 ){ //Error msg for title missing
                                $errorMsg      .= $this->__( 'Title is missing. Check row no. : ' . $errorTitleStrIds .'<br/>' );
                            }

                            if( count( $errorAuthorRowIds ) > 0 ){ //Error msg for author not exists
                                $errorMsg      .= $this->__('Author name is not exists. Check row no. : ' . $errorAuthorStrIds );
                            }
                            Mage::getSingleton( 'adminhtml/session' )->addError( $errorMsg );
                            $this->_redirect( '*/*/' );
                            return;
                        }
                    }

                    //To import csv record
                    if( ( $handle = fopen( $_FILES['import_file']['tmp_name'], 'r' ) ) !== FALSE ){

                        //Import record
                        $counter        = 0;
                        $recordCounter  = 0;
                        $arrColumn      = array();

                        while ( ( $data = fgetcsv( $handle, 9999, "," ) ) !== FALSE ) { //csv loop start

                            //First column of csv
                            if( $counter == 0 ){
                                $arrColumn = $data;
                                $counter++;
                                continue; //To skip first column record to import
                            }

                            $flexibleBlogModel = Mage::getModel( 'flexibleblog/post' );
                            $flexibleBlogModel->setData( $postData );

                            //Import title value
                            if( $key = array_search( 'title',$arrColumn ) ){
                                $post_title = $data[$key];
                                $flexibleBlogModel->setPostTitle( $data[$key] );
                            }

                            //Import title value
                            if( $key = array_search( 'post_url',$arrColumn ) ){
                                if( $data[$key] == '' ){
                                    $post_url   = Mage::getModel( 'flexibleblog/post' )->makeUniquePostUrl( $post_title );
                                }else{
                                    $post_check = Mage::getModel( 'flexibleblog/post' )->getCollection()
                                                    ->addFieldToFilter( 'post_url_key', array( 'eq' => $data[$key] ));

                                    if( $post_check->count() > 0 ){ //to skip row if post url key already exists
                                        continue;
                                    }
                                    $post_url = $data[$key];
                                }
                                $flexibleBlogModel->setPostUrlKey( $post_url );
                            }

                            if( !Mage::app()->isSingleStoreMode() )
                            {
                                //Import post status value
                                if( $key = array_search( 'store_ids',$arrColumn ) )
                                {
                                    $arrstoreId = array();
                                    if( $data[$key] != '')
                                    {
                                        $arrStoreCode = explode( ',', $data[$key] );
                                        $allStores = Mage::app()->getStores();
                                        foreach ($allStores as $_eachStoreId => $val)
                                        {
                                            $SiteStores[] = Mage::app()->getStore($_eachStoreId)->getId();
                                        }
                                        foreach( $arrStoreCode as $store ){
                                            if($store != ''){ //To check null value
                                                $arrstoreId[] = trim( $store );
                                            }
                                        }
                                    }
                                    if( count( $arrstoreId ) ){
                                        $compareStore  = array_diff($SiteStores,$arrstoreId);
                                        $countDiffStore = count($compareStore);
                                        if($countDiffStore > 0 ){
                                            $arrstoreIds = implode( ',', $arrstoreId );
                                            $flexibleBlogModel->setPostStoreView( $arrstoreIds );
                                        }else{
                                            array_push($SiteStores, 0);
                                            $StrSiteStores = implode( ',', $SiteStores );
                                            $flexibleBlogModel->setPostStoreView( $StrSiteStores );
                                        }
                                    }
                                    else{
                                        $flexibleBlogModel->setPostStoreView( 0 );
                                    }
                                }
                                else if( $key = array_search( 'store_codes',$arrColumn ) )
                                {
                                    $arrstoreId =array();
                                    if( $data[$key] != '')
                                    {
                                        $arrStoreCode = explode( ',', $data[$key] );
                                        //To access all store ids
                                        $allStores = Mage::app()->getStores();
                                        foreach ($allStores as $_eachStoreId => $val)
                                        {
                                            $SiteStores[] = Mage::app()->getStore($_eachStoreId)->getId();
                                        }
                                        //To retrive csv store ids
                                        foreach( $arrStoreCode as $store ){
                                            if($store != ''){ //To check null value
                                                $store          = trim( $store );
                                                $arrstoreId[]   = Mage::getModel('core/store')->load($store, 'code')->getId();
                                           }
                                        }
                                    }

                                    if( count( $arrstoreId ) ){
                                        //Compare all store ids with csv store ids
                                        $compareStore  = array_diff($SiteStores,$arrstoreId);
                                        $countDiffStore = count($compareStore);
                                        if($countDiffStore > 0 ){
                                            $arrstoreIds = implode( ',', $arrstoreId );
                                            $flexibleBlogModel->setPostStoreView( $arrstoreIds );
                                        }else{
                                            array_push($SiteStores, 0);
                                            $StrSiteStores = implode( ',', $SiteStores );
                                            $flexibleBlogModel->setPostStoreView( $StrSiteStores );
                                        }
                                    }
                                    else{
                                        $flexibleBlogModel->setPostStoreView( 0 );
                                    }
                                }
                            }
                            else
                            {
                                $flexibleBlogModel->setPostStoreView( 0 );
                            }

                            //Import post status value
                            if( $key = array_search( 'status',$arrColumn ) ){
                                $status = ( $data[$key] == 'Enabled' ) ? 1 : 2;
                                $flexibleBlogModel->setPostStatus( $status );
                            }

                            //Import post description value
                            if( $key = array_search( 'description', $arrColumn ) ){
                                $flexibleBlogModel->setPostDescription( $data[$key] );
                            }

                            //Import post short description value
                            if( $key = array_search( 'short_description',$arrColumn ) ){
                                $flexibleBlogModel->setPostShortDescription( $data[$key] );
                            }

                            //Import post categories value
                            if( $key = array_search( 'categories',$arrColumn ) ){

                                if($data[$key]){
                                    $arrCategory = explode( ',', $data[$key] );
                                    $arrCategoryId =array();
                                    foreach( $arrCategory as $category ){
                                        if($category != ''){ //TO check null value
                                            $category = trim($category);
                                            $categoryModel  = Mage::getModel( 'flexibleblog/category' )->getCollection()
                                                            ->addFieldToFilter( 'category_title', array( 'eq' => $category ) )
                                                            ->getFirstItem();
                                            if( $categoryModel->getCategoryId() ){
                                                $arrCategoryId[] = $categoryModel->getCategoryId();
                                            }else{
                                                $category_url = Mage::getModel( 'flexibleblog/category' )->makeUniqueCategoryUrl( $category );
                                                Mage::getModel( 'flexibleblog/category' )->setData( $postData )
                                                            ->setCategoryTitle( $category )
                                                            ->setCategoryUrlKey( $category_url )
                                                            ->setCategoryStatus( 1 )
                                                            ->setCategoryCreatedTime( now() )
                                                            ->setCategoryUpdateTime( now() )
                                                            ->save();
                                                $arrCategoryId[] = Mage::getModel( 'flexibleblog/category' )->getCategoryByUrl( $category_url );
                                            }
                                        }
                                    }
                                }

                                if( count( $arrCategoryId ) ){
                                    $categoryIds = implode( ',', $arrCategoryId );
                                    $flexibleBlogModel->setPostCategories( $categoryIds );
                                }
                            }

                            //Import post Tags value
                            if( $key = array_search( 'tags',$arrColumn ) ){
                                if( $data[$key] ){
                                    $arrTag     = explode( ',',$data[$key] );
                                    $arrTagId   = array();
                                    foreach( $arrTag as $tag ){
                                        if($tag != ''){ //TO check null value
                                            $tag = trim($tag);
                                            $tagModel  = Mage::getModel( 'flexibleblog/tag' )->getCollection()
                                                            ->addFieldToFilter( 'tag_name', array( 'eq' => $tag ) )
                                                            ->getFirstItem();

                                            if( $tagModel->getTagId() ){
                                                $arrTagId[] = $tagModel->getTagId();
                                            }else{
                                                $tag_url = Mage::getModel( 'flexibleblog/tag' )->makeUniqueTagUrl( $tag );
                                                Mage::getModel( 'flexibleblog/tag' )->setData( $postData )
                                                            ->setTagName( $tag )
                                                            ->setTagUrlKey( $tag_url )
                                                            ->save();
                                                $arrTagId[] = Mage::getModel( 'flexibleblog/tag' )->getTagId();
                                            }
                                        }
                                    }
                                }

                                if( count( $arrTagId ) )
                                {
                                    $tagIds = implode( ',', $arrTagId );
                                    $flexibleBlogModel->setPostTags( $tagIds );
                                }
                            }

                            //Import post comments value
                            if( $key = array_search( 'enable_comments',$arrColumn ) ){
                                $comments = ( $data[$key] == 'Enabled' ) ? 1 : 2;
                                $flexibleBlogModel->setPostAllowComments( $comments );
                            }

                            //Import post comments value
                            if( $key = array_search( 'image',$arrColumn ) ){
                                $flexibleBlogModel->setPostImage( $data[$key] );
                            }

                            //Import post publish date value
                            if( $key = array_search( 'publish_date', $arrColumn ) ){
                                $publishDate =  date( 'Y-m-d h:i:s', strtotime( $data[$key] ) );
                                $flexibleBlogModel->setPostPublishDate( $publishDate );
                                $flexibleBlogModel->setPostCreatedTime( $publishDate );
                                $flexibleBlogModel->setPostUpdateTime( $publishDate );
                            } else{
                                $publishDate =  now();
                                $flexibleBlogModel->setPostPublishDate( $publishDate );
                                $flexibleBlogModel->setPostCreatedTime( $publishDate );
                                $flexibleBlogModel->setPostUpdateTime( $publishDate );
                            }
                            

                            //Import post author
                            if( $key = array_search( 'author',$arrColumn ) ){
                                if($data[$key]){
                                    $authorModel  = Mage::getModel( 'flexibleblog/author' )->getCollection()
                                                    ->addFieldToFilter( 'author_url_key', array( 'eq' => $data[$key] ) )
                                                    ->getFirstItem();

                                    if( $authorModel->getAuthorId() ){
                                        $flexibleBlogModel->setPostAuthor( $authorModel->getAuthorId() );
                                    }
                                }
                            }

                            //Import post page layout value
                            if( $key = array_search( 'page_layout', $arrColumn ) ){
                                $pageLayout = '';
                                if($data[$key] == "Empty" ){
                                    $pageLayout = 'empty';
                                }
                                elseif($data[$key] == "1 column" ){
                                    $pageLayout = 'one_column';
                                }
                                elseif($data[$key] == "2 columns with left bar" ){
                                    $pageLayout = 'two_columns_left';
                                }
                                elseif($data[$key] == "2 columns with right bar" ){
                                    $pageLayout = 'two_columns_right';
                                }
                                elseif($data[$key] == "3 columns" ){
                                    $pageLayout = 'three_columns';
                                }
                                $flexibleBlogModel->setPostCustomLayout( $pageLayout );
                            }

                            //Import post Custom Layout Update value
                            if( $key = array_search( 'custom_layout_update', $arrColumn ) ) {
                                $value = $data[$key];
                                $post_custom_layout_update = new Varien_Simplexml_Element('<config>' . $value . '</config>');
                                if($post_custom_layout_update):
                                    $flexibleBlogModel->setPostCustomLayoutUpdate( $value );
                                endif;
                            }

                            //Import post Meta Title value
                            if( $key = array_search( 'meta_title', $arrColumn ) ) {
                                $flexibleBlogModel->setPostMetaTitle( $data[$key] );
                            }

                            //Import post Meta Keywords value
                            if( $key = array_search( 'meta_keywords', $arrColumn ) ) {
                                $flexibleBlogModel->setPostMetaKeyword( $data[$key] );
                            }

                            //Import post Meta Description value
                            if( $key = array_search( 'meta_description', $arrColumn ) ) {
                                $flexibleBlogModel->setPostMetaDescription( $data[$key] );
                            }
                            if($flexibleBlogModel->save()) {
                                $recordCounter++;
                            }
                        }//Csv loop end
                        fclose( $handle ); //To close file
                    }
                    $sucessMsg = $this->__( $recordCounter.' CSV record(s) import successfully' );
                    Mage::getSingleton( 'adminhtml/session' )->addSuccess( $sucessMsg );
                    $this->_redirect('*/*/');
                    return;
                }else{
                    Mage::getSingleton('adminhtml/session')->addError( "Only CSV file allow to import data" );
                    $this->_redirect('*/*/');
                    return;
                }
            }else{
                Mage::getSingleton( 'adminhtml/session' )->addError( "Upload csv file to import data" );
                $this->_redirect('*/*/');
                return;
            }
        } catch ( Exception $e ) {
            Mage::getSingleton( 'adminhtml/session' )->addError( $e->getMessage() );
            Mage::getSingleton( 'adminhtml/session' )->setFlexibleblogData( $this->getRequest()->getPost() );
            $this->_redirect( '*/*/' );
            return;
        }
        $this->_redirect( '*/*/' );
        return;
    }

    public function downloadAction()
    {
        $filepath = Mage::getBaseUrl("media") .'flexibleblog/file/sample.csv';
        $this->getResponse ()
            ->setHttpResponseCode ( 200 )
            ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )
            ->setHeader ( 'Pragma', 'public', true )
            ->setHeader ( 'Content-type', 'application/force-download' )
            ->setHeader ( 'Content-Disposition', 'attachment' . '; filename=' . basename($filepath) );
        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();
        readfile ( $filepath );
        return;
    }
    // For role specific permission
    protected function _isAllowed()
    {
        return true;
    }
}