<?php
function berocket_filter_vc_before_init() {
if( class_exists('WPBakeryShortCode') && function_exists('vc_map') ) {
    class WPBakeryShortCode_br_filter_single extends WPBakeryShortCode {
    }
    $query = new WP_Query(array('post_type' => 'br_product_filter', 'nopaging' => true));
    $filter_list = array(__('--Please select filter--', 'BeRocket_AJAX_domain') => '');
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $filter_list[get_the_title() . ' (ID:' . get_the_id() . ')'] = get_the_id();
        }
        wp_reset_postdata();
    }
    vc_map( array(
        'base' => 'br_filter_single',
        'name' => __( 'Single Filter', 'BeRocket_AJAX_domain' ),
        'class' => '',
        'category' => __( 'BeRocket', 'BeRocket_AJAX_domain' ),
        'icon' => 'icon-heart',
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Filter', 'BeRocket_AJAX_domain' ),
                'param_name' => 'filter_id',
                'value'     => $filter_list,
            ),
        ),
    ) );
    class WPBakeryShortCode_br_filters_group extends WPBakeryShortCode {
    }
    $query = new WP_Query(array('post_type' => 'br_filters_group', 'nopaging' => true));
    $filter_list = array(__('--Please select filter--', 'BeRocket_AJAX_domain') => '');
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $filter_list[get_the_title() . ' (ID:' . get_the_id() . ')'] = get_the_id();
        }
        wp_reset_postdata();
    }
    vc_map( array(
        'base' => 'br_filters_group',
        'name' => __( 'Group Filter', 'BeRocket_AJAX_domain' ),
        'class' => '',
        'category' => __( 'BeRocket', 'BeRocket_AJAX_domain' ),
        'icon' => 'icon-heart',
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Filter', 'BeRocket_product_brand_domain' ),
                'param_name' => 'group_id',
                'value'     => $filter_list,
            ),
        ),
    ) );
}
}
add_action('vc_before_init', 'berocket_filter_vc_before_init');
