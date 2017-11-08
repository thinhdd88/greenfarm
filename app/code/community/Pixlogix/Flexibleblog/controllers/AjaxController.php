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
 * Ajax Controller
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_AjaxController extends Mage_Core_Controller_Front_Action
{
    /**
     * tag Action
     *
     * @return Tag action to autosuggest into Post edit page
     */
    public function tagAction()
    {
        $search_item= $this->getRequest()->getParam('term');
        $collection = Mage::getModel('flexibleblog/tag')->getCollection();
        $collection->addFieldToFilter('tag_name' , array( 'like' => '%'.$search_item.'%') );
        $tagArray   = array();
        $tagJSON    = array();
        foreach($collection as $tag){
           $tagArray[] = $tag->getTagName();
        }
        $tagJSON    = json_encode($tagArray);
        echo $tagJSON;
    }
}