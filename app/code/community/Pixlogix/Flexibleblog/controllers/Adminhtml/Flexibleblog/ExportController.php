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
 * Adminhtml_Flexibleblog_ExportController
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Adminhtml_Flexibleblog_ExportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu( 'flexibleblog/export' )
            ->_addBreadcrumb( Mage::helper( 'adminhtml' )->__( 'Export Data' ), Mage::helper( 'adminhtml' )->__( 'Export Data' ) );
        return $this;
    }

    // For Index action
    public function indexAction(){
        $this->_title( $this->__( 'Flexible Blog' ) );
        $this->_title( $this->__( 'Export Data' ) );
        Mage::register( 'flexibleblog_data', $flexibleblogModel );

        $this->loadLayout();
        $this->_setActiveMenu( 'flexibleblog/export' );

        $this->_addBreadcrumb( Mage::helper( 'adminhtml' )->__( 'Export Record' ), Mage::helper( 'adminhtml' )->__( 'Export Data' ) );
        $this->_addContent( $this->getLayout()->createBlock( 'flexibleblog/adminhtml_export_edit' ) )
            ->_addLeft( $this->getLayout()->createBlock( 'flexibleblog/adminhtml_export_edit_tabs' ) );
        $this->renderLayout();
    }

    //Import csv file
    public function saveAction(){

        $postData = $this->getRequest()->getPost();
        try {
            //Filter parameters
            $fromDate   = date( 'Y-m-d H:i:s',strtotime( $postData[ 'export_date_from'] ) );
            $toDate     = date( 'Y-m-d H:i:s',strtotime( $postData[ 'export_date_to' ] ) );
            $arrCategory= $postData[ 'post_categories' ];
            $tagStr     = $postData[ 'post_tags' ];
            $author     = $postData[ 'post_author' ];
            $status     = $postData[ 'post_status' ];

            $postCollection = Mage::getModel( 'flexibleblog/post' )->getCollection();
            if( $postData[ 'export_date_from' ] != '' && $postData[ 'export_date_to'] != '' ){ //Condition to check date when from and to date selected
                $postCollection->addFieldToFilter(
                                        array( 'post_publish_date' ),
                                        array( array( 'from' => $fromDate,'to' => $toDate ) )
                                    );
            }elseif( $postData[ 'export_date_from'] != '' && $postData[ 'export_date_to'] == '' ){ //Condition to check only from date
                $postCollection->addFieldToFilter( 'post_publish_date', array( 'gteq' => $fromDate ) );
            }elseif( $postData[ 'export_date_from'] == '' && $postData[ 'export_date_to'] != '' ){ //Condition to check only to date
                $postCollection->addFieldToFilter( 'post_publish_date', array( 'lteq' => $toDate ) );
            }
            
            if( !Mage::app()->isSingleStoreMode() &&  !in_array( 0, $postData['stores'] ) ) {
                $arrStore = array();
                foreach($postData['stores'] as $store){
                    $storeId = Mage::app()->getStore($store)->getId();
                    $arrStore[] = array( 'finset'=> $storeId );
                }
                if( count( $arrStore ) ){
                    $postCollection->addFieldToFilter( 'post_store_view', $arrStore );
                }
            }

            if( count( $postData['post_categories'] )  && ( !in_array( '0',$postData[ 'post_categories' ] ) ) ){
                $arrCategory = array();
                foreach( $postData[ 'post_categories' ] as $postCategory ){
                    $arrCategory[] = array( 'finset'=> $postCategory );
                }

                if( count( $arrCategory ) ){
                    $postCollection->addFieldToFilter( 'post_categories', $arrCategory );
                }
            }

            if( $postData['post_tags'] ){
                $arrTags    = array();
                $arrTagList = explode( ',',$postData['post_tags'] );
                foreach($arrTagList as $postTag ){
                    $postTag  = trim( $postTag );
                    if( $postTag ){
                        $TagModel   = Mage::getModel( 'flexibleblog/tag' )->getCollection()
                                        ->addFieldToFilter( 'tag_name', array( 'eq'=>$postTag ) )
                                        ->getFirstItem();
                        $tagId      = $TagModel->getTagId();
                        $arrTags[]  = array( 'finset'=>$tagId );
                    }
                }
                if( count( $arrTags ) ){
                    $postCollection->addFieldToFilter( 'post_tags', $arrTags );
                }
            }

            if( $status != 0 ){
                $postCollection->addFieldToFilter( 'post_status', array( 'eq' => $status ) );
            }

            if( $author != '' ){
                $postCollection->addFieldToFilter( 'post_author', array( 'eq' => $author ) );
            }
            $fileName = 'flexibleblog_export.csv';
            $filepath = Mage::getBaseDir('media').'/flexibleblog/file/'.$fileName;
            $list=array();
            $list = Mage::getModel( 'flexibleblog/export' )->exportRecords( $postCollection );
            $fp = fopen($filepath, 'w');
            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
            $fileurl = Mage::getBaseUrl("media") .'flexibleblog/file/'.$fileName;
            $this->getResponse ()
                ->setHttpResponseCode ( 200 )
                ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )
                ->setHeader ( 'Pragma', 'public', true )
                ->setHeader ( 'Content-type', 'application/force-download' )
                ->setHeader ( 'Content-Disposition', 'attachment' . '; filename=' . basename($fileurl) );
            $this->getResponse()->clearBody();
            $this->getResponse()->sendHeaders();
            readfile ( $filepath );
            return;
        } catch( Exception $e ){
            Mage::getSingleton( 'adminhtml/session' )->addError( $e->getMessage() );
            Mage::getSingleton( 'adminhtml/session' )->setFlexibleblogData( $this->getRequest()->getPost() );
            $this->_redirect( '*/*/' );
            return;
        }

        $this->_redirect( '*/*/' );
        return;
    }

    // For role specific permission
    protected function _isAllowed()
    {
        return true;
    }

    protected function _sendUploadResponse( $fileName, $content, $contentType = 'application/octet-stream' ) {
        $response = $this->getResponse();
        $response->setHeader( 'HTTP/1.1 200 OK', '' );
        $response->setHeader( 'Pragma', 'public', true );
        $response->setHeader( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true );
        $response->setHeader( 'Content-Disposition', 'attachment; filename=' . $fileName );
        $response->setHeader( 'Last-Modified', date( 'r' ) );
        $response->setHeader( 'Accept-Ranges', 'bytes' );
        $response->setHeader( 'Content-Length', strlen( $content ) );
        $response->setHeader( 'Content-type', $contentType );
        $response->setBody( $content );
        $response->sendResponse();
        return;
    }
}