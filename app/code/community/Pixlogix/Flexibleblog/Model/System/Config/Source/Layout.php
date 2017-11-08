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
 * Flexibleblog System_Config_Source_Layout model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Model_System_Config_Source_Layout
{
    /**
     * Retrieve Layout option value
     *
     * @return Pixlogix_Flexibleblog_Model_System_Config_Source_Layout
     */
    public function toOptionArray()
    {
        return array(
            array( 'value' => 'empty', 'label' =>'Empty' ),
            array( 'value' => 'one_column', 'label' => '1 Column' ),
            array( 'value' => 'two_columns_left', 'label' =>'2 columns with left bar' ),
            array( 'value' => 'two_columns_right', 'label' =>'2 columns with right bar' ),
            array( 'value' => 'three_columns', 'label' =>'3 columns' ),
        );
    }
}