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
 * @copyright  Copyright (c) 2015 Pixlogix Flexibleblog (http://www.pixlogix.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Widgetform block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleblog_Block_WidgetLatestPost extends Pixlogix_Flexibleblog_Block_Post_List implements Mage_Widget_Block_Interface
{
    /**
     * Set Template for Widget Data
     *
     * @return Pixlogix_Flexibleblog_Block_Widgetlatestpost
     */
    public function _construct(){
        parent::_construct();
        if( $this->helper('flexibleblog')->enableFlexibleblog() ):
            $this->setTemplate('flexibleblog/post/widget-latest-post.phtml');
        endif;
    }

    /**
     * Set collection for widget post data
     *
     * @return Pixlogix_Flexibleblog_Block_Widgetlatestpost
     */
    public function getWidgetFormData()
    {
        
        $arrstoreId = array($storeId);
        $collection = Mage::getModel('flexibleblog/post')
                ->getCollection()
                ->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) )
                ->addFieldToFilter('post_status', array('eq' => 1 ));

        $arrCategory = array();

        if($this->getData('category_id')){
            $arrCategory = explode(',', $this->getData('category_id') );
        }

        if(count($arrCategory) > 0){
            $arrPara = array();
            foreach($arrCategory as $category){
                $arrPara[] = array('finset'=>$category);
            }
            $collection->addFieldToFilter('post_categories', $arrPara );
        }

        //Store spacific condition
        if(!Mage::app()->isSingleStoreMode())
        {
            $storeId    = Mage::app()->getStore()->getStoreId();
            $collection = $collection->addFieldToFilter( 'post_store_view',array('finset' => array( $storeId ) ) );
        }
        $postLimit = ($this->getData('post_limit')) ? $this->getData('post_limit') : 5;
        $collection->setPageSize($postLimit);
        $collection->setOrder('post_publish_date','DESC');

        return $collection;
    }
}

        