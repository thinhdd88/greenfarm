<?php

class TM_Catalog_Model_Resource_Bestsellers_Month_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    public function __construct()
    {
        $this->setModel('adminhtml/report_item');
        parent::__construct(Mage::getResourceModel('sales/report')->init('sales/bestsellers_aggregated_daily'));
    }

    public function _beforeLoad() {
        parent::_beforeLoad();
        $this->getSelect()->reset('columns');
        $this->getSelect()->columns(array(
            'product_id'      => 'product_id',
            'total_qty'     => 'SUM(main_table.qty_ordered)',
        ));
        $this->getSelect()->where('main_table.period <= ?', date('Y-m-d'));
        $this->getSelect()->where('main_table.period >= ?', date('Y-m-d', strtotime("- 1 month")));
        $this->getSelect()->group('main_table.product_id');
        $this->getSelect()->having('total_qty >= 10');
        $this->getSelect()->order('SUM(main_table.qty_ordered) DESC');
        return $this;
    }
}