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
 * Category Controller
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_CategoryController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_BLOG_TITLE = 'flexibleblog_options/general/blog_title';

    /**
     * index Action
     *
     * @return category wise post list page
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
        $category = $this->getRequest()->getParam('cat_url');
        $collection = Mage::getModel('flexibleblog/category')
                ->getCollection()
                ->addFieldToFilter('category_url_key', array( 'eq' => $category))
                ->getFirstItem();

        $category_meta_title= $collection->getCategoryMetaTitle();
        $category_title     = $collection->getCategoryTitle();
        $blog_title         = Mage::getStoreConfig(self::XML_PATH_BLOG_TITLE);
        $meta_title         = $blog_title ? $blog_title : $this->__('Blog');
        $categoryTitle      = $category_meta_title ? $this->__('%s Category Archive', $category_meta_title).' - '.$meta_title : $this->__('%s Category Archive', $category_title).' - '.$meta_title;
        $head               = $this->getLayout()->getBlock('head');
        $url                = Mage::helper('flexibleblog')->customBlogUrl();
        
        $head->setTitle($this->__($categoryTitle));
        if($collection->getCategoryMetaKeyword())
        {
            $head->setKeywords($this->__($collection->getCategoryMetaKeyword()));
        }
        if($collection->getCategoryMetaDescription())
        {
            $head->setDescription($this->__($collection->getCategoryMetaDescription()));
        }

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

            $breadcrumbs->addCrumb('category',
                array(
                    'label' => Mage::helper('flexibleblog')->__($category_title),
                    'title' => Mage::helper('flexibleblog')->__($category_title)
                )
            );
        }

        $this->renderLayout();
    }
}