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
 * Sidebar Archives block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Sidebar_Archives extends Mage_Core_Block_Template
{
    /**
     * set archives
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Archives
     */
    public function getArchives()
    {
        $prefix     = Mage::getConfig()->getTablePrefix();
        $tableName  = '`'.$prefix.'flexibleblog_post`';
        $read       = Mage::getModel('core/resource')->getConnection('core_read');
        $limit      = '';
        if( $count = Mage::helper('flexibleblog')->getArchivesWidgetEntries() && Mage::helper('flexibleblog')->enableArchivesWidget() ){
                $limit = " LIMIT 0,".$count;
        }
        $storeId    = Mage::app()->getStore()->getStoreId();
        if ( !Mage::app()->isSingleStoreMode() ){
            $store_mode = "AND FIND_IN_SET('".$storeId."',post_store_view) > 0";
        }

        $connection = $read->query("SELECT DATE_FORMAT(post_publish_date, '%Y-%M') AS post_archive, COUNT(DISTINCT post_id) AS post_count FROM ".$tableName." WHERE post_publish_date <= '".Mage::getModel('core/date')->gmtDate()."' AND post_status = 1 $store_mode GROUP BY post_archive ORDER BY post_publish_date DESC ".$limit);
        $data       = $connection->fetchAll();
        $yearmonth  = array();
        foreach($data as $values){
            $yearmonth['posts'][] = array( 'date' => $values['post_archive'], 'count' => $values['post_count']);
        }
        return $yearmonth['posts'];
    }

    /**
     * set archive
     *
     * @return Pixlogix_Flexibleblog_Block_Sidebar_Archives
     */
    public function getArchive($value)
    {
        $dateurl    = date('Y/m/', strtotime($value));
        $url        = Mage::helper('flexibleblog')->customBlogUrl();
        $dateurl    = Mage::getUrl().$url.'/archive/'.$dateurl;
        $this->setDateUrl($dateurl);
        $this->setTitle(date('F Y', strtotime($value)));
        return $this;
    }
}