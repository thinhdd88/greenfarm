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
 * Adminhtml_Flexibleblog_Tag Controller
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Adminhtml_Flexibleblog_TagController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('flexibleblog/tags')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Categories'), Mage::helper('adminhtml')->__('Manage Tags'));
        return $this;
    }

    // For Index action
    public function indexAction() {
        $this->_initAction();
        $this->_title($this->__('Flexible Blog'));
        $this->_title($this->__('Tags'));
        $this->_title($this->__('Manage Tags'));
        $this->renderLayout();
    }

    // For Edit action
    public function editAction()
    {
        $flexibleblogId     = $this->getRequest()->getParam('id');
        $flexibleblogModel  = Mage::getModel('flexibleblog/tag')->load($flexibleblogId);
        $this->_title($this->__('Flexible Blog'));
        $this->_title($this->__('Tags'));
        $this->_title($this->__('Manage Tags'));
        $this->_title( $flexibleblogModel->getTagId() ? $this->__('Edit \'%s\' Tag', $flexibleblogModel->getTagName()) : $this->__('Add Tag') );

        if ($flexibleblogModel->getId() || $flexibleblogId == 0) {
            Mage::register('flexibleblog_data', $flexibleblogModel);

            $this->loadLayout();
            $this->_setActiveMenu('flexibleblog/tags');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Tags'), Mage::helper('adminhtml')->__('Manage Tags'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Tag'), Mage::helper('adminhtml')->__('Item Tags'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('flexibleblog/adminhtml_tag_edit'))
                ->_addLeft($this->getLayout()->createBlock('flexibleblog/adminhtml_tag_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('flexibleblog')->__('Tag does not exist'));
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
                $tagData = $this->getRequest()->getPost();
                // Load category data by category id
                $flexibleblogId    = $this->getRequest()->getParam('id');
                $flexibleblogModel = Mage::getModel('flexibleblog/tag')->load($flexibleblogId);

                // Save 'Tag Url Key' data into table
                if(empty($tagData['tag_url_key'])){
                    $tagData['tag_url_key'] = Mage::getSingleton('catalog/product')->formatUrlKey($tagData['tag_name']);
                } else {
                    $tagData['tag_url_key'] = trim(str_replace(' ','-',$tagData['tag_url_key']));
                }

                $_tagCollection = Mage::getModel('flexibleblog/tag')
                                    ->getCollection()
                                    ->addFieldToSelect('*')
                                    ->addFieldToFilter('tag_url_key', array('like' => $tagData['tag_url_key']));

                if($this->getRequest()->getParam('id') > 0)
                {
                    $_tagCollection->addFieldToFilter('tag_id', array('neq' => $this->getRequest()->getParam('id')));
                }

                $tag = $_tagCollection->count();
                if($category > 0)
                {
                    $err_msg = $this->__('Tag Url Key must be unique');
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

                // Save data into table
                $flexibleblogModel->setData($tagData)
                    ->setTagId($this->getRequest()->getParam('id'))
                    ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Tag was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFlexibleblogData(false);

                if ($this->getRequest()->getParam('back')) {
                    $active_tab = $this->getRequest()->getParam('active_tab');
                    $this->_redirect('*/*/edit', array('id' => $flexibleblogModel->getTagId(),'active_tab' => $active_tab));
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
                $flexibleblogModel = Mage::getModel('flexibleblog/tag');

                $flexibleblogModel->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Tag was successfully deleted'));
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
            $this->getLayout()->createBlock('flexibleblog/adminhtml_tag_grid')->toHtml()
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
                    $flexibleblog = Mage::getModel('flexibleblog/tag')->load($flexibleblogId);
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

    // For export CSV file action
    public function exportCsvAction() {
        $fileName = 'flexibleblog_tag.csv';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_tag_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    // For export XML file action
    public function exportXmlAction() {
        $fileName = 'flexibleblog_tag.xml';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_tag_grid')
             ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    // For export EXCEL file action
    public function exportExcelAction()
    {
        $fileName = 'flexibleblog_tag.xls';
        $content  = $this->getLayout()->createBlock('flexibleblog/adminhtml_tag_grid')
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