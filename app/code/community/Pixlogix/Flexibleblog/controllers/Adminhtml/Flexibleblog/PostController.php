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
 * Adminhtml_Flexibleblog_Post Controller
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Adminhtml_Flexibleblog_PostController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('flexibleblog/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Posts'), Mage::helper('adminhtml')->__('Manage Posts'));
        return $this;
    }

    // For Index action
    public function indexAction() {
        $this->_initAction();
        $this->_title($this->__('Flexible Blog'));
        $this->_title($this->__('Posts'));
        $this->_title($this->__('Manage Posts'));
        $this->renderLayout();
    }

    // For Edit action
    public function editAction()
    {
        $flexibleblogId     = $this->getRequest()->getParam('id');
        $flexibleblogModel  = Mage::getModel('flexibleblog/post')->load($flexibleblogId);
        $this->_title($this->__('Flexible Blog'));
        $this->_title($this->__('Posts'));
        $this->_title($this->__('Manage Posts'));
        $this->_title( $flexibleblogModel->getPostId() ? $this->__('Edit \'%s\' Post', $flexibleblogModel->getPostTitle()) : $this->__('Add Post') );

        if ($flexibleblogModel->getId() || $flexibleblogId == 0) {
            Mage::register('flexibleblog_data', $flexibleblogModel);
			//Set Tags in form
			$tagDetails = Mage::getModel('flexibleblog/tag')->getPostTags($flexibleblogId);
			$tags = implode(',',$tagDetails);
			$flexibleblogModel->setData('post_tags',$tags);

            $this->loadLayout();
            $this->_setActiveMenu('flexibleblog/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Posts'), Mage::helper('adminhtml')->__('Manage Posts'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Post'), Mage::helper('adminhtml')->__('Item Post'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit'))
                ->_addLeft($this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleblog')->__('Post does not exist'));
            $this->_redirect('*/*/');
        }
    }

    // For New action
    public function newAction()
    {
        $this->_forward('edit');
    }

    // For Save action
    public function saveAction()
    {
        if ( $this->getRequest()->getPost() ) {
            try {
                $postData = $this->getRequest()->getPost();

                if(isset($postData['stores'])){
                    if(!in_array(0,$postData['stores'])){
                        $postData['post_store_view'] = implode(',',$postData['stores']);
                    }else{
                        $allStores = Mage::app()->getStores();
                        $arrStore = array(0);
                        foreach ($allStores as $_eachStoreId => $val){
                            $storeId    = 0;
                            $_storeId   = Mage::app()->getStore($_eachStoreId)->getId();
                            $arrStore[] = $_storeId;
                        }
                        $postData['post_store_view'] = implode(',',$arrStore);
                    }
                    unset($postData['stores']);
                }
                // Save 'Post Associated Categories' data into table
                foreach ($postData['post_categories'] as $category){
                    $arrCategory[] = $category;
                }
                $categories = implode(',',$arrCategory);

                // Load post data by post id
                $flexibleblogId    = $this->getRequest()->getParam('id');
                $flexibleblogModel = Mage::getModel('flexibleblog/post')->load($flexibleblogId);

                // Save 'Post Url Key' data into table
                if(empty($postData['post_url_key'])){
                    $postData['post_url_key'] = Mage::getSingleton('catalog/product')->formatUrlKey($postData['post_title']);
                } else {
                    $postData['post_url_key'] = trim(str_replace(' ','-', $postData['post_url_key']));
                }

                $_postCollection = Mage::getModel('flexibleblog/post')
                                    ->getCollection()
                                    ->addFieldToSelect('*')
                                    ->addFieldToFilter('post_url_key', array('like' => $postData['post_url_key']));
                if( $this->getRequest()->getParam('id') > 0 )
                {
                    $_postCollection->addFieldToFilter('post_id', array('neq' => $this->getRequest()->getParam('id')));
                }

                $post = $_postCollection->count();
                if($post > 0)
                {
                    $err_msg = $this->__('Post Url Key must be unique');
                    Mage::getSingleton('adminhtml/session')->addError($err_msg);
                    if($this->getRequest()->getParam('id'))
                    {
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;
                    } else {
                        $this->_redirect('*/*/');
                        return;
                    }
                }

                // Save 'Image' data into table
                if(isset($_FILES['post_image']['name']) && (file_exists($_FILES['post_image']['tmp_name']))) {
                    try {
                        $uploader = new Varien_File_Uploader('post_image');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
                        $uploader->setAllowCreateFolders(true);
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);

                        $path = Mage::getBaseDir('media') . DS . 'flexibleblog' . DS;
                        $filename = $_FILES['post_image']['name'];

                        $uploader->save($path, $filename);
                        //$postData['post_image'] = $filename;

                        // Get uploaded file name
                        $postData['post_image'] = $uploader->getUploadedFileName();

                        // Unlink/Remove image from folder
                        //@unlink(Mage::getBaseDir('media') . DS . 'flexibleblog' . DS . $flexibleblogModel->getPostImage());
                    }catch(Exception $e) {
                        echo 'Error Message: '.$e->getMessage();
                    }
                } else {
                    if(isset($postData['post_image']['delete']) && $postData['post_image']['delete'] == 1)
                    {
                        $postData['post_image'] = '';
                        // Unlink/Remove image from folder
                        @unlink(Mage::getBaseDir('media') . DS . 'flexibleblog' . DS . $flexibleblogModel->getPostImage());
                    } else {
                        unset($postData['post_image']);
                    }
                }

                //To save post tags
                if(!empty($postData['post_tags']))
                {
                    Mage::getModel('flexibleblog/tag')->saveTags($postData['post_tags']);
                    $arrTags = explode(',',$postData['post_tags']);
                    if(count($arrTags) > 0)
                    {
                        foreach($arrTags as $tags)
                        {
                            $arrTagIds[] = Mage::getModel('flexibleblog/tag')->getTagIdByName($tags);
                        }
                    }
                    $postData['post_tags'] = implode(',',array_unique($arrTagIds));
                }

                // Save 'Author' data into table
                if(empty($postData['post_author'])){
                    // Check if any admin is logged in or not
                    if (Mage::getSingleton('admin/session')->isLoggedIn()) {
                       $author_name = Mage::getSingleton('admin/session')->getUser()->getName();
                    }
                    $postData['post_author'] = $author_name;
                } else {
                    $postData['post_author'] = $postData['post_author'];
                }

                if(empty($postData['post_publish_date'])){
                    $post_publish_date = now();
                }
                else
                {
                    $post_publish_date = date('Y-m-d h:i:s', strtotime($postData['post_publish_date']));
                }

                // Save 'Post Created Time' data into table
                if($flexibleblogModel->getPostCreatedTime() == '')
                {
                    $created_time = now();
                } else {
                    $created_time = $flexibleblogModel->getPostCreatedTime();
                }
                
                // To check 'Custom Layout Update' XML validation
                $value = $postData['post_custom_layout_update'];
                $post_custom_layout_update = new Varien_Simplexml_Element('<config>' . $value . '</config>');

                // Save data into table
                $flexibleblogModel->setData($postData)
                    ->setPostId($this->getRequest()->getParam('id'))
                    ->setPostCategories($categories)
                    ->setPostPublishDate($post_publish_date)
                    ->setPostCreatedTime($created_time)
                    ->setPostUpdateTime(now())
                    ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Post was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFlexibleblogData(false);

                if ($this->getRequest()->getParam('back')) {
                    $active_tab = $this->getRequest()->getParam('active_tab');
                    $this->_redirect('*/*/edit', array('id' => $flexibleblogModel->getPostId(),'active_tab' => $active_tab));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFlexibleblogData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    // For Delete action
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $flexibleblogModel = Mage::getModel('flexibleblog/post');
                $flexibleblogModel->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Post was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('flexibleblog/adminhtml_post_grid')->toHtml()
        );
    }

    // For Mass Delete action
    public function massDeleteAction() {
        $flexibleblogIds = $this->getRequest()->getParam('flexibleblog');
        if(!is_array($flexibleblogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($flexibleblogIds as $flexibleblogId) {
                    $flexibleblog = Mage::getModel('flexibleblog/post')->load($flexibleblogId);
                    $flexibleblog->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($flexibleblogIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    // For Mass Status change action
    public function massStatusAction()
    {
        $flexibleblogIds = $this->getRequest()->getParam('flexibleblog');
        if(!is_array($flexibleblogIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($flexibleblogIds as $flexibleblogId) {
                    $flexibleblog = Mage::getSingleton('flexibleblog/post')
                        ->load($flexibleblogId)
                        ->setPostStatus($this->getRequest()->getParam('post_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($flexibleblogIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    // For export CSV file action
    public function exportCsvAction() {
        $fileName = 'flexibleblog_post.csv';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_post_grid')
            ->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    // For export XML file action
    public function exportXmlAction() {
        $fileName = 'flexibleblog_post.xml';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_post_grid')
             ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    // For export EXCEL file action
    public function exportExcelAction()
    {
        $fileName = 'flexibleblog_post.xls';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_post_grid')
                ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        return;
    }

    // For role specific permission
    protected function _isAllowed()
    {
        return true;
    }
}