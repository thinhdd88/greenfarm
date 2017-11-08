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
 * Search Controller
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_SearchController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_BLOG_TITLE = 'flexibleblog_options/general/blog_title';

	/**
     * index Action
     *
     * @return search index page
     */
    public function indexAction()
    {
        $isEnable = Mage::helper('flexibleblog')->enableFlexibleblog();
	if( !$isEnable )
        {
            $this->_forward('NoRoute');
            return;
        }
        $this->loadLayout();
        $search_item= $this->getRequest()->getParam('s');
        $search     = $this->__("Search results for: '%s'", $search_item);

        $blog_title = Mage::getStoreConfig(self::XML_PATH_BLOG_TITLE);
        $meta_title = $blog_title ? $blog_title : $this->__('Blog');
        $title      = $search.' - '.$meta_title;
        $head       = $this->getLayout()->getBlock('head');
        if($head)
        {
            $head->setTitle($title);
        }
        $url        = Mage::helper('flexibleblog')->customBlogUrl();
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

            $breadcrumbs->addCrumb('search',
                array(
                    'label' => Mage::helper('flexibleblog')->__($search),
                    'title' => Mage::helper('flexibleblog')->__($search)
                )
            );
        }
        $this->renderLayout();
    }
}