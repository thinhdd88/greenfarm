<?php
// app/code/local/Envato/WidgetLinks/Model/Options.php
class Pixlogix_Flexibleblog_Model_Options {
    /**
      * Provide available options as a value/label array
      *
      * @return array
      */
    public function toOptionArray() {
        return array(
            array('value' => 'print', 'label' => 'Print Button'),
            array('value' => 'email', 'label' => 'Inquiry Email Button'),
        );
    }
}