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

class Pixlogix_Flexibleblog_Model_Category extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleblog/category');
    }

    /**
     * set Category Url
     *
     * @return Pixlogix_Flexibleblog_Model_Category
     */
    public function getCategoryUrl($key)
    {
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        return Mage::getUrl( $url.'/category/'.$key);
    }

    /**
     * set Category Details
     *
     * @return Pixlogix_Flexibleblog_Model_Category
     */
    public function getCategoryDetails($categoryid)
    {
        $collection  = Mage::getModel('flexibleblog/category')
                ->getCollection()
                ->addFieldToFilter('category_id', array('eq' => $categoryid ))
                ->getFirstItem();
        return $collection;
    }

    /**
     * set Category By Url
     *
     * @return Pixlogix_Flexibleblog_Model_Category
     */
    public function getCategoryByUrl($url)
    {
        $collection  = Mage::getModel('flexibleblog/category')
                ->getCollection()
                ->addFieldToFilter('category_url_key', array('eq' => $url ))
                ->getFirstItem();
        return $collection->getCategoryId();
    }

    /**
     * set Category Post
     *
     * @return Pixlogix_Flexibleblog_Model_Category
     */
    public function getCategoryPost($categories)
    {
        $categoryIds = explode(',', $categories);
        $collection = Mage::getModel('flexibleblog/category')->getCollection();
        $collection->addFieldToFilter( 'category_id', array( 'in' => $categoryIds ) );
        $collection->addFieldToFilter( 'category_status', array( 'eq' => 1 ) );
        $countCategory = $collection->count();

        $_categoryList = '';
        $i = 1;
        foreach($collection as $category)
        {
            $_categoryList .= '<li><a href="'.$this->getCategoryUrl($category->getCategoryUrlKey()).'">'.$category->getCategoryTitle().'</a>';
            if($i != $countCategory)
            {
                $_categoryList .= ',';
            }
            $_categoryList .= '</li>';
            $i++;
        }
        $_categoryList = rtrim($_categoryList, ',');
        return $_categoryList;
    }

    public function getStoreCategory($storeId)
    {
        $arrStore = array($storeId);
        $collection = Mage::getModel('flexibleblog/category')->getCollection();
        $collection->addFieldToFilter( 'category_store_view', array( 'finset' => $arrStore ) );
        $collection->addFieldToFilter( 'category_status', array( 'eq' => 1 ) );
        $i=0;
        foreach($collection as $col)
        {
            $storecategory[$i++]['finset'][] = $col->getCategoryId();
        }
        return $storecategory;
    }
	
    /**
     * set Category Layout
     *
     * @return Pixlogix_Flexibleblog_Model_Category
     */
    public function getCategoryLayout($url)
    {
        $collection = Mage::getModel('flexibleblog/category')
                    ->getCollection()
                    ->addFieldToSelect('category_id')
                    ->addFieldToSelect('category_custom_layout')
                    ->addFieldToFilter('category_status', array('eq' => 1 ))
                    ->addFieldToFilter('category_url_key', array('eq' => $url ))
                    ->getFirstItem();
        $layout     = $collection->getCategoryCustomLayout() ? $collection->getCategoryCustomLayout() : Mage::helper('flexibleblog')->getBlogLayout();
        return $layout;
    }

    /**
     * set Category Layout Update
     *
     * @return Pixlogix_Flexibleblog_Model_Category
     */
    public function getCategoryLayoutUpdate($url)
    {
        $collection = Mage::getModel('flexibleblog/category')
                    ->getCollection()
                    ->addFieldToSelect('category_id')
                    ->addFieldToSelect('category_custom_layout_update')
                    ->addFieldToFilter('category_url_key', array('eq' => $url ))
                    ->getFirstItem();
        $layout     = $collection->getCategoryCustomLayoutUpdate();
        return $layout;
    }

    /**
     * set unique category unique url
     *
     * @return Pixlogix_Flexibleblog_Model_Category
     */
    public function makeUniqueCategoryUrl( $title )
    {
        $url        = Mage::getSingleton('catalog/product')->formatUrlKey($title);
        $collection = Mage::getModel('flexibleblog/category')
                        ->getCollection()
                        ->addFieldToFilter('category_url_key', array('eq' => $url ));
        if( $collection->count() )
        {
            $url_key = $this->generateUniqueCategoryUrl($url);
        }
        else
        {
            $url_key = $url;
        }
        return $url_key;
    }

    /**
     * generate unique category unique url key
     *
     * @return Pixlogix_Flexibleblog_Model_Category
     */
    public function generateUniqueCategoryUrl($url)
    {
        $counter= 1;
        $flag   = 0;
        do{
            $url_key    = $url.'_'.$counter;
            $collection = Mage::getModel('flexibleblog/category')
                            ->getCollection()
                            ->addFieldToFilter('category_url_key', array('eq' => $url_key ));
            if( !$collection->count() )
            {
                $flag = 1;
            }
            else
            {
                $counter++;
            }
        }while( $flag == 0 );
        return $url_key;
    }
}