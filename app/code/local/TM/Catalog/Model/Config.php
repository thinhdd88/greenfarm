<?php

class TM_Catalog_Model_Config extends Mage_Catalog_Model_Config {
    public function getAttributeUsedForSortByArray() {
        $options = parent::getAttributeUsedForSortByArray();
        if (!isset($options['created_at'])) {
            $options['created_at'] = Mage::helper('catalog')->__('Date Created');
        }
        return $options;
    }
}