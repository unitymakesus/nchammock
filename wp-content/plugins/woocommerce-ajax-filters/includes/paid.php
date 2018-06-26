<?php
class BeRocket_AAPF_paid {
    function __construct() {
        add_filter( 'berocket_filter_filter_type_array', array( $this, 'filter_type_array' ) );
    }
    
    public function filter_type_array($filter_type_array) {
        $filter_type_array = berocket_insert_to_array($filter_type_array, 'product_cat', array('custom_product_cat' => array(
            'name' => __('Product Category', 'BeRocket_AJAX_domain'),
            'sameas' => 'custom_taxonomy',
            'attribute' => 'product_cat',
        )), true);
        return $filter_type_array;
    }
}
new BeRocket_AAPF_paid();
