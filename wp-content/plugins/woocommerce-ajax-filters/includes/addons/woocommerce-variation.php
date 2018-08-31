<?php
class BeRocket_AAPF_compat_woocommerce_variation {
    function __construct() {
        add_filter('berocket_filters_query_already_filtered', array(__CLASS__, 'query_already_filtered'), 10, 3);
        add_filter('berocket_add_out_of_stock_variable', array(__CLASS__, 'out_of_stock_variable'), 10, 3);
    }
    public static function query_already_filtered($query, $terms, $limits) {
        $get_queried_object = $query->get_queried_object();
        if( is_a($get_queried_object, 'WP_Term') && strpos($get_queried_object->taxonomy, 'pa_') !== FALSE ) {
            if( ! is_array($terms) ) {
                $terms = array();
            }
            $terms[] = array(
                $get_queried_object->taxonomy,
                $get_queried_object->term_id,
                'OR',
                $get_queried_object->slug,
                'attribute'
            );
        }
        $post_not_in = self::out_of_stock_variable(array(), $terms, $limits);
        if( is_array($post_not_in) && count($post_not_in) ) {
            $post__not_in = $query->get('post__not_in');
            $post__not_in = array_merge($post__not_in, $post_not_in);
            $query->set('post__not_in', $post__not_in);
        }
        return $query;
    }
    public static function out_of_stock_variable($input, $terms, $limits) {
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
        $out_of_stock_variable = $wpdb->get_results( sprintf( '
            SELECT filtered_post.id, filtered_post.out_of_stock, COUNT(filtered_post.ID) as post_count FROM (SELECT filtered_post.*, max_filtered_post.max_meta_count, COALESCE(stock_table.out_of_stock, "0") as out_of_stock FROM (
                SELECT %1$s.id as var_id, %1$s.post_parent as ID, COUNT(%1$s.id) as meta_count FROM %1$s
                INNER JOIN %2$s AS pf1 ON (%1$s.ID = pf1.post_id)
                WHERE %1$s.post_type = "product_variation"
                AND pf1.meta_key IN ("%4$s") AND pf1.meta_value IN ("%5$s")
                GROUP BY %1$s.id
            ) as filtered_post
            INNER JOIN (SELECT ID, MAX(meta_count) as max_meta_count FROM (
                    SELECT %1$s.id as var_id, %1$s.post_parent as ID, COUNT(%1$s.id) as meta_count FROM %1$s
                    INNER JOIN %2$s AS pf1 ON (%1$s.ID = pf1.post_id)
                    WHERE %1$s.post_type = "product_variation"
                    AND pf1.meta_key IN ("%4$s") AND pf1.meta_value IN ("%5$s")
                    GROUP BY %1$s.id
                ) as max_filtered_post GROUP BY ID
            ) as max_filtered_post ON max_filtered_post.ID = filtered_post.ID AND max_filtered_post.max_meta_count = filtered_post.meta_count
            LEFT JOIN ( 
                SELECT %1$s.id as id, "1" as out_of_stock 
                FROM %1$s 
                WHERE %1$s.id IN (SELECT object_id FROM %3$s WHERE term_taxonomy_id IN ( '.$outofstock->term_id.' ) ) ) as stock_table 
                ON filtered_post.var_id = stock_table.id
            GROUP BY filtered_post.ID, out_of_stock) as filtered_post GROUP BY filtered_post.ID HAVING post_count = 1 AND out_of_stock = 1
        ', $wpdb->posts, $wpdb->postmeta, $wpdb->term_relationships, $current_attributes, $current_terms ), ARRAY_N );
        $post_not_in = array();
        if( is_array($out_of_stock_variable) && count($out_of_stock_variable) ) {
            foreach($out_of_stock_variable as $out_of_stock) {
                $post_not_in[] = $out_of_stock[0];
            }
        }
        return $post_not_in;
    }
}
new BeRocket_AAPF_compat_woocommerce_variation();
