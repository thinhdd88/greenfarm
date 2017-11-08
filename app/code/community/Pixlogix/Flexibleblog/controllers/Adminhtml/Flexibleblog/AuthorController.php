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
 * Adminhtml_Flexibleblog_Author Controller
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Adminhtml_Flexibleblog_AuthorController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('flexibleblog/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Author'), Mage::helper('adminhtml')->__('Manage Author'));
        return $this;
    }

    // For Index action
    public function indexAction() {
        $this->_initAction();
        $this->_title($this->__('Flexible Blog'));
        $this->_title($this->__('Author'));
        $this->_title($this->__('Manage Author'));
        $this->renderLayout();
    }

    // For Edit action
    public function editAction()
    {
        $flexibleblogId     = $this->getRequest()->getParam('id');
        $flexibleblogModel  = Mage::getModel('flexibleblog/author')->load($flexibleblogId);
        $this->_title($this->__('Flexible Blog'));
        $this->_title($this->__('Author'));
        $this->_title($this->__('Manage Author'));
        $this->_title( $flexibleblogModel->getAuthorId() ? $this->__('Edit \'%s\' Author', $flexibleblogModel->getAuthorName()) : $this->__('Add Author') );

        if ($flexibleblogModel->getId() || $flexibleblogId == 0) {
            Mage::register('flexibleblog_data', $flexibleblogModel);

            $this->loadLayout();
            $this->_setActiveMenu('flexibleblog/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Author'), Mage::helper('adminhtml')->__('Manage Author'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Author'), Mage::helper('adminhtml')->__('Item Author'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('flexibleblog/adminhtml_author_edit'))
                ->_addLeft($this->getLayout()->createBlock('flexibleblog/adminhtml_author_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleblog')->__('Author does not exist'));
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
                $authorData = $this->getRequest()->getPost();
                // Load author data by author id
                $flexibleblogId    = $this->getRequest()->getParam('id');
                $flexibleblogModel = Mage::getModel('flexibleblog/author')->load($flexibleblogId);

                // Save 'Author Url Key' data into table
                if(empty($authorData['author_url_key'])){
                    $authorData['author_url_key'] = Mage::getSingleton('catalog/product')->formatUrlKey($authorData['author_name']);
                } else {
                    $authorData['author_url_key'] = trim(str_replace(' ','-', $authorData['author_url_key']));
                }

                $_authorCollection = Mage::getModel('flexibleblog/author')
                                    ->getCollection()
                                    ->addFieldToSelect('*')
                                    ->addFieldToFilter('author_url_key', array('like' => $authorData['author_url_key']));
                if($this->getRequest()->getParam('id') > 0)
                {
                    $_authorCollection->addFieldToFilter('author_id', array('neq' => $this->getRequest()->getParam('id')));
                }

                $author = $_authorCollection->count();
                if($author > 0)
                {
                    $err_msg = $this->__('Author Url Key must be unique');
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

                // Save 'Author Created Time' data into table
                if($flexibleblogModel->getAuthorCreatedTime() == '')
                {
                    $created_time = now();
                } else {
                    $created_time = $flexibleblogModel->getAuthorCreatedTime();
                }
				
		// Save 'Image' data into table
                if(isset($_FILES['author_avatar']['name']) && (file_exists($_FILES['author_avatar']['tmp_name'])) ) {
                    try {
                        $uploader = new Varien_File_Uploader('author_avatar');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
                        $uploader->setAllowCreateFolders(true);
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);

                        $path = Mage::getBaseDir('media') . DS . 'flexibleblog' . DS;
                        $filename = $_FILES['author_avatar']['name'];

                        $uploader->save($path, $filename);
                        //$postData['post_image'] = $filename;

                        // Get uploaded file name
                        $authorData['author_avatar'] = $uploader->getUploadedFileName();

                        // Unlink/Remove image from folder
                        //@unlink(Mage::getBaseDir('media') . DS . 'flexibleblog' . DS . $flexibleblogModel->getPostImage());
                    }catch(Exception $e) {
                        echo 'Error Message: '.$e->getMessage();
                    }
                } else {
                    if(isset($authorData['author_avatar']['delete']) && $authorData['author_avatar']['delete'] == 1)
                    {
                        $authorData['author_avatar'] = '';
                        // Unlink/Remove image from folder
                        @unlink(Mage::getBaseDir('media') . DS . 'flexibleblog' . DS . $flexibleblogModel->getAuthorAvatar());
                    } else {
                        unset($authorData['author_avatar']);
                    }
                }

                // Save data into table
                $flexibleblogModel->setData($authorData)
                    ->setAuthorId($this->getRequest()->getParam('id'))
                    ->setAuthorCreatedTime($created_time)
                    ->setAuthorUpdateTime(now())
                    ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Author was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFlexibleblogData(false);

                if ($this->getRequest()->getParam('back')) {
                    $active_tab = $this->getRequest()->getParam('active_tab');
                    $this->_redirect('*/*/edit', array('id' => $flexibleblogModel->getAuthorId(),'active_tab' => $active_tab));
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
                $flexibleblogModel = Mage::getModel('flexibleblog/author');
				if($this->getRequest()->getParam('id') != 1)
				{
	                $flexibleblogModel->setId($this->getRequest()->getParam('id'))->delete();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Author was successfully deleted'));
				}
                else
				{
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Default admin author can not remove'));
				}
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
            $this->getLayout()->createBlock('flexibleblog/adminhtml_author_grid')->toHtml()
        );
    }

    // For Mass Delete action
    public function massDeleteAction() {
        $flexibleblogIds = $this->getRequest()->getParam('flexibleblog');
        if(!is_array($flexibleblogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                $counter=0;
                foreach ($flexibleblogIds as $flexibleblogId) {
                    if($flexibleblogId!=1)
                    {
                        $flexibleblog = Mage::getModel('flexibleblog/author')->load($flexibleblogId);
                        $flexibleblog->delete();
                    }
                    else
                    {
                        $counter++;
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Default admin author can not remove'));
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($flexibleblogIds)-1
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    // For export CSV file action
    public function exportCsvAction() {
        $fileName = 'flexibleblog_author.csv';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_author_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    // For export XML file action
    public function exportXmlAction() {
        $fileName = 'flexibleblog_author.xml';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_author_grid')
             ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    // For export EXCEL file action
    public function exportExcelAction()
    {
        $fileName = 'flexibleblog_author.xls';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_author_grid')
                ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, author-check=0, pre-check=0', true);
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