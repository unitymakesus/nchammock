<?php
class BeRocket_AAPF_compat_woocommerce_variation_image {
    function __construct() {
        add_filter('berocket_filters_query_already_filtered', array(__CLASS__, 'out_of_stock_variable'), 10, 3);
        add_filter('woocommerce_product_get_image', array(__CLASS__, 'replace_variation_image'), 10, 5);
    }
    public static function replace_variation_image($image, $product, $size, $attr, $placeholder) {
        global $berocket_variable_to_variation_list;
        if( is_array($berocket_variable_to_variation_list) && array_key_exists($product->get_id(), $berocket_variable_to_variation_list) ) {
            $parent_product = $berocket_variable_to_variation_list[$product->get_id()];
            $parent_product = wc_get_product($parent_product);
            $image = $parent_product->get_image($size, $attr, $placeholder);
        }
        return $image;
    }
    public static function out_of_stock_variable($query, $terms, $limits) {
        global $wpdb;
        $outofstock = get_term_by( 'slug', 'outofstock', 'product_visibility' );
        $current_terms = array();
        $current_attributes = array();
        if( is_array($terms) && count($terms) ) {
            foreach($terms as $term) {
                if( substr( $term[0], 0, 3 ) == 'pa_' ) {
                    $current_attributes[] = 'attribute_' . $term[0];
                    $current_terms[] = $term[3];
                }
            }
        }
        if( is_array($limits) && count($limits) ) {
            foreach($limits as $attr => $term_ids) {
                if( substr( $attr, 0, 3 ) == 'pa_' ) {
                    $current_attributes[] = 'attribute_' . $attr;
                    foreach($term_ids as $term_id) {
                        $term = get_term($term_id);
                        if( ! empty($term) && ! is_wp_error($term) ) {
                            $current_terms[] = $term->slug;
                        }
                    }
                }
            }
        }
        $current_terms = array_unique($current_terms);
        $current_attributes = array_unique($current_attributes);
        $current_terms = implode('", "', $current_terms);
        $current_attributes = implode('", "', $current_attributes);
        $variable_products = $wpdb->get_results( sprintf( '
            SELECT filtered_post.var_id, filtered_post.ID FROM
                (SELECT %1$s.id as var_id, %1$s.post_parent as ID, COUNT(%1$s.id) as meta_count FROM %1$s
                INNER JOIN %2$s AS pf1 ON (%1$s.ID = pf1.post_id)
                WHERE %1$s.post_type = "product_variation"
                AND pf1.meta_key IN ("%3$s") AND pf1.meta_value IN ("%4$s")
                GROUP BY %1$s.id) as filtered_post
                INNER JOIN (SELECT ID, MAX(meta_count) as max_meta_count FROM (
                    SELECT %1$s.id as var_id, %1$s.post_parent as ID, COUNT(%1$s.id) as meta_count FROM %1$s
                    INNER JOIN %2$s AS pf1 ON (%1$s.ID = pf1.post_id)
                    WHERE %1$s.post_type = "product_variation"
                    AND pf1.meta_key IN ("%3$s") AND pf1.meta_value IN ("%4$s")
                    GROUP BY %1$s.id
                ) as max_filtered_post GROUP BY ID
            ) as max_filtered_post ON max_filtered_post.ID = filtered_post.ID AND max_filtered_post.max_meta_count = filtered_post.meta_count
        ', $wpdb->posts, $wpdb->postmeta, $current_attributes, $current_terms ), ARRAY_N );
        global $berocket_variable_to_variation_list;
        $berocket_variable_to_variation_list = array();
        if( is_array($variable_products) ) {
            foreach($variable_products as $variable_product) {
                if( is_array($variable_product) && count($variable_product) >= 2 ) {
                    $berocket_variable_to_variation_list[$variable_product[1]] = $variable_product[0];
                }
            }
        }
        return $query;
    }
}
new BeRocket_AAPF_compat_woocommerce_variation_image();
