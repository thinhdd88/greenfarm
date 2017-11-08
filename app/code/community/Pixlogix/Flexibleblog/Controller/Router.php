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
 * Flexibleblog Controller Router
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    /**
     * Initialize Controller Router
     *
     * @param Varien_Event_Observer $observer
     */
    public function initControllerRouters($observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();
		$flexibleblog = new Pixlogix_Flexibleblog_Controller_Router();
        $front->addRouter('blog', $flexibleblog);
    }

    /**
     * Validate and Match Flexibleblog Page and modify request
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if(!Mage::isInstalled())
        {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            return;
        }

        $route = Mage::helper('flexibleblog')->customBlogUrl();
        $identifier = trim($request->getPathInfo(), '/');
        $condition = new Varien_Object(array(
            'identifier' => $identifier,
            'continue' => true
        ));

        if ($condition->getRedirectUrl())
        {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($condition->getRedirectUrl())
                ->sendResponse();
            $request->setDispatched(true);
            return true;
        }

        if (!$condition->getContinue()) 
        {
            return false;
        }

        $pos = strpos($identifier,'.html');
        $url='';
        if($pos)
        {
            $url = substr($identifier,0,$pos);
            $url = str_replace($route.'/','',$url);
        }
        $collection = Mage::getModel('flexibleblog/post')->getCollection()
                        ->addFieldToFilter('post_url_key', array('eq' => $url ))
                        ->getFirstItem();

        if($collection->getData()){
            $request->setModuleName('blog');
            $request->setControllerName('index');
            $request->setActionName('view');
            $request->setParam('id', $collection->getPostId());
			$request->setParam('url', $url);
            return true;
        }
        else if( strpos($identifier,'/category/') )
        {
            $category =str_replace($route.'/category/','',$identifier);
            $request->setModuleName('blog');
            $request->setControllerName('category');
            $request->setActionName('index');
            $request->setParam('cat_url', $category);
            $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $identifier
            );
            return true;
        }
        else if( strpos($identifier,'/tag/') )
        {
            $tag =str_replace($route.'/tag/','',$identifier);
            $request->setModuleName('blog');
            $request->setControllerName('tag');
            $request->setActionName('index');
            $request->setParam('tag_url', $tag);
            $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $identifier
            );
            return true;
        }
        else if( strpos($identifier,'/author/') )
        {
            $author =str_replace($route.'/author/','',$identifier);
            $request->setModuleName('blog');
            $request->setControllerName('author');
            $request->setActionName('index');
            $request->setParam('author_url', $author);
            $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $identifier
            );
            return true;
        }
        else if( strpos($identifier,'/archive/') )
        {
            $archive =str_replace($route.'/archive/','',$identifier);
            $arrArchive = explode('/',$archive);
            $request->setModuleName('blog');
            $request->setControllerName('archive');
            $request->setActionName('index');
            $request->setParam('year', $arrArchive[0]);
            $request->setParam('month', $arrArchive[1]);
            $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $identifier.'/'
            );
            return true;
        }
        elseif( ( strpos($identifier,'/search/') || strpos($identifier,'/search') ) )
        {
            $search_item = Mage::app()->getRequest()->getParam('s');
            $search = str_replace($route.'/search/','',$identifier);
            $request->setModuleName('blog');
            $request->setControllerName('search');
            $request->setActionName('index');
            $request->setParam('s', $search_item);
            $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $identifier
            );
            return true;
        }
        elseif( $identifier == $route )
        {
            $request->setModuleName('blog');
            $request->setControllerName('index');
            $request->setActionName('index');
            $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $identifier
            );
            return true;
        }
    }
}