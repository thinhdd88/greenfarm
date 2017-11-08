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
 * Flexibleblog Observer model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * To chnage layout
     *
     * @return Pixlogix_Flexibleblog_Model_Observer
     */
    public function addNewLayout($observer)
    {
        $isEnable = Mage::helper('flexibleblog')->enableFlexibleblog();
        if($isEnable)
        {
            $layout         = $observer->getEvent()->getLayout();
            $update         = $layout->getUpdate();
            $helper         = Mage::helper('flexibleblog');
            $pageLayoutType = '';
            $pageLayoutType = $helper->getBlogLayout();

            $layoutupdate   = '';   // Custom Layout Update
            if( Mage::app()->getRequest()->getActionName()=='view' && Mage::app()->getRequest()->getParam('id') !='')
            {
                    $postid         = Mage::app()->getRequest()->getParam('id');
                    $pageLayoutType = Mage::getModel('flexibleblog/post')->getPostLayout($postid);
                    $layoutupdate   = Mage::getModel('flexibleblog/post')->getPostLayoutUpdate($postid);
            }
            else if(Mage::app()->getRequest()->getControllerName()=='category' && Mage::app()->getRequest()->getParam('cat_url') !='')
            {
                    $category_url   = Mage::app()->getRequest()->getParam('cat_url');
                    $pageLayoutType = Mage::getModel('flexibleblog/category')->getCategoryLayout($category_url);
                    $layoutupdate   = Mage::getModel('flexibleblog/category')->getCategoryLayoutUpdate($category_url);
            }
            $rootLayout = $helper->getBlogLayoutXml($pageLayoutType); // Blog Page Layout Update

            $Sidebar= '';  // Sidebar Layout Update
            $both   = 'both';
            if($helper->enableSearchWidget())
            {
                    $searchPosition = $helper->getSearchWidgetPosition();
                    if($searchPosition == $both)
                    {
                        $Sidebar .= '<reference name="left"><block type="flexibleblog/sidebar_search" template="flexibleblog/sidebar/search.phtml" before="-" name="flexibleblog_search"/></reference>'
                                 .'<reference name="right"><block type="flexibleblog/sidebar_search" template="flexibleblog/sidebar/search.phtml" before="-" name="flexibleblog_search"/></reference>';
                    }
                    else
                    {
                        $Sidebar .= '<reference name="'.$searchPosition.'"><block type="flexibleblog/sidebar_search" template="flexibleblog/sidebar/search.phtml" before="-" name="flexibleblog_search"/></reference>';
                    }
            }

            if($helper->enableCategoriesWidget())
            {
                $categoriesPosition = $helper->getCategoriesWidgetPosition();
                if($categoriesPosition == $both)
                {
                    $Sidebar .= '<reference name="left"><block type="flexibleblog/sidebar_categories" template="flexibleblog/sidebar/categories.phtml"/></reference>'
                            . '<reference name="right"><block type="flexibleblog/sidebar_categories" template="flexibleblog/sidebar/categories.phtml"/></reference>';
                }
                else
                {
                    $Sidebar .= '<reference name="'.$categoriesPosition.'"><block type="flexibleblog/sidebar_categories" template="flexibleblog/sidebar/categories.phtml"/></reference>';
                }
            }

            if($helper->enableArchivesWidget())
            {
                $archivesPosition = $helper->getArchivesWidgetPosition();
                if($archivesPosition == $both)
                {
                    $Sidebar .= '<reference name="left"><block type="flexibleblog/sidebar_archives" template="flexibleblog/sidebar/archives.phtml"/></reference>'
                            . '<reference name="right"><block type="flexibleblog/sidebar_archives" template="flexibleblog/sidebar/archives.phtml"/></reference>';
                }
                else
                {
                    $Sidebar .= '<reference name="'.$archivesPosition.'"><block type="flexibleblog/sidebar_archives" template="flexibleblog/sidebar/archives.phtml"/></reference>';
                }
            }

            if($helper->enableRecentPostWidget())
            {
                $recentPostPosition = $helper->getRecentPostWidgetPosition();
                if($recentPostPosition == $both)
                {
                    $Sidebar .= '<reference name="left"><block type="flexibleblog/sidebar_recentposts" template="flexibleblog/sidebar/recentposts.phtml"/></reference>'
                            . '<reference name="right"><block type="flexibleblog/sidebar_recentposts" template="flexibleblog/sidebar/recentposts.phtml"/></reference>';
                }
                else
                {
                    $Sidebar .= '<reference name="'.$recentPostPosition.'"><block type="flexibleblog/sidebar_recentposts" template="flexibleblog/sidebar/recentposts.phtml"/></reference>';
                }
            }

            if($helper->enableRecentCommentsWidget())
            {
                    $recentCommentsPosition = $helper->getRecentCommentsWidgetPosition();
                    if($recentCommentsPosition == $both)
                    {
                        $Sidebar .= '<reference name="left"><block type="flexibleblog/sidebar_recentcomments" template="flexibleblog/sidebar/recentcomments.phtml"/></reference>'
                                . '<reference name="right"><block type="flexibleblog/sidebar_recentcomments" template="flexibleblog/sidebar/recentcomments.phtml"/></reference>';
                    }
                    else
                    {
                        $Sidebar .= '<reference name="'.$recentCommentsPosition.'"><block type="flexibleblog/sidebar_recentcomments" template="flexibleblog/sidebar/recentcomments.phtml"/></reference>';
                    }
            }

            if($helper->enableTagCloudWidget())
            {
                $tagCloudPosition = $helper->getTagCloudWidgetPosition();
                if($tagCloudPosition == $both)
                {
                    $Sidebar .= '<reference name="left"><block type="flexibleblog/sidebar_tagcloud" template="flexibleblog/sidebar/tagcloud.phtml"/></reference>'
                             . '<reference name="right"><block type="flexibleblog/sidebar_tagcloud" template="flexibleblog/sidebar/tagcloud.phtml"/></reference>';
                }
                else
                {
                    $Sidebar .= '<reference name="'.$tagCloudPosition.'"><block type="flexibleblog/sidebar_tagcloud" template="flexibleblog/sidebar/tagcloud.phtml"/></reference>';
                }
            }
            //here is the pieces of layout xml you're going to load (you get it from database)
            $xml = $rootLayout.$Sidebar.$layoutupdate;
            $update->addUpdate($xml);
        }
        return;
    }

    public function rewritesEnable($observer)
    {
        $moduleName     = Mage::app()->getRequest()->getModuleName();
        $controllerName = Mage::app()->getRequest()->getControllerName();
        $actionName     = Mage::app()->getRequest()->getActionName();
        if ($moduleName == 'blog' && $controllerName == 'index' && $actionName == 'index') {
            $node = Mage::getConfig()->getNode('global/blocks/page/rewrite');
        }
    }
}