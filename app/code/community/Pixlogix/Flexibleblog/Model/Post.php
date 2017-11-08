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
 * Flexibleblog Post model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Model_Post extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleblog/post');
    }

    /**
     * set Category Collection
     *
     * @return Pixlogix_Flexibleblog_Model_Post
     */
    public function getCategoryCollection()
    {
        $categoryCollection  = Mage::getModel('flexibleblog/category')->getCollection();
        $categoryCollection->addFieldToFilter('category_status', array('eq'=> 1));
        $categoryCollection->setOrder('category_id','asc');
        $categories = array();
        if($categoryCollection->count() > 0)
        {
            foreach ($categoryCollection as $category)
            {
                $categories[] =array(
                    'value' => $category->getCategoryId(),
                    'label' => $category->getCategoryTitle(),
                );
            }
        }
        else
        {
            $categories = '';
        }
        return $categories;
    }

    /**
     * set Archive Dates
     *
     * @return Pixlogix_Flexibleblog_Model_Post
     */
    public function getArchiveDates($year,$month)
    {
        if( empty($year) || empty($month) )
        {
            return false;
        }
        $lastdate       = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $start_date     = $year.'-'.$month.'-01 00:00:00';
        $date['from']   = date('Y-m-d h:s:i',strtotime($start_date));
        $end_date       = $year.'-'.$month.'-'.$lastdate.' 00:00:00';
        $date['to']     = date('Y-m-d h:s:i',strtotime($end_date));
        return $date;
    }

    /**
     * set Post Layout
     *
     * @return Pixlogix_Flexibleblog_Model_Post
     */
    public function getPostLayout($id)
    {
            $collection = Mage::getModel('flexibleblog/post')
                        ->getCollection()
                        ->addFieldToSelect('post_id')
                        ->addFieldToSelect('post_custom_layout')
                        ->addFieldToFilter('post_status', array('eq' => 1 ))
                        ->addFieldToFilter('post_id', array('eq' => $id ))
                        ->getFirstItem();
            $layout = $collection->getPostCustomLayout() ? $collection->getPostCustomLayout() : Mage::helper('flexibleblog')->getBlogLayout();
            return $layout;
    }

    /**
     * set Post Layout Update
     *
     * @return Pixlogix_Flexibleblog_Model_Post
     */
    public function getPostLayoutUpdate($id)
    {
        $collection = Mage::getModel('flexibleblog/post')
                    ->getCollection()
                    ->addFieldToSelect('post_id')
                    ->addFieldToSelect('post_custom_layout_update')
                    ->addFieldToFilter('post_status', array('eq' => 1 ))
                    ->addFieldToFilter('post_id', array('eq' => $id ))
                    ->getFirstItem();
        $layout     = $collection->getPostCustomLayoutUpdate();
        return $layout;
    }

    /**
     * set Unique post url
     *
     * @return Pixlogix_Flexibleblog_Model_Post
     */
    public function makeUniquePostUrl( $title )
    {
        $url        = Mage::getSingleton('catalog/product')->formatUrlKey($title);
        $collection = Mage::getModel('flexibleblog/post')
                        ->getCollection()
                        ->addFieldToFilter('post_url_key', array('eq' => $url ));
        if($collection->count()){
            $url_key = $this->generateUniquePostUrl($url);
        }else{
            $url_key = $url;
        }
        return $url_key;

    }

    /**
     * set generate unique url key
     *
     * @return Pixlogix_Flexibleblog_Model_Post
     */
    public function generateUniquePostUrl($url)
    {
        $counter = 1;
        $flag    = 0;

        do{
            $url_key    = $url.'_'.$counter;
            $collection = Mage::getModel('flexibleblog/post')
                            ->getCollection()
                            ->addFieldToFilter('post_url_key', array('eq' => $url_key ));
            if( !$collection->count() )
            {
                $flag =1;
            }
            else
            {
                $counter++;
            }
        }while( $flag == 0 );
        return $url_key;
    }
}