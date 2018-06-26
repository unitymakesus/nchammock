<?php
if( ! function_exists( 'br_get_current_language_code' ) ){
    /**
     * Permalink block in settings
     *
     */
    function br_get_current_language_code() {
        $language = '';
        if( function_exists( 'qtranxf_getLanguage' ) ) {
            $language = qtranxf_getLanguage();
        }
        if( defined('ICL_LANGUAGE_CODE') ) {
            $language = ICL_LANGUAGE_CODE;
        }
        return $language;
    }
}
if( ! function_exists( 'berocket_wpml_attribute_translate' ) ){
    function berocket_wpml_attribute_translate($slug) {
        $wpml_slug = apply_filters( 'wpml_translate_single_string', $slug, 'WordPress', sprintf( 'URL attribute slug: %s', $slug ) );
        if( $wpml_slug != $slug ) {
            $translations = get_option('berocket_wpml_attribute_slug_untranslate');
            if( ! is_array($translations) ) {
                $translations = array();
            }
            $translations[$wpml_slug] = $slug;
            update_option('berocket_wpml_attribute_slug_untranslate', $translations);
        }
        return $wpml_slug;
    }
}
if( ! function_exists( 'berocket_wpml_attribute_untranslate' ) ){
    function berocket_wpml_attribute_untranslate($slug) {
        $translations = get_option('berocket_wpml_attribute_slug_untranslate');
        if( is_array($translations) && ! empty($translations[$slug]) ) {
            $slug = $translations[$slug];
        }
        return $slug;
    }
}
if( ! function_exists( 'br_get_value_from_array' ) ){
    function br_get_value_from_array(&$arr, $index, $default = '') {
        if( ! isset($arr) || ! is_array($arr) ) {
            return $default;
        }
        $array = $arr;
        if( ! is_array($index) ) {
            $index = array($index);
        }
        foreach($index as $i) {
            if( ! isset($array[$i]) ) {
                return $default;
            } else {
                $array = $array[$i];
            }
        }
        return $array;
    }
}
if( ! function_exists( 'berocket_isset' ) ){
    function berocket_isset(&$var, $property_name = false, $default = null) {
        if( $property_name === false ) {
            return ( isset($var) ? $var : $default );
        } else {
            return ( isset($var) ? ( property_exists($var, $property_name) ? $var->$property_name : $default ) : $default );
        }
    }
}
if( ! function_exists( 'br_permalink_input_section_echo' ) ){
    /**
     * Permalink block in settings
     *
     */
    function br_permalink_input_section_echo() {
        echo '<div>'.__('BeRocket AJAX Product Filters Nice URL settings', 'BeRocket_AJAX_domain').'</div>';
        BeRocket_AAPF::br_get_template_part( 'permalink_option' );
    }
}
if( ! function_exists( 'br_is_plugin_active' ) ) {
    /**
     * Public function to add to plugin settings buttons to upload or select icons
     *
     * @var $plugin_name - should be class name without BeRocket_ part
     *
     * @return boolean
     */
    function br_is_plugin_active( $plugin_name, $version = '1.0.0.0', $version_end = '9.9.9.9' ) {
        if ( defined( "BeRocket_" . $plugin_name . "_version" ) &&
             constant( "BeRocket_" . $plugin_name . "_version" ) >= $version &&
             constant( "BeRocket_" . $plugin_name . "_version" ) <= $version_end
        ) {
            return true;
        }

        return false;
    }
}
if( ! function_exists( 'br_get_woocommerce_version' ) ){
    /**
     * Public function to get WooCommerce version
     *
     * @return float|NULL
     */
    function br_get_woocommerce_version() {
        if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';

        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];
        } else {
            return NULL;
        }
    }
}
if( ! function_exists( 'br_woocommerce_version_check' ) ){
    function br_woocommerce_version_check( $version = '2.7' ) {
        $wc_version = br_get_woocommerce_version();
        if( $wc_version !== NULL ) {
            if( version_compare( $wc_version, $version, ">=" ) ) {
                return true;
            }
        }
        return false;
    }
}

if( ! function_exists( 'br_get_template_part' ) ){
    /**
     * Public function to get plugin's template
     *
     * @param string $name Template name to search for
     *
     * @return void
     */
    function br_get_template_part( $name = '' ){
        BeRocket_AAPF::br_get_template_part( $name );
    }
}

if( ! function_exists( 'br_is_filtered' ) ){
    /**
     * Public function to check if filter set
     *
     * @param bool $filters is filter set
     * @param bool $limits is limit set
     * @param bool $price is price set
     *
     * @return bool
     */
    function br_is_filtered( $filters = true, $limits = true, $price = true, $search = true ){
        $filtered = false;
        if ( $filters ) {
            $filtered = $filtered || ( isset( $_POST['terms'] ) && is_array( $_POST['terms'] ) && count( $_POST['terms'] ) > 0 );
        }
        if ( $limits ) {
            $filtered = $filtered || ( isset( $_POST['limits'] ) && is_array( $_POST['limits'] ) && count( $_POST['limits'] ) > 0 );
        }
        if ( $price ) {
            $filtered = $filtered || ( isset( $_POST['price'] ) && is_array( $_POST['price'] ) && count( $_POST['price'] ) > 0 );
        }
        if ( $search ) {
            $filtered = $filtered || ! empty( $_GET['s'] );
        }
        return $filtered;
    }
}

if( ! function_exists( 'br_get_cache' ) ){
    /**
     * Get cached object
     *
     * @param string $key Key to find value
     * @param string $group Group with keys
     * @param string $cache_type Type of cache 'wordpress' or 'persistent'
     *
     * @return mixed
     */
    function br_get_cache( $key, $group, $cache_type ){
        $language = br_get_current_language_code();
        $return = false;
        $group = $group.$language;
        if ( $cache_type == 'wordpress' ) {
            $return = get_site_transient( md5($group.$key) );
        } elseif ( $cache_type == 'persistent' ) {
            $return = wp_cache_get( $key, $group );
        }
        return $return;
    }
}

if( ! function_exists( 'br_set_cache' ) ){
    /**
     * Save object to cache
     *
     * @param string $key Key to save value
     * @param mixed $value Value to save
     * @param string $group Group with keys
     * @param int $expire Expiration time in seconds
     * @param string $cache_type Type of cache 'wordpress' or 'persistent'
     *
     * @return void
     */
    function br_set_cache( $key, $value, $group, $expire, $cache_type ){
        $language = br_get_current_language_code();
        $group = $group.$language;
        if ( $cache_type == 'wordpress' ) {
            set_site_transient( md5($group.$key), $value, $expire );
        } elseif ( $cache_type == 'persistent' ) {
            wp_cache_add( $key, $value, $group, $expire );
        }
    }
}

if ( ! function_exists( 'br_is_term_selected' ) ) {
    /**
     * Public function to check if term is selected
     *
     * @param object $term - Term to check for
     * @param boolean $checked - if TRUE return ' checked="checked"'
     * @param boolean $child_parent - if TRUE search child selected
     * @param integer $depth - current term depth in hierarchy
     *
     * @return string ' selected="selected"' if selected, empty string '' if not selected
     */
    function br_is_term_selected( $term, $checked = FALSE, $child_parent = FALSE, $depth = 0 ) {
        $term_taxonomy = $term->taxonomy;
        if( $term_taxonomy == '_rating' ) {
            $term_taxonomy = 'product_visibility';
        }
        $is_checked = false;

        if ( ! empty($_POST['terms']) and ! empty($term) and is_object( $term ) ) {
            if ( $child_parent ) {
                $selected_terms = br_get_selected_term( $term_taxonomy );
                foreach( $selected_terms as $selected_term ) {
                    $ancestors = get_ancestors( $selected_term, $term_taxonomy );
                    if( count( $ancestors ) > $depth ) {
                        if ( $ancestors[count($ancestors) - ( $depth + 1 )] == $term->term_id ) {
                            $is_checked = true;
                        }
                    }
                }
            }
            foreach ( $_POST['terms'] as $p_term ) {
                if ( (  ! empty($p_term[0]) and ! empty($p_term[1]) and $p_term[0] == $term_taxonomy and $term->term_id == $p_term[1] ) or $is_checked ) {
                    if($checked) return ' checked="checked"';
                    else return ' selected="selected"';
                }
            }
        }
        if ( ! empty($_POST['add_terms']) and ! empty($term) and is_object( $term ) ) {
            foreach ( $_POST['add_terms'] as $p_term ) {
                if ( ( ! empty($p_term[0]) and ! empty($p_term[1]) and $p_term[0] == $term_taxonomy and $term->term_id == $p_term[1] ) or $is_checked ) {
                    if($checked) return ' checked="checked"';
                    else return ' selected="selected"';
                }
            }
        }
        if ( ! empty($_POST['price_ranges']) and ! empty($term) and is_object( $term ) and $term_taxonomy == 'price' ) {
            $is_checked = false;
            foreach ( $_POST['price_ranges'] as $p_term ) {
                if ( ( $term->term_id == $p_term ) ) {
                    if($checked) return ' checked="checked"';
                    else return ' selected="selected"';
                }
            }
        }
        return '';
    }
}

if ( ! function_exists( 'br_get_selected_term' ) ) {
    /**
     * Public function to get all selected terms in taxonomy
     *
     * @param object $taxonomy - Taxonomy name
     *
     * @return array selected terms
     */
    function br_get_selected_term( $taxonomy ) {
        $term = array();
        if ( ! empty($_POST['terms']) ) {
            foreach ( $_POST['terms'] as $p_term ) {
                if ( ! empty($p_term[0]) and $p_term[0] == $taxonomy ) {
                    $term[] = ( empty($p_term[1]) ? '' : $p_term[1] );
                }
            }
        }
        if ( ! empty($_POST['limits']) ) {
            foreach ( $_POST['limits'] as $p_term ) {
                if ( ! empty($p_term[0]) && $p_term[0] == $taxonomy ) {
                    if ( ! is_numeric( $p_term[1] ) || ! is_numeric( $p_term[2] ) ) {
                        $all_terms_name = array();
                        $terms          = get_terms( $p_term[0] );
                        $is_numeric = true;
                        foreach ( $terms as $term_ar ) {
                            array_push( $all_terms_name, $term_ar->name );
                            if( ! is_numeric( substr( $term_ar->name[0], 0, 1 ) ) ) {
                                $is_numeric = false;
                            }
                        }
                        if( $is_numeric ) {
                            sort( $all_terms_name, SORT_NUMERIC );
                        } else {
                            sort( $all_terms_name );
                        }
                        $start_terms    = array_search( $p_term[1], $all_terms_name );
                        $end_terms      = array_search( $p_term[2], $all_terms_name );
                        $all_terms_name = array_slice( $all_terms_name, $start_terms, ( $end_terms - $start_terms + 1 ) );
                        foreach ( $all_terms_name as $term_name ) {
                            $term_id = get_term_by ( 'name', $term_name, $taxonomy );
                            $term[] = $term_id->term_id;
                        }
                    }
                }
            }
        }
        return $term;
    }
}

if( ! function_exists( 'br_aapf_get_attributes' ) ) {
    /**
     * Get all possible woocommerce attribute taxonomies
     *
     * @return mixed|void
     */
    function br_aapf_get_attributes() {
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $attributes           = array();

        if ( $attribute_taxonomies ) {
            foreach ( $attribute_taxonomies as $tax ) {
                $attributes[ wc_attribute_taxonomy_name( $tax->attribute_name ) ] = $tax->attribute_label;
            }
        }

        return apply_filters( 'berocket_aapf_get_attributes', $attributes );
    }
}

if( ! function_exists( 'br_aapf_parse_order_by' ) ) {
    /**
     * br_aapf_parse_order_by - parsing order by data and saving to $args array that was passed into
     *
     * @param $args
     */
    function br_aapf_parse_order_by( &$args ) {
        $orderby = $_GET['orderby'] = $_POST['orderby'];
        $order   = "ASC";
        if ( preg_match( "/-/", ( empty($orderby) ? '' : $orderby ) ) ) {
            list( $orderby, $order ) = explode( "-", $orderby );
        }
        $order = strtoupper($order);

        // needed for woocommerce sorting funtionality
        if ( ! empty($orderby) and ! empty($order) ) {

            // Get ordering from query string unless defined
            $orderby = strtolower( $orderby );
            $order   = strtoupper( $order );

            // default - menu_order
            $args['orderby']  = 'menu_order title';
            $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';

            switch ( strtolower($orderby) ) {
                case 'rand' :
                    $args['orderby']  = 'rand';
                    break;
                case 'date' :
                    $args['orderby']  = 'date';
                    $args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
                    break;
                case 'price' :
                    $args['orderby']  = 'meta_value_num';
                    $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                    $args['meta_key'] = '_price';
                    break;
                case 'popularity' :
                    $args['meta_key'] = 'total_sales';

                    // Sorting handled later though a hook
                    add_filter( 'posts_clauses', array( 'BeRocket_AAPF', 'order_by_popularity_post_clauses' ) );
                    break;
                case 'rating' :
                    // Sorting handled later though a hook
                    add_filter( 'posts_clauses', array( 'BeRocket_AAPF', 'order_by_rating_post_clauses' ) );
                    break;
                case 'title' :
                    $args['orderby']  = 'title';
                    $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                    break;
            }
        }
    }
}

if( ! function_exists( 'br_aapf_args_parser' ) ){
    /**
     * br_aapf_args_parser - extend $args based on passed filters
     *
     * @param array $args
     *
     * @return array
     */
    function br_aapf_args_parser( $args = array() ) {
        $br_options = BeRocket_AAPF::get_aapf_option();
        $attributes_terms = $tax_query = array();
        $tags             = '';
        $attributes       = apply_filters( 'berocket_aapf_listener_get_attributes', br_aapf_get_attributes() );
        $product_visibility = get_terms('product_visibility');
        $attributes['product_visibility'] = array();
        foreach($product_visibility as $product_visibility_term) {
            $attributes['product_visibility'][] = $product_visibility_term->term_id;
        }

        if ( ! empty($attributes) ) {
            foreach ( $attributes as $k => $v ) {
                $terms = get_terms( array( $k ), $args = array( 'orderby' => 'name', 'order' => 'ASC' ) );
                if ( $terms ) {
                    foreach ( $terms as $term ) {
                        $attributes_terms[ $k ][ $term->term_id ] = $term->term_id;
                    }
                }
            }
        }

        if ( ! empty($_POST['terms']) ) {
            foreach ( $_POST['terms'] as $post_key => $t ) {
                if ( $t[4] == 'tag' && ! $br_options['tags_custom'] ) {
                    if ( $tags ) {
                        if ( $t[2] == 'OR' ) {
                            $tags .= ',';
                        } else {
                            $tags .= '+';
                        }
                    }
                    $tags .= $t[3];
                } elseif ( $t[4] == 'attribute' && $t[0] != 'product_cat' && $t[0] != 'product_tag' ) {
                    $taxonomies[ $t[0] ][]        = ( empty($attributes_terms[ $t[0] ][ $t[1] ]) ? '' : $attributes_terms[ $t[0] ][ $t[1] ] );
                    $taxonomies_operator[ $t[0] ] = $t[2];
                } elseif ( taxonomy_exists( $t[0] ) ) {
                    $taxonomies[ $t[0] ][]        = $t[1];
                    $taxonomies_operator[ $t[0] ] = $t[2];
                }
            }
        }

        $taxonomies          = apply_filters( 'berocket_aapf_listener_taxonomies', ( empty($taxonomies) ? '' : $taxonomies ) );
        $taxonomies_operator = apply_filters( 'berocket_aapf_listener_taxonomies_operator', ( empty($taxonomies_operator) ? '' : $taxonomies_operator ) );

        if ( ! empty($taxonomies) ) {
            $tax_query['relation'] = 'AND';
            if ( $taxonomies ) {
                foreach ( $taxonomies as $k => $v ) {
                    if ( $taxonomies_operator[ $k ] == 'AND' ) {
                        $op = 'AND';
                    } else {
                        $op = 'OR';
                    }

                    $fields = 'id';
                    $current_tax_query = array();
                    $current_tax_query['relation'] = $op;
                    $include_children = false;
                    if( $k == 'product_cat' ) {
                        $include_children = true;
                    }
                    foreach($v as $v_i) {
                        $current_tax_query[] = apply_filters('berocket_aapf_tax_query_attribute', array(
                            'taxonomy'          => $k,
                            'field'             => $fields,
                            'terms'             => $v_i,
                            'operator'          => 'IN',
                            'include_children'  => $include_children,
                            'is_berocket'       => true
                        ));
                    }
                    $tax_query[] = $current_tax_query;
                }
            }
        }

        if ( ! empty($tags) ) {
            $args['product_tag'] = $tags;
        }

        if ( ! empty($_POST['product_cat']) and $_POST['product_cat'] != '-1' ) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => strip_tags( $_POST['product_cat'] ),
                'operator' => 'IN',
                'is_berocket'=> true
            );
        }

        $args['tax_query'] = $tax_query;
        $args['post_type'] = 'product';

        if ( ! empty($_POST['orderby']) ) {
            br_aapf_parse_order_by( $args );
        }

        return $args;
    }
}

if( ! function_exists( 'br_aapf_args_converter' ) ) {
    /**
     * convert args-url to normal filters
     */
    function br_aapf_args_converter($query) {
        $br_options = BeRocket_AAPF::get_aapf_option();
        $option_permalink = get_option( 'berocket_permalink_option' );
        if( ! empty($br_options['nice_urls']) && $query->get( $option_permalink['variable'], '' ) ) {
            $values_split = $option_permalink['value'];
            $values_split = explode( 'values', $values_split );
            $regex = '#(.+?)'.preg_quote($values_split[0]).'(.+?)'.preg_quote($values_split[1].$option_permalink['split']).'#';
            $filters = $query->get( $option_permalink['variable'], '' );
            $filters = str_replace('+', '%2B', $filters);
            $filters = urldecode( $filters );
            if( preg_match('#\/page\/(\d+)#', $filters, $page_match) ) {
                $filters = preg_replace( '#\/page\/(\d+)#', '', $filters );
                $_GET['paged'] = $page_match[1];
                set_query_var( 'paged', $page_match[1] );
            }
            $filters = $filters.$option_permalink['split'];
            $query_string = '';
            $matches = array();
            preg_match_all( $regex, $filters, $matches );
            for($i = 0; $i < count($matches[1]); $i++ ) {
                if( strlen($query_string) > 0 ) {
                    $query_string .= '|';
                }
                $query_string .= $matches[1][$i].'['.$matches[2][$i].']';
            }
            $_GET['filters'] = $query_string;
        }
        if( empty($br_options['seo_uri_decode']) ) {
            $_GET['filters'] = urlencode($_GET['filters']);
            $_GET['filters'] = str_replace('+', urlencode('+'), $_GET['filters']);
            $_GET['filters'] = urldecode($_GET['filters']);
        }
        $_POST['terms'] = array();
        $_POST['limits'] = array();
        $_POST['price'] = array();
        $filters = array();
        if ( preg_match( "~\|~", $_GET['filters'] ) ) {
            $filters = explode( "|", $_GET['filters'] );
        } elseif( $_GET['filters' ]) {
            $filters[0] = $_GET['filters'];
        }   
        foreach ( $filters as $filter ) {
            if( isset($min) ) {
                unset($min);
            }
            if( isset($min) ) {
                unset($max);
            }
            if ( preg_match( "~\[~", $filter ) ) {
                list( $attribute, $value ) = explode( "[", trim( preg_replace( "~\]~", "", $filter) ), 2 );
                $attribute = berocket_wpml_attribute_untranslate($attribute);
                $value = html_entity_decode($value);
                if( term_exists( sanitize_title($value), 'pa_'.$attribute ) ) {
                    $value = array($value);
                    $operator = 'OR';
                } elseif ( preg_match( "~\*~", $value ) ) {
                    $value = explode( "-", $value );
                } elseif ( preg_match( "~\+~", $value ) ) {
                    $value    = explode( "+", $value );
                    $operator = 'AND';
                } elseif ( preg_match( "~\-~", $value ) ) {
                    $value = explode( "-", $value );
                    if( ! empty($br_options['slug_urls']) && $attribute != '_stock_status' && $attribute != '_sale' ) {
                        /*for( $i = 0; $i < count($value); $i++ ) {
                            $value[$i] = urldecode( $value[$i] );
                            while( 1 ) {
                                if ( ! term_exists( $value[$i], $attribute ) && ! term_exists( $value[$i], 'pa_'.$attribute ) ) {
                                    if( $i + 1 < count($value) )
                                    {
                                        $value[$i] = $value[$i].'-'.urldecode( $value[$i+1] );
                                        unset( $value[$i+1] );
                                        $value = array_values ( $value );
                                    } else {
                                        break;
                                    }
                                } else {
                                    $test_value = $value[$i].'-'.urldecode( $value[$i+1] );
                                    if ( ! term_exists( $test_value, $attribute ) && ! term_exists( $test_value, 'pa_'.$attribute ) ) {
                                        break;
                                    } else {
                                        if( $i + 1 < count($value) )
                                        {
                                            $value[$i] = $value[$i].'-'.urldecode( $value[$i+1] );
                                            unset( $value[$i+1] );
                                            $value = array_values ( $value );
                                        } else {
                                            break;
                                        }
                                    }
                                }
                            }
                        }*/

                        $values = array();
                        for ( $i = 0; $i < count( $value) ; $i++ ) {
                            $values[ $i ] = urldecode( $value[ $i ] );
                        }

                        $value = array();
                        $attribute_check = $attribute;
                        if( $attribute == '_rating' ) {
                            $attribute_check = 'product_visibility';
                        }
                        for ( $i = 0; $i < count( $values ); $i++ ) {
                            $cur_value = $values;
                            for ( $ii = count( $values ); $ii > 0; $ii-- ) {
                                if ( ! term_exists( implode( '-', $cur_value ), $attribute_check ) && ! term_exists( implode( '-', $cur_value ), 'pa_' . $attribute_check ) ) {
                                    array_pop( $cur_value );
                                    if ( ! $cur_value ) {
                                        break 2;
                                    }
                                } else {
                                    $value[] = implode( '-', array_splice( $values, 0, count( $cur_value ) ) );
                                    $i       = - 1;
                                    break;
                                }
                            }
                        }
                    }
                    $operator = 'OR';
                } elseif ( preg_match( "~\_~", $value ) ) {
                    list( $min, $max ) = explode( "_", $value );
                    if( $attribute == '_date' ) {
                        $min = substr($min, 0, 2).'/'.substr($min, 2, 2).'/'.substr($min, 4, 4);
                        $max = substr($max, 0, 2).'/'.substr($max, 2, 2).'/'.substr($max, 4, 4);
                    }
                    $operator = '';
                } else {
                    $value    = explode( " ", $value );
                    $operator = 'OR';
                }
            }else{
                list( $attribute, $value ) = explode( "-", $filter, 2 );
            }

            if ( $attribute == 'price' ) {
                if ( isset( $min ) && isset( $max ) ) {
                    $_POST['price'] = apply_filters('berocket_min_max_filter', array( $min, $max ));
                } else {
                    $_POST['price_ranges'] = $value;
                }
            } elseif ( $attribute == 'order' ) {
                $_GET['orderby'] = $value;
            } else {
                if ( $operator ) {
                    foreach ( $value as $v ) {
                        $type = FALSE;
                        if($attribute == 'product_tag') {
                            $type = 'tag';
                            $attribute_2 = 'product_tag';
                            $operator_2 = $operator;
                        } elseif( taxonomy_exists( 'pa_'.$attribute ) ) {
                            $type = 'attribute';
                            $attribute_2 = "pa_" . $attribute;
                            $operator_2 = $operator;
                        } elseif( taxonomy_exists( $attribute ) ) {
                            $type = 'custom_taxonomy';
                            $attribute_2 = $attribute;
                            $operator_2 = $operator;
                        } elseif( $attribute == '_stock_status' || $attribute == '_sale' ) {
                            $type = 'attribute';
                            $attribute_2 = $attribute;
                            $operator_2 = $operator;
                        } elseif( $attribute == '_rating' ) {
                            $type = 'custom_taxonomy';
                            $attribute_2 = 'product_visibility';
                            $operator_2 = $operator;
                        }
                        if($type !== FALSE) {
                            if( $attribute_2 == '_stock_status' || $attribute_2 == '_sale' ) {
                                if( $attribute_2 == '_stock_status' ) {
                                    $slug_name = array( '', 'instock', 'outofstock');
                                } else {
                                    $slug_name = array( '', 'sale', 'notsale');
                                }
                                if( ! empty($br_options['slug_urls']) ) {
                                    $attr_name = $v;
                                    $v = array_search( $v, $slug_name );
                                } else {
                                    $attr_name = $slug_name[$v];
                                }
                            } else {
                                if( ! empty($br_options['slug_urls']) ) {
                                    $attr_name = get_term_by( 'slug', $v, $attribute_2, 'OBJECT' )->term_id;
                                    $slug_name = $v;
                                    $v = $attr_name;
                                    $attr_name = $slug_name;
                                } else {
                                    $attr_name = get_term_by( 'id', $v, $attribute_2, 'OBJECT' )->slug;
                                }
                            }
                            if( $attribute_2 == '_sale' ) {
                                $_POST['add_terms'][] = array( $attribute_2, $v, $operator_2, $attr_name, $type );
                            } else {
                                $_POST['terms'][] = array( $attribute_2, $v, $operator_2, $attr_name, $type );
                            }
                        }
                    }
                } else {
                    $_POST['limits'][] = array( "pa_" . $attribute, $min, $max );
                }
            }
        }
    }
}

if ( ! function_exists( 'br_aapf_get_styled' ) ) {
    function br_aapf_get_styled() {
        return array(
            "title"          => array(
                "name" => __('Widget Title', 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "label"          => array(
                "name" => __('Label(checkbox/radio)', 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selectbox"      => array(
                "name" => __("Select-box", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => false,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => false,
                    "theme"       => true,
                    "image"       => false,
                ),
            ),
            "slider_input"   => array(
                "name" => __("Slider Inputs", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description"    => array(
                "name" => __("Description Block", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_border"    => array(
                "name" => __("Description Border", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_title"    => array(
                "name" => __("Description Title Text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_text"    => array(
                "name" => __("Description Text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area"    => array(
                "name" => __("Selected filters area text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_hover"    => array(
                "name" => __("Selected filters mouse over text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_block"    => array(
                "name" => __("Selected filters link background", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_border"    => array(
                "name" => __("Selected filters link border", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
        );
    }
}

if ( ! function_exists( 'br_aapf_converter_styles' ) ) {
    function br_aapf_converter_styles( $user_options = array() ) {
        $converted_styles = $converted_classes = array();
        $styled           = br_aapf_get_styled();
        $included_fonts   = array();
        if ( ! empty($user_options) ) {
            foreach ( $user_options as $element => $style ) {
                if ( ! empty($styled[ $element ]['has']) ) {
                    foreach ( $styled[ $element ]['has'] as $style_name => $use ) {
                        if ( $use ) {
                            if( empty($converted_styles[ $element ]) ) {
                                $converted_styles[ $element ] = '';
                            }
                            if ( $style_name == 'color' && ! empty($style['color']) ) {
                                @ $converted_styles[ $element ] .= "color: #" . ltrim( $style['color'], '#' ) . ";";
                            }
                            if ( $style_name == 'bold' and ! empty($style['bold']) ) {
                                @ $converted_styles[ $element ] .= "font-weight: {$style['bold']};";
                            }
                            if ( $style_name == 'font_size' && ! empty($style['font_size']) ) {
                                @ $converted_styles[ $element ] .= "font-size: " . ( (float) $style['font_size'] ) . "px;";
                            }


                            if ( $style_name == 'theme' ) {
                                if ( empty($style['theme']) ) {
                                    $style['theme'] = 'default';
                                } else {
                                    @ $converted_classes[ $element ] .= " themed";
                                }
                                if( empty($converted_classes[ $element ]) ) {
                                    @ $converted_classes[ $element ] = " " . $style['theme'];
                                } else {
                                    @ $converted_classes[ $element ] .= " " . $style['theme'];
                                }
                            }

                            if ( $style_name == 'font_family' and $style['font_family'] ) {
                                @ $converted_styles[ $element ] .= "font-family: '" . $style['font_family'] . "';";
                                if ( ! in_array( $style['font_family'], $included_fonts ) ) {
                                    $included_fonts[] = $style['font_family'];

                                    $http = ( is_ssl() ? 'https' : 'http' );
                                    wp_register_style( "berocket_aapf_widget-{$element}-font", $http . '://fonts.googleapis.com/css?family=' . urlencode( $style['font_family'] ) );
                                    wp_enqueue_style( "berocket_aapf_widget-{$element}-font" );
                                }
                            }
                        }
                    }
                }
            }
        }

        return array( "style" => $converted_styles, "class" => $converted_classes );
    }
}
if( ! function_exists('berocket_reset_orderby_clauses_popularity') ) {
    function berocket_reset_orderby_clauses_popularity($args) {
        $args['orderby'] = '';
        return $args;
    }
}

if( ! function_exists( 'berocket_insert_to_array' ) ) {
    /**
     * Public function to select color
     *
     * @param array $array - array with options
     * @param string $key_in_array - key in array where additional options must be added
     * @param array $array_to_insert - array with additional options
     * @param boolean $before - insert additional options before option with key $key_in_array
     *
     * @return string html code with all needed blocks and buttons
     */
    function berocket_insert_to_array($array, $key_in_array, $array_to_insert, $before = false) {
        $position = array_search($key_in_array, array_keys($array), true);
        if( $position !== FALSE ) {
            if( ! $before ) {
                $position++;
            }
            $array = array_merge(array_slice($array, 0, $position, true),
                                $array_to_insert,
                                array_slice($array, $position, NULL, true));
        }
        return $array;
    }
}
if ( ! function_exists( 'g_fonts_list' ) ) {
    function g_fonts_list() {
        return array(
            "ABeeZee",
            "Abel",
            "Abril Fatface",
            "Aclonica",
            "Acme",
            "Actor",
            "Adamina",
            "Advent Pro",
            "Aguafina Script",
            "Akronim",
            "Aladin",
            "Aldrich",
            "Alef",
            "Alegreya",
            "Alegreya SC",
            "Alegreya Sans",
            "Alegreya Sans SC",
            "Alex Brush",
            "Alfa Slab One",
            "Alice",
            "Alike",
            "Alike Angular",
            "Allan",
            "Allerta",
            "Allerta Stencil",
            "Allura",
            "Almendra",
            "Almendra Display",
            "Almendra SC",
            "Amarante",
            "Amaranth",
            "Amatic SC",
            "Amethysta",
            "Amiri",
            "Anaheim",
            "Andada",
            "Andika",
            "Angkor",
            "Annie Use Your Telescope",
            "Anonymous Pro",
            "Antic",
            "Antic Didone",
            "Antic Slab",
            "Anton",
            "Arapey",
            "Arbutus",
            "Arbutus Slab",
            "Architects Daughter",
            "Archivo Black",
            "Archivo Narrow",
            "Arimo",
            "Arizonia",
            "Armata",
            "Artifika",
            "Arvo",
            "Asap",
            "Asset",
            "Astloch",
            "Asul",
            "Atomic Age",
            "Aubrey",
            "Audiowide",
            "Autour One",
            "Average",
            "Average Sans",
            "Averia Gruesa Libre",
            "Averia Libre",
            "Averia Sans Libre",
            "Averia Serif Libre",
            "Bad Script",
            "Balthazar",
            "Bangers",
            "Basic",
            "Battambang",
            "Baumans",
            "Bayon",
            "Belgrano",
            "Belleza",
            "BenchNine",
            "Bentham",
            "Berkshire Swash",
            "Bevan",
            "Bigelow Rules",
            "Bigshot One",
            "Bilbo",
            "Bilbo Swash Caps",
            "Bitter",
            "Black Ops One",
            "Bokor",
            "Bonbon",
            "Boogaloo",
            "Bowlby One",
            "Bowlby One SC",
            "Brawler",
            "Bree Serif",
            "Bubblegum Sans",
            "Bubbler One",
            "Buda",
            "Buenard",
            "Butcherman",
            "Butterfly Kids",
            "Cabin",
            "Cabin Condensed",
            "Cabin Sketch",
            "Caesar Dressing",
            "Cagliostro",
            "Calligraffitti",
            "Cambay",
            "Cambo",
            "Candal",
            "Cantarell",
            "Cantata One",
            "Cantora One",
            "Capriola",
            "Cardo",
            "Carme",
            "Carrois Gothic",
            "Carrois Gothic SC",
            "Carter One",
            "Caudex",
            "Cedarville Cursive",
            "Ceviche One",
            "Changa One",
            "Chango",
            "Chau Philomene One",
            "Chela One",
            "Chelsea Market",
            "Chenla",
            "Cherry Cream Soda",
            "Cherry Swash",
            "Chewy",
            "Chicle",
            "Chivo",
            "Cinzel",
            "Cinzel Decorative",
            "Clicker Script",
            "Coda",
            "Coda Caption",
            "Codystar",
            "Combo",
            "Comfortaa",
            "Coming Soon",
            "Concert One",
            "Condiment",
            "Content",
            "Contrail One",
            "Convergence",
            "Cookie",
            "Copse",
            "Corben",
            "Courgette",
            "Cousine",
            "Coustard",
            "Covered By Your Grace",
            "Crafty Girls",
            "Creepster",
            "Crete Round",
            "Crimson Text",
            "Croissant One",
            "Crushed",
            "Cuprum",
            "Cutive",
            "Cutive Mono",
            "Damion",
            "Dancing Script",
            "Dangrek",
            "Dawning of a New Day",
            "Days One",
            "Dekko",
            "Delius",
            "Delius Swash Caps",
            "Delius Unicase",
            "Della Respira",
            "Denk One",
            "Devonshire",
            "Dhurjati",
            "Didact Gothic",
            "Diplomata",
            "Diplomata SC",
            "Domine",
            "Donegal One",
            "Doppio One",
            "Dorsa",
            "Dosis",
            "Dr Sugiyama",
            "Droid Sans",
            "Droid Sans Mono",
            "Droid Serif",
            "Duru Sans",
            "Dynalight",
            "EB Garamond",
            "Eagle Lake",
            "Eater",
            "Economica",
            "Ek Mukta",
            "Electrolize",
            "Elsie",
            "Elsie Swash Caps",
            "Emblema One",
            "Emilys Candy",
            "Engagement",
            "Englebert",
            "Enriqueta",
            "Erica One",
            "Esteban",
            "Euphoria Script",
            "Ewert",
            "Exo",
            "Exo 2",
            "Expletus Sans",
            "Fanwood Text",
            "Fascinate",
            "Fascinate Inline",
            "Faster One",
            "Fasthand",
            "Fauna One",
            "Federant",
            "Federo",
            "Felipa",
            "Fenix",
            "Finger Paint",
            "Fira Mono",
            "Fira Sans",
            "Fjalla One",
            "Fjord One",
            "Flamenco",
            "Flavors",
            "Fondamento",
            "Fontdiner Swanky",
            "Forum",
            "Francois One",
            "Freckle Face",
            "Fredericka the Great",
            "Fredoka One",
            "Freehand",
            "Fresca",
            "Frijole",
            "Fruktur",
            "Fugaz One",
            "GFS Didot",
            "GFS Neohellenic",
            "Gabriela",
            "Gafata",
            "Galdeano",
            "Galindo",
            "Gentium Basic",
            "Gentium Book Basic",
            "Geo",
            "Geostar",
            "Geostar Fill",
            "Germania One",
            "Gidugu",
            "Gilda Display",
            "Give You Glory",
            "Glass Antiqua",
            "Glegoo",
            "Gloria Hallelujah",
            "Goblin One",
            "Gochi Hand",
            "Gorditas",
            "Goudy Bookletter 1911",
            "Graduate",
            "Grand Hotel",
            "Gravitas One",
            "Great Vibes",
            "Griffy",
            "Gruppo",
            "Gudea",
            "Gurajada",
            "Habibi",
            "Halant",
            "Hammersmith One",
            "Hanalei",
            "Hanalei Fill",
            "Handlee",
            "Hanuman",
            "Happy Monkey",
            "Headland One",
            "Henny Penny",
            "Herr Von Muellerhoff",
            "Hind",
            "Holtwood One SC",
            "Homemade Apple",
            "Homenaje",
            "IM Fell DW Pica",
            "IM Fell DW Pica SC",
            "IM Fell Double Pica",
            "IM Fell Double Pica SC",
            "IM Fell English",
            "IM Fell English SC",
            "IM Fell French Canon",
            "IM Fell French Canon SC",
            "IM Fell Great Primer",
            "IM Fell Great Primer SC",
            "Iceberg",
            "Iceland",
            "Imprima",
            "Inconsolata",
            "Inder",
            "Indie Flower",
            "Inika",
            "Irish Grover",
            "Istok Web",
            "Italiana",
            "Italianno",
            "Jacques Francois",
            "Jacques Francois Shadow",
            "Jim Nightshade",
            "Jockey One",
            "Jolly Lodger",
            "Josefin Sans",
            "Josefin Slab",
            "Joti One",
            "Judson",
            "Julee",
            "Julius Sans One",
            "Junge",
            "Jura",
            "Just Another Hand",
            "Just Me Again Down Here",
            "Kalam",
            "Kameron",
            "Kantumruy",
            "Karla",
            "Karma",
            "Kaushan Script",
            "Kavoon",
            "Kdam Thmor",
            "Keania One",
            "Kelly Slab",
            "Kenia",
            "Khand",
            "Khmer",
            "Khula",
            "Kite One",
            "Knewave",
            "Kotta One",
            "Koulen",
            "Kranky",
            "Kreon",
            "Kristi",
            "Krona One",
            "La Belle Aurore",
            "Laila",
            "Lakki Reddy",
            "Lancelot",
            "Lateef",
            "Lato",
            "League Script",
            "Leckerli One",
            "Ledger",
            "Lekton",
            "Lemon",
            "Libre Baskerville",
            "Life Savers",
            "Lilita One",
            "Lily Script One",
            "Limelight",
            "Linden Hill",
            "Lobster",
            "Lobster Two",
            "Londrina Outline",
            "Londrina Shadow",
            "Londrina Sketch",
            "Londrina Solid",
            "Lora",
            "Love Ya Like A Sister",
            "Loved by the King",
            "Lovers Quarrel",
            "Luckiest Guy",
            "Lusitana",
            "Lustria",
            "Macondo",
            "Macondo Swash Caps",
            "Magra",
            "Maiden Orange",
            "Mako",
            "Mallanna",
            "Mandali",
            "Marcellus",
            "Marcellus SC",
            "Marck Script",
            "Margarine",
            "Marko One",
            "Marmelad",
            "Martel Sans",
            "Marvel",
            "Mate",
            "Mate SC",
            "Maven Pro",
            "McLaren",
            "Meddon",
            "MedievalSharp",
            "Medula One",
            "Megrim",
            "Meie Script",
            "Merienda",
            "Merienda One",
            "Merriweather",
            "Merriweather Sans",
            "Metal",
            "Metal Mania",
            "Metamorphous",
            "Metrophobic",
            "Michroma",
            "Milonga",
            "Miltonian",
            "Miltonian Tattoo",
            "Miniver",
            "Miss Fajardose",
            "Modak",
            "Modern Antiqua",
            "Molengo",
            "Molle",
            "Monda",
            "Monofett",
            "Monoton",
            "Monsieur La Doulaise",
            "Montaga",
            "Montez",
            "Montserrat",
            "Montserrat Alternates",
            "Montserrat Subrayada",
            "Moul",
            "Moulpali",
            "Mountains of Christmas",
            "Mouse Memoirs",
            "Mr Bedfort",
            "Mr Dafoe",
            "Mr De Haviland",
            "Mrs Saint Delafield",
            "Mrs Sheppards",
            "Muli",
            "Mystery Quest",
            "NTR",
            "Neucha",
            "Neuton",
            "New Rocker",
            "News Cycle",
            "Niconne",
            "Nixie One",
            "Nobile",
            "Nokora",
            "Norican",
            "Nosifer",
            "Nothing You Could Do",
            "Noticia Text",
            "Noto Sans",
            "Noto Serif",
            "Nova Cut",
            "Nova Flat",
            "Nova Mono",
            "Nova Oval",
            "Nova Round",
            "Nova Script",
            "Nova Slim",
            "Nova Square",
            "Numans",
            "Nunito",
            "Odor Mean Chey",
            "Offside",
            "Old Standard TT",
            "Oldenburg",
            "Oleo Script",
            "Oleo Script Swash Caps",
            "Open Sans",
            "Open Sans Condensed",
            "Oranienbaum",
            "Orbitron",
            "Oregano",
            "Orienta",
            "Original Surfer",
            "Oswald",
            "Over the Rainbow",
            "Overlock",
            "Overlock SC",
            "Ovo",
            "Oxygen",
            "Oxygen Mono",
            "PT Mono",
            "PT Sans",
            "PT Sans Caption",
            "PT Sans Narrow",
            "PT Serif",
            "PT Serif Caption",
            "Pacifico",
            "Paprika",
            "Parisienne",
            "Passero One",
            "Passion One",
            "Pathway Gothic One",
            "Patrick Hand",
            "Patrick Hand SC",
            "Patua One",
            "Paytone One",
            "Peddana",
            "Peralta",
            "Permanent Marker",
            "Petit Formal Script",
            "Petrona",
            "Philosopher",
            "Piedra",
            "Pinyon Script",
            "Pirata One",
            "Plaster",
            "Play",
            "Playball",
            "Playfair Display",
            "Playfair Display SC",
            "Podkova",
            "Poiret One",
            "Poller One",
            "Poly",
            "Pompiere",
            "Pontano Sans",
            "Port Lligat Sans",
            "Port Lligat Slab",
            "Prata",
            "Preahvihear",
            "Press Start 2P",
            "Princess Sofia",
            "Prociono",
            "Prosto One",
            "Puritan",
            "Purple Purse",
            "Quando",
            "Quantico",
            "Quattrocento",
            "Quattrocento Sans",
            "Questrial",
            "Quicksand",
            "Quintessential",
            "Qwigley",
            "Racing Sans One",
            "Radley",
            "Rajdhani",
            "Raleway",
            "Raleway Dots",
            "Ramabhadra",
            "Ramaraja",
            "Rambla",
            "Rammetto One",
            "Ranchers",
            "Rancho",
            "Ranga",
            "Rationale",
            "Ravi Prakash",
            "Redressed",
            "Reenie Beanie",
            "Revalia",
            "Ribeye",
            "Ribeye Marrow",
            "Righteous",
            "Risque",
            "Roboto",
            "Roboto Condensed",
            "Roboto Slab",
            "Rochester",
            "Rock Salt",
            "Rokkitt",
            "Romanesco",
            "Ropa Sans",
            "Rosario",
            "Rosarivo",
            "Rouge Script",
            "Rozha One",
            "Rubik Mono One",
            "Rubik One",
            "Ruda",
            "Rufina",
            "Ruge Boogie",
            "Ruluko",
            "Rum Raisin",
            "Ruslan Display",
            "Russo One",
            "Ruthie",
            "Rye",
            "Sacramento",
            "Sail",
            "Salsa",
            "Sanchez",
            "Sancreek",
            "Sansita One",
            "Sarina",
            "Sarpanch",
            "Satisfy",
            "Scada",
            "Scheherazade",
            "Schoolbell",
            "Seaweed Script",
            "Sevillana",
            "Seymour One",
            "Shadows Into Light",
            "Shadows Into Light Two",
            "Shanti",
            "Share",
            "Share Tech",
            "Share Tech Mono",
            "Shojumaru",
            "Short Stack",
            "Siemreap",
            "Sigmar One",
            "Signika",
            "Signika Negative",
            "Simonetta",
            "Sintony",
            "Sirin Stencil",
            "Six Caps",
            "Skranji",
            "Slabo 13px",
            "Slabo 27px",
            "Slackey",
            "Smokum",
            "Smythe",
            "Sniglet",
            "Snippet",
            "Snowburst One",
            "Sofadi One",
            "Sofia",
            "Sonsie One",
            "Sorts Mill Goudy",
            "Source Code Pro",
            "Source Sans Pro",
            "Source Serif Pro",
            "Special Elite",
            "Spicy Rice",
            "Spinnaker",
            "Spirax",
            "Squada One",
            "Sree Krushnadevaraya",
            "Stalemate",
            "Stalinist One",
            "Stardos Stencil",
            "Stint Ultra Condensed",
            "Stint Ultra Expanded",
            "Stoke",
            "Strait",
            "Sue Ellen Francisco",
            "Sunshiney",
            "Supermercado One",
            "Suranna",
            "Suravaram",
            "Suwannaphum",
            "Swanky and Moo Moo",
            "Syncopate",
            "Tangerine",
            "Taprom",
            "Tauri",
            "Teko",
            "Telex",
            "Tenali Ramakrishna",
            "Tenor Sans",
            "Text Me One",
            "The Girl Next Door",
            "Tienne",
            "Timmana",
            "Tinos",
            "Titan One",
            "Titillium Web",
            "Trade Winds",
            "Trocchi",
            "Trochut",
            "Trykker",
            "Tulpen One",
            "Ubuntu",
            "Ubuntu Condensed",
            "Ubuntu Mono",
            "Ultra",
            "Uncial Antiqua",
            "Underdog",
            "Unica One",
            "UnifrakturCook",
            "UnifrakturMaguntia",
            "Unkempt",
            "Unlock",
            "Unna",
            "VT323",
            "Vampiro One",
            "Varela",
            "Varela Round",
            "Vast Shadow",
            "Vesper Libre",
            "Vibur",
            "Vidaloka",
            "Viga",
            "Voces",
            "Volkhov",
            "Vollkorn",
            "Voltaire",
            "Waiting for the Sunrise",
            "Wallpoet",
            "Walter Turncoat",
            "Warnes",
            "Wellfleet",
            "Wendy One",
            "Wire One",
            "Yanone Kaffeesatz",
            "Yellowtail",
            "Yeseva One",
            "Yesteryear",
            "Zeyada"
        );
    }
}
if ( ! function_exists( 'fa_icons_list' ) ) {
    function fa_icons_list() {
        return array(
            "fa-glass",
            "fa-music",
            "fa-search",
            "fa-envelope-o",
            "fa-heart",
            "fa-star",
            "fa-star-o",
            "fa-user",
            "fa-film",
            "fa-th-large",
            "fa-th",
            "fa-th-list",
            "fa-check",
            "fa-times",
            "fa-search-plus",
            "fa-search-minus",
            "fa-power-off",
            "fa-signal",
            "fa-cog",
            "fa-trash-o",
            "fa-home",
            "fa-file-o",
            "fa-clock-o",
            "fa-road",
            "fa-download",
            "fa-arrow-circle-o-down",
            "fa-arrow-circle-o-up",
            "fa-inbox",
            "fa-play-circle-o",
            "fa-repeat",
            "fa-refresh",
            "fa-list-alt",
            "fa-lock",
            "fa-flag",
            "fa-headphones",
            "fa-volume-off",
            "fa-volume-down",
            "fa-volume-up",
            "fa-qrcode",
            "fa-barcode",
            "fa-tag",
            "fa-tags",
            "fa-book",
            "fa-bookmark",
            "fa-print",
            "fa-camera",
            "fa-font",
            "fa-bold",
            "fa-italic",
            "fa-text-height",
            "fa-text-width",
            "fa-align-left",
            "fa-align-center",
            "fa-align-right",
            "fa-align-justify",
            "fa-list",
            "fa-outdent",
            "fa-indent",
            "fa-video-camera",
            "fa-picture-o",
            "fa-pencil",
            "fa-map-marker",
            "fa-adjust",
            "fa-tint",
            "fa-pencil-square-o",
            "fa-share-square-o",
            "fa-check-square-o",
            "fa-arrows",
            "fa-step-backward",
            "fa-fast-backward",
            "fa-backward",
            "fa-play",
            "fa-pause",
            "fa-stop",
            "fa-forward",
            "fa-fast-forward",
            "fa-step-forward",
            "fa-eject",
            "fa-chevron-left",
            "fa-chevron-right",
            "fa-plus-circle",
            "fa-minus-circle",
            "fa-times-circle",
            "fa-check-circle",
            "fa-question-circle",
            "fa-info-circle",
            "fa-crosshairs",
            "fa-times-circle-o",
            "fa-check-circle-o",
            "fa-ban",
            "fa-arrow-left",
            "fa-arrow-right",
            "fa-arrow-up",
            "fa-arrow-down",
            "fa-share",
            "fa-expand",
            "fa-compress",
            "fa-plus",
            "fa-minus",
            "fa-asterisk",
            "fa-exclamation-circle",
            "fa-gift",
            "fa-leaf",
            "fa-fire",
            "fa-eye",
            "fa-eye-slash",
            "fa-exclamation-triangle",
            "fa-plane",
            "fa-calendar",
            "fa-random",
            "fa-comment",
            "fa-magnet",
            "fa-chevron-up",
            "fa-chevron-down",
            "fa-retweet",
            "fa-shopping-cart",
            "fa-folder",
            "fa-folder-open",
            "fa-arrows-v",
            "fa-arrows-h",
            "fa-bar-chart",
            "fa-twitter-square",
            "fa-facebook-square",
            "fa-camera-retro",
            "fa-key",
            "fa-cogs",
            "fa-comments",
            "fa-thumbs-o-up",
            "fa-thumbs-o-down",
            "fa-star-half",
            "fa-heart-o",
            "fa-sign-out",
            "fa-linkedin-square",
            "fa-thumb-tack",
            "fa-external-link",
            "fa-sign-in",
            "fa-trophy",
            "fa-github-square",
            "fa-upload",
            "fa-lemon-o",
            "fa-phone",
            "fa-square-o",
            "fa-bookmark-o",
            "fa-phone-square",
            "fa-twitter",
            "fa-facebook",
            "fa-github",
            "fa-unlock",
            "fa-credit-card",
            "fa-rss",
            "fa-hdd-o",
            "fa-bullhorn",
            "fa-bell",
            "fa-certificate",
            "fa-hand-o-right",
            "fa-hand-o-left",
            "fa-hand-o-up",
            "fa-hand-o-down",
            "fa-arrow-circle-left",
            "fa-arrow-circle-right",
            "fa-arrow-circle-up",
            "fa-arrow-circle-down",
            "fa-globe",
            "fa-wrench",
            "fa-tasks",
            "fa-filter",
            "fa-briefcase",
            "fa-arrows-alt",
            "fa-users",
            "fa-link",
            "fa-cloud",
            "fa-flask",
            "fa-scissors",
            "fa-files-o",
            "fa-paperclip",
            "fa-floppy-o",
            "fa-square",
            "fa-bars",
            "fa-list-ul",
            "fa-list-ol",
            "fa-strikethrough",
            "fa-underline",
            "fa-table",
            "fa-magic",
            "fa-truck",
            "fa-pinterest",
            "fa-pinterest-square",
            "fa-google-plus-square",
            "fa-google-plus",
            "fa-money",
            "fa-caret-down",
            "fa-caret-up",
            "fa-caret-left",
            "fa-caret-right",
            "fa-columns",
            "fa-sort",
            "fa-sort-desc",
            "fa-sort-asc",
            "fa-envelope",
            "fa-linkedin",
            "fa-undo",
            "fa-gavel",
            "fa-tachometer",
            "fa-comment-o",
            "fa-comments-o",
            "fa-bolt",
            "fa-sitemap",
            "fa-umbrella",
            "fa-clipboard",
            "fa-lightbulb-o",
            "fa-exchange",
            "fa-cloud-download",
            "fa-cloud-upload",
            "fa-user-md",
            "fa-stethoscope",
            "fa-suitcase",
            "fa-bell-o",
            "fa-coffee",
            "fa-cutlery",
            "fa-file-text-o",
            "fa-building-o",
            "fa-hospital-o",
            "fa-ambulance",
            "fa-medkit",
            "fa-fighter-jet",
            "fa-beer",
            "fa-h-square",
            "fa-plus-square",
            "fa-angle-double-left",
            "fa-angle-double-right",
            "fa-angle-double-up",
            "fa-angle-double-down",
            "fa-angle-left",
            "fa-angle-right",
            "fa-angle-up",
            "fa-angle-down",
            "fa-desktop",
            "fa-laptop",
            "fa-tablet",
            "fa-mobile",
            "fa-circle-o",
            "fa-quote-left",
            "fa-quote-right",
            "fa-spinner",
            "fa-circle",
            "fa-reply",
            "fa-github-alt",
            "fa-folder-o",
            "fa-folder-open-o",
            "fa-smile-o",
            "fa-frown-o",
            "fa-meh-o",
            "fa-gamepad",
            "fa-keyboard-o",
            "fa-flag-o",
            "fa-flag-checkered",
            "fa-terminal",
            "fa-code",
            "fa-reply-all",
            "fa-star-half-o",
            "fa-location-arrow",
            "fa-crop",
            "fa-code-fork",
            "fa-chain-broken",
            "fa-question",
            "fa-info",
            "fa-exclamation",
            "fa-superscript",
            "fa-subscript",
            "fa-eraser",
            "fa-puzzle-piece",
            "fa-microphone",
            "fa-microphone-slash",
            "fa-shield",
            "fa-calendar-o",
            "fa-fire-extinguisher",
            "fa-rocket",
            "fa-maxcdn",
            "fa-chevron-circle-left",
            "fa-chevron-circle-right",
            "fa-chevron-circle-up",
            "fa-chevron-circle-down",
            "fa-html5",
            "fa-css3",
            "fa-anchor",
            "fa-unlock-alt",
            "fa-bullseye",
            "fa-ellipsis-h",
            "fa-ellipsis-v",
            "fa-rss-square",
            "fa-play-circle",
            "fa-ticket",
            "fa-minus-square",
            "fa-minus-square-o",
            "fa-level-up",
            "fa-level-down",
            "fa-check-square",
            "fa-pencil-square",
            "fa-external-link-square",
            "fa-share-square",
            "fa-compass",
            "fa-caret-square-o-down",
            "fa-caret-square-o-up",
            "fa-caret-square-o-right",
            "fa-eur",
            "fa-gbp",
            "fa-usd",
            "fa-inr",
            "fa-jpy",
            "fa-rub",
            "fa-krw",
            "fa-btc",
            "fa-file",
            "fa-file-text",
            "fa-sort-alpha-asc",
            "fa-sort-alpha-desc",
            "fa-sort-amount-asc",
            "fa-sort-amount-desc",
            "fa-sort-numeric-asc",
            "fa-sort-numeric-desc",
            "fa-thumbs-up",
            "fa-thumbs-down",
            "fa-youtube-square",
            "fa-youtube",
            "fa-xing",
            "fa-xing-square",
            "fa-youtube-play",
            "fa-dropbox",
            "fa-stack-overflow",
            "fa-instagram",
            "fa-flickr",
            "fa-adn",
            "fa-bitbucket",
            "fa-bitbucket-square",
            "fa-tumblr",
            "fa-tumblr-square",
            "fa-long-arrow-down",
            "fa-long-arrow-up",
            "fa-long-arrow-left",
            "fa-long-arrow-right",
            "fa-apple",
            "fa-windows",
            "fa-android",
            "fa-linux",
            "fa-dribbble",
            "fa-skype",
            "fa-foursquare",
            "fa-trello",
            "fa-female",
            "fa-male",
            "fa-gittip",
            "fa-sun-o",
            "fa-moon-o",
            "fa-archive",
            "fa-bug",
            "fa-vk",
            "fa-weibo",
            "fa-renren",
            "fa-pagelines",
            "fa-stack-exchange",
            "fa-arrow-circle-o-right",
            "fa-arrow-circle-o-left",
            "fa-caret-square-o-left",
            "fa-dot-circle-o",
            "fa-wheelchair",
            "fa-vimeo-square",
            "fa-try",
            "fa-plus-square-o",
            "fa-space-shuttle",
            "fa-slack",
            "fa-envelope-square",
            "fa-wordpress",
            "fa-openid",
            "fa-university",
            "fa-graduation-cap",
            "fa-yahoo",
            "fa-google",
            "fa-reddit",
            "fa-reddit-square",
            "fa-stumbleupon-circle",
            "fa-stumbleupon",
            "fa-delicious",
            "fa-digg",
            "fa-pied-piper",
            "fa-pied-piper-alt",
            "fa-drupal",
            "fa-joomla",
            "fa-language",
            "fa-fax",
            "fa-building",
            "fa-child",
            "fa-paw",
            "fa-spoon",
            "fa-cube",
            "fa-cubes",
            "fa-behance",
            "fa-behance-square",
            "fa-steam",
            "fa-steam-square",
            "fa-recycle",
            "fa-car",
            "fa-taxi",
            "fa-tree",
            "fa-spotify",
            "fa-deviantart",
            "fa-soundcloud",
            "fa-database",
            "fa-file-pdf-o",
            "fa-file-word-o",
            "fa-file-excel-o",
            "fa-file-powerpoint-o",
            "fa-file-image-o",
            "fa-file-archive-o",
            "fa-file-audio-o",
            "fa-file-video-o",
            "fa-file-code-o",
            "fa-vine",
            "fa-codepen",
            "fa-jsfiddle",
            "fa-life-ring",
            "fa-circle-o-notch",
            "fa-rebel",
            "fa-empire",
            "fa-git-square",
            "fa-git",
            "fa-hacker-news",
            "fa-tencent-weibo",
            "fa-qq",
            "fa-weixin",
            "fa-paper-plane",
            "fa-paper-plane-o",
            "fa-history",
            "fa-circle-thin",
            "fa-header",
            "fa-paragraph",
            "fa-sliders",
            "fa-share-alt",
            "fa-share-alt-square",
            "fa-bomb",
            "fa-futbol-o",
            "fa-tty",
            "fa-binoculars",
            "fa-plug",
            "fa-slideshare",
            "fa-twitch",
            "fa-yelp",
            "fa-newspaper-o",
            "fa-wifi",
            "fa-calculator",
            "fa-paypal",
            "fa-google-wallet",
            "fa-cc-visa",
            "fa-cc-mastercard",
            "fa-cc-discover",
            "fa-cc-amex",
            "fa-cc-paypal",
            "fa-cc-stripe",
            "fa-bell-slash",
            "fa-bell-slash-o",
            "fa-trash",
            "fa-copyright",
            "fa-at",
            "fa-eyedropper",
            "fa-paint-brush",
            "fa-birthday-cake",
            "fa-area-chart",
            "fa-pie-chart",
            "fa-line-chart",
            "fa-lastfm",
            "fa-lastfm-square",
            "fa-toggle-off",
            "fa-toggle-on",
            "fa-bicycle",
            "fa-bus",
            "fa-ioxhost",
            "fa-angellist",
            "fa-cc",
            "fa-ils",
            "fa-meanpath",
        );
    }
}
if ( ! function_exists( 'berocket_font_select_upload' ) ) {
    /**
     * Public function to add to plugin settings buttons to upload or select icons
     *
     * @param string $text - Text above buttons
     * @param string $id - input ID
     * @param string $name - input name
     * @param string $value - current value link or font awesome icon class
     * @param bolean $show_fa - show font awesome button and generate font awesome icon table
     * @param bolean $show_upload - show upload button that allow upload custom icons
     * @param bolean $show_remove - show remove button that allow clear input
     * @param string $data_sc - add data-sc options with this value into input 
     *
     * @return string html code with all needed blocks and buttons
     */
    function berocket_font_select_upload( $text, $id, $name, $value, $show_fa = true, $show_upload = true, $show_remove = true, $data_sc = '' ) {
        if ( $show_fa ) {
            $font_awesome_list = fa_icons_list();
            $font_awesome      = "";
            foreach ( $font_awesome_list as $font ) {
                $font_awesome .= '<label><span data-value="' . $font . '" 
                    class="berocket_aapf_icon_select"></span><i class="fa ' . $font . '"></i></label>';
            }
        }
        $result = '<div>';
        if ( $text && $text != '' ) {
            $result .= '<p>' . $text . '</p>';
        }
        $result .= '<input id="' . $id . '" type="text" name="' . $name . '" '.( ( $data_sc ) ? 'data-sc="'.$data_sc.'" ' : '' ).'value="' . $value . '"
            readonly style="display:none;" class="' . $name . ' '.( ( $data_sc ) ? 'berocket_aapf_widget_sc ' : '' ).'berocket_aapf_icon_text_value"/><span class="berocket_aapf_selected_icon_show">
            ' . ( ! empty( $value ) ? ( ( substr( $value, 0, 3 ) == 'fa-' ) ? '<i class="fa ' . $value . '"></i>' : '<i class="fa">
            <image src="' . $value . '" alt=""></i>' ) : '' ) . '</span>';
        if ( $show_fa ) {
            $result .= '<input type="button" class="berocket_aapf_font_awesome_icon_select button" value="'.__('Font awesome', 'BeRocket_AJAX_domain').'"/>
            <div style="display: none;" class="berocket_aapf_select_icon"><div><p>Font Awesome Icons<i class="fa fa-times"></i></p>
            ' . $font_awesome . '</div></div>';
        }
        if ( $show_upload ) {
            $result .= '<input type="button" class="berocket_aapf_upload_icon button" value="'.__('Upload', 'BeRocket_AJAX_domain').'"/> ';
        }
        if ( $show_remove ) {
            $result .= '<input type="button" class="berocket_aapf_remove_icon button" value="'.__('Remove', 'BeRocket_AJAX_domain').'"/>';
        }
        $result .= '</div>';

        return $result;
    }
}

if ( ! function_exists( 'br_get_post_meta_price' ) ) {
    /**
     * Public function to get price of product
     *
     * @param int $object_id product id
     *
     * @return float product price
     */
    function br_get_post_meta_price( $object_id ) {
        global $wpdb;

        $meta_list = $wpdb->get_row("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$object_id} AND meta_key = '_price' ORDER BY meta_id ASC LIMIT 1", ARRAY_A );

        return maybe_unserialize( $meta_list['meta_value'] );
    }
}

if ( ! function_exists( 'br_get_taxonomy_id' ) ) {
    /**
     * Public function to get category id by $value in $field
     *
     * @param string $value value for search
     * @param string $field by what field is search
     *
     * @return int category id
     */
    function br_get_taxonomy_id( $taxonomy, $value, $field = 'slug', $return = 'term_id' ) {
        global $wpdb;

        if ( 'id' == $field ) {
            return $value;
        } elseif ( 'slug' == $field ) {
            $field = 't.slug';
            $value = sanitize_title( $value );
            if ( empty( $value ) ) {
                return false;
            }
        } elseif ( 'name' == $field ) {
            $value = wp_unslash( $value );
            $field = 't.name';
        } else {
            return false;
        }

        $term = $wpdb->get_row(
            $wpdb->prepare( "SELECT t.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy
                  AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = '%s' AND $field = %s LIMIT 1", $taxonomy, $value )
        );

        if ( ! $term )
            return false;

        $term = (array)$term;
        return $term[$return];
    }
}

if ( ! function_exists( 'br_get_sub_taxonomies' ) ) {
    /**
     * Public function to get sub categories from category
     *
     * @param string $field_value value for search
     * @param string $field_name by what field is search
     * @param array $args 'return' - type of return data, 
     * 'include_parent' = include parent to cate gories list, 'max_depth' - max depth of sub category
     *
     * @return string|array|o category
     */
    function br_get_sub_taxonomies( $taxonomy, $field_value, $field_name = 'slug', $args = array(), $return = 'term_id' ) {
        $defaults  = array( 'return' => 'string', 'include_parent' => false, 'max_depth' => 9 );
        $args      = wp_parse_args( $args, $defaults );
        $parent_id = 0;

        if ( $field_value ) {
            $parent_id = br_get_taxonomy_id( $taxonomy, $field_value, $field_name, $return );
        }

        $args['taxonomy_name'] = $taxonomy;
        $categories = br_get_cat_hierarchy( $args, $parent_id );

        if ( $args['include_parent'] ) {
            if ( $args['return'] == 'string' ) {
                if ( $parent_id ) {
                    if ( $categories ) $categories .= ",";
                    $categories .= $parent_id;
                }
            } elseif ( $args['return'] == 'array' ) {
                array_unshift( $cat_hierarchy, $parent_id );
            } elseif ( $args['return'] == 'hierarchy_objects' ) {
                $cat = br_get_category( $parent_id );
                $cat->depth = 0;
                $cat_hierarchy[ $parent_id ] = $cat;
                ksort( $cat_hierarchy );
            }
        }

        return $categories;
    }
}

if ( ! function_exists( 'br_get_category_id' ) ) {
    /**
     * Public function to get category id by $value in $field
     *
     * @param string $value value for search
     * @param string $field by what field is search
     *
     * @return int category id
     */
    function br_get_category_id( $value, $field = 'slug', $return = 'term_id' ) {
        global $wpdb;

        if ( 'id' == $field ) {
            return $value;
        } elseif ( 'slug' == $field ) {
            $field = 't.slug';
            $value = sanitize_title( $value );
            if ( empty( $value ) ) {
                return false;
            }
        } elseif ( 'name' == $field ) {
            $value = wp_unslash( $value );
            $field = 't.name';
        } else {
            return false;
        }

        $term = $wpdb->get_row(
            $wpdb->prepare( "SELECT t.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy
                  AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'product_cat' AND $field = %s LIMIT 1", $value )
        );

        if ( ! $term )
            return false;

        $term = (array)$term;
        return $term[$return];
    }
}

if ( ! function_exists( 'br_get_category' ) ) {
    /**
     * Public function to get category by ID
     *
     * @param int $id category id
     *
     * @return object category
     */
    function br_get_category( $id ) {
        global $wpdb;

        if ( ! $id = (int) $id or ! $term = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->terms WHERE term_id = %s", $id ) ) ) {
            return false;
        }

        return $term;
    }
}

if ( ! function_exists( 'br_get_sub_categories' ) ) {
    /**
     * Public function to get sub categories from category
     *
     * @param string $field_value value for search
     * @param string $field_name by what field is search
     * @param array $args 'return' - type of return data, 
     * 'include_parent' = include parent to cate gories list, 'max_depth' - max depth of sub category
     *
     * @return string|array|o category
     */
    function br_get_sub_categories( $field_value, $field_name = 'slug', $args = array(), $return = 'term_id' ) {
        $defaults  = array( 'return' => 'string', 'include_parent' => false, 'max_depth' => 9 );
        $args      = wp_parse_args( $args, $defaults );
        $parent_id = 0;

        if ( $field_value ) {
            $parent_id = br_get_category_id( $field_value, $field_name, $return );
        }

        $categories = br_get_cat_hierarchy( $args, $parent_id );

        if ( $args['include_parent'] ) {
            if ( $args['return'] == 'string' ) {
                if ( $parent_id ) {
                    if ( $categories ) $categories .= ",";
                    $categories .= $parent_id;
                }
            } elseif ( $args['return'] == 'array' ) {
                array_unshift( $cat_hierarchy, $parent_id );
            } elseif ( $args['return'] == 'hierarchy_objects' ) {
                $cat = br_get_category( $parent_id );
                $cat->depth = 0;
                $cat_hierarchy[ $parent_id ] = $cat;
                ksort( $cat_hierarchy );
            }
        }
        return $categories;
    }
}

if ( ! function_exists( 'br_wp_get_object_terms' ) ) {
    /**
     * Public function to get terms by id and taxonomy
     *
     * @param int $object_id category id
     * @param int $taxonomy category id
     *
     * @return array terms
     */
    function br_wp_get_object_terms( $object_id, $taxonomy, $args = array() ) {
        global $wpdb;

        if ( empty( $object_id ) || empty( $taxonomy ) )
            return array();

        $object_id = (int) $object_id;

        $terms = array();
        $fields = $args['fields'] ? $args['fields'] : 'all' ;

        $select_this = '';
        if ( 'all' == $fields ) {
            $select_this = 't.*, tt.*';
        } elseif ( 'ids' == $fields ) {
            $select_this = 't.term_id';
        } elseif ( 'names' == $fields ) {
            $select_this = 't.name';
        } elseif ( 'slugs' == $fields ) {
            $select_this = 't.slug';
        } elseif ( 'all_with_object_id' == $fields ) {
            $select_this = 't.*, tt.*, tr.object_id';
        }

        $query = "SELECT $select_this FROM $wpdb->terms AS t
                  INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id
                  INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  WHERE tt.taxonomy = '$taxonomy' AND tr.object_id = $object_id
                  ORDER BY t.term_id ASC";

        if( BeRocket_AAPF::$debug_mode ) {
            $wpdb->show_errors();
            BeRocket_AAPF::$error_log['102_get_object_terms_SELECT'] = $query;
        }

        $objects = false;
        if ( 'all' == $fields || 'all_with_object_id' == $fields ) {
            $_terms = $wpdb->get_results( $query );
            if( BeRocket_AAPF::$debug_mode ) {
                BeRocket_AAPF::$error_log['000_select_status'][] = @ $wpdb->last_error;
            }
            foreach ( $_terms as $key => $term ) {
                $_terms[$key] = sanitize_term( $term, $taxonomy, 'raw' );
            }
            $terms = array_merge( $terms, $_terms );
            $objects = true;
        } elseif ( 'ids' == $fields || 'names' == $fields || 'slugs' == $fields ) {
            $_terms = $wpdb->get_col( $query );
            if( BeRocket_AAPF::$debug_mode ) {
                ob_start();
                if ( $wpdb->last_error ) {
                    $wpdb->print_error();
                }
                BeRocket_AAPF::$error_log['000_select_status'][] = ob_get_contents();
                ob_end_clean();
            }
            $_field = ( 'ids' == $fields ) ? 'term_id' : 'name';
            foreach ( $_terms as $key => $term ) {
                $_terms[$key] = sanitize_term_field( $_field, $term, $term, $taxonomy, 'raw' );
            }
            $terms = array_merge( $terms, $_terms );
        } elseif ( 'tt_ids' == $fields ) {
            $terms = $wpdb->get_col("SELECT tr.term_taxonomy_id FROM $wpdb->term_relationships AS tr INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id IN ($object_ids) AND tt.taxonomy IN ($taxonomies) $orderby $order");
            if( BeRocket_AAPF::$debug_mode ) {
                ob_start();
                if ( $wpdb->last_error ) {
                    $wpdb->print_error();
                }
                BeRocket_AAPF::$error_log['000_select_status'][] = ob_get_contents();
                ob_end_clean();
            }
            foreach ( $terms as $key => $tt_id ) {
                $terms[$key] = sanitize_term_field( 'term_taxonomy_id', $tt_id, 0, $taxonomy, 'raw' ); // 0 should be the term id, however is not needed when using raw context.
            }
        }

        if ( ! $terms ) {
            return array();
        } elseif ( $objects && 'all_with_object_id' !== $fields ) {
            $_tt_ids = array();
            $_terms = array();
            foreach ( $terms as $term ) {
                if ( in_array( $term->term_taxonomy_id, $_tt_ids ) ) {
                    continue;
                }

                $_tt_ids[] = $term->term_taxonomy_id;
                $_terms[] = $term;
            }
            $terms = $_terms;
        } elseif ( ! $objects ) {
            $terms = array_values( array_unique( $terms ) );
        }

        return $terms;
    }
}

if ( ! function_exists( 'br_get_cat_hierarchy' ) ) {
    /**
     * Public function to get terms by id and taxonomy
     *
     * @param array $args 'return' - type of return data, 
     * 'include_parent' = include parent to cate gories list, 'max_depth' - max depth of sub category
     * @param int $parent_id category id that will be used as parent
     * @param int $depth sub categories depth
     *
     * @return array terms
     */
    function br_get_cat_hierarchy( $args, $parent_id = 0, $depth = 0 ) {
        global $wpdb;
        if ( $args['return'] == 'string' ) {
			$cat_hierarchy = '';
		} else {
			$cat_hierarchy = array();
		}
        $taxonomy_name = 'product_cat';
        if( ! empty($args['taxonomy_name']) ) {
            $taxonomy_name = $args['taxonomy_name'];
        }

        if ( $depth > $args['max_depth'] ) {
            return '';
        }

        $query = array(
            'select' => "SELECT t.*, tt.taxonomy, ttax.count FROM $wpdb->term_taxonomy as tt",
            'join'   => "INNER JOIN $wpdb->terms as t
                INNER JOIN $wpdb->term_taxonomy as ttax",
            'where'  => "WHERE tt.term_id = t.term_id
                AND ttax.term_id = t.term_id
                AND tt.taxonomy = '{$taxonomy_name}'
                AND tt.parent = %s"
        );
        if( defined( 'WCML_VERSION' ) && defined('ICL_LANGUAGE_CODE') ) {
            $query['join'] = $query['join']." INNER JOIN {$wpdb->prefix}icl_translations as wpml_lang ON ( tt.term_id = wpml_lang.element_id )";
            $query['where'] = $query['where']." AND wpml_lang.language_code = '".ICL_LANGUAGE_CODE."' AND wpml_lang.element_type = 'tax_{$taxonomy_name}'";
        }
        $query_string = $query['select'].' '.$query['join'].' '.$query['where'];

        if( BeRocket_AAPF::$debug_mode ) {
            BeRocket_AAPF::$error_log['103_cat_hierarchy_SELECT'] = $wpdb->prepare( $query_string, $parent_id );
            $wpdb->show_errors();
        }

        $terms = $wpdb->get_results( $wpdb->prepare( $query_string, $parent_id ) );
        unset( $query_string );

        if( BeRocket_AAPF::$debug_mode ) {
            ob_start();
            if ( $wpdb->last_error ) {
                $wpdb->print_error();
            }
            BeRocket_AAPF::$error_log['000_select_status'][] = ob_get_contents();
            ob_end_clean();
        }

        if ( $terms ) {
            foreach ( $terms as $term ) {
                $term->parent = $parent_id;
                $term->slug = urldecode($term->slug);
                if ( $args['return'] == 'string' ) {
                    if ( $cat_hierarchy ) $cat_hierarchy .= ",";
                    $cat_hierarchy .= $term->term_id;

                    if ( $child = br_get_cat_hierarchy( $args, $term->term_id, $depth + 1 ) ) {
                        $cat_hierarchy .= "," . $child;
                    }
                } elseif ( $args['return'] == 'array' ) {
                    $cat_hierarchy[] = $term->term_id;

                    if ( $child = br_get_cat_hierarchy( $args, $term->term_id, $depth + 1 ) ) {
                        $cat_hierarchy = array_merge( $cat_hierarchy, $child );
                    }
                } elseif ( $args['return'] == 'hierarchy_objects' ) {
                    $term->depth = $depth;
                    $cat_hierarchy[ $term->term_id ] = $term;

                    if ( $child = br_get_cat_hierarchy( $args, $term->term_id, $depth + 1 ) ) {
                        $cat_hierarchy[ $term->term_id ]->child = $child;
                    }
                }
            }
        }

        return $cat_hierarchy;
    }
}

if ( ! function_exists( 'br_select_post_status' ) ) {
    /**
     * Public function to get string with possible post statuses for the mysql query
     *
     * @return array string
     */
    function br_select_post_status() {
        global $wpdb, $br_select_post_status;

        if ( $br_select_post_status ) {
            return $br_select_post_status;
        }

        if ( ! isset( $wpdb->posts ) ) return '1=1';

        if ( is_user_logged_in() ) {
            $br_select_post_status = "( {$wpdb->posts}.post_status='publish' OR {$wpdb->posts}.post_status='private' )";
        } else {
            $br_select_post_status = "{$wpdb->posts}.post_status='publish'";
        }

        return $br_select_post_status;
    }
}

if ( ! function_exists( 'br_where_search' ) ) {
    /**
     * Public function to get string with possible post statuses for the mysql query
     *
     * @return array string
     */
    function br_where_search( &$query = '' ) {
        $s = '';
        $has_new_function = class_exists('WC_Query') && method_exists('WC_Query', 'get_main_query') && method_exists('WC_Query', 'get_main_search_query_sql');
        if( $has_new_function ) {
            $WC_Query_get_main_query = WC_Query::get_main_query();
            $has_new_function = ! empty($WC_Query_get_main_query);
        }
        if( $has_new_function ) {
            $s = WC_Query::get_main_search_query_sql();

            if ( ! empty( $s ) ) {
                $s = ' AND ' . $s;

                if ( ! empty( $query ) ) {
                    $query['where'] .= $s;
                }
            }
        }

        return $s;
    }
}

if ( ! function_exists( 'br_filters_old_wc_compatible' ) ) {
    /**
     * Public function to get string with possible post statuses for the mysql query
     *
     * @return array string
     */
    function br_filters_old_wc_compatible( $query, $new = false ) {
        global $br_old_wp_query;
        if ( ! isset( $br_old_wp_query ) ) {
            if ( ! $new ) {
                $query      = BeRocket_AAPF::apply_user_price( $query, true );
                $query      = BeRocket_AAPF::apply_user_filters( $query, true );
                $query_vars = $query->query_vars;
            } else {
                $query_vars = array();
            }

            $query_vars[ 'posts__in' ] = apply_filters( 'loop_shop_post_in', array() );
            $br_old_wp_query           = $query_vars;
        }

        return $br_old_wp_query;
    }
}

if ( ! function_exists( 'br_filters_query' ) ) {
    function br_filters_query( $query, $for = 'price' ) {
        global $wpdb, $wp_query;

        $old_join_posts = $old_query_vars = $old_tax_query = $old_meta_query = '';
        $has_new_function = method_exists('WC_Query', 'get_main_query') && method_exists('WC_Query', 'get_main_meta_query') && method_exists('WC_Query', 'get_main_tax_query');
        if( $has_new_function ) {
            $WC_Query_get_main_query = WC_Query::get_main_query();
            $has_new_function = ! empty($WC_Query_get_main_query);
        }
        if ( ! $has_new_function ) {
            $old_query_vars = br_filters_old_wc_compatible( $wp_query );
            $old_meta_query = ( empty( $old_query_vars[ 'meta_query' ] ) || ! is_array($old_query_vars[ 'meta_query' ]) ? array() : $old_query_vars[ 'meta_query' ] );
            $old_tax_query  = ( empty( $old_query_vars[ 'tax_query' ] ) || ! is_array($old_query_vars[ 'tax_query' ]) ? array() : $old_query_vars[ 'tax_query' ] );
        } else {
            $old_query_vars = br_filters_old_wc_compatible( $wp_query, true );
        }

        if ( ! empty( $old_query_vars[ 'posts__in' ] ) ) {
            $old_join_posts = " AND {$wpdb->posts}.ID IN (" . implode( ',', $old_query_vars[ 'posts__in' ] ) . ") ";
        }

        if ( $has_new_function ) {
            $tax_query = WC_Query::get_main_tax_query();
        } else {
            $tax_query = $old_tax_query;
        }

        if ( $has_new_function ) {
            $meta_query = WC_Query::get_main_meta_query();
        } else {
            $meta_query = $old_meta_query;
        }

        $queried_object = $wp_query->get_queried_object_id();
        if ( ! empty( $queried_object ) ) {
            $query_object = $wp_query->get_queried_object();
            if ( ! empty( $query_object->taxonomy ) && ! empty( $query_object->slug ) ) {
                $tax_query[ $query_object->taxonomy ] = array(
                    'taxonomy' => $query_object->taxonomy,
                    'terms'    => array( $query_object->slug ),
                    'field'    => 'slug',
                );
            }
        }
        $meta_query = new WP_Meta_Query( $meta_query );
        $tax_query  = new WP_Tax_Query( $tax_query );

        if ( $for == 'price' ) {
            foreach ( $meta_query->queries as $mkey => $mquery ) {
                if ( isset( $mquery[ 'key' ] ) and $mquery[ 'key' ] == '_price' ) {
                    unset( $meta_query->queries[ $mkey ] );
                }
            }
        }

        $meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

        if( ! is_array($query) ) {
            $query = array('join' => '', 'where' => '');
        }

        // Generate query
        if( ! isset($query[ 'join' ]) ) {
            $query[ 'join' ] = '';
        }
        $query[ 'join' ]
            .= "
                    INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
                    INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
                    INNER JOIN {$wpdb->terms} AS terms USING( term_id )
                    " . $tax_query_sql[ 'join' ] . $meta_query_sql[ 'join' ];
        if( ! isset($query[ 'where' ]) ) {
            $query[ 'where' ] = '';
        }
        $query[ 'where' ]
            .= "
                    WHERE {$wpdb->posts}.post_type IN ( 'product' )
                    AND " . br_select_post_status() . "
                    " . $tax_query_sql[ 'where' ] . $meta_query_sql[ 'where' ] . "
                ";
        if ( defined( 'WCML_VERSION' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
            $query[ 'join' ] = $query[ 'join' ] . " INNER JOIN {$wpdb->prefix}icl_translations as wpml_lang ON ( {$wpdb->posts}.ID = wpml_lang.element_id )";
            $query[ 'where' ] = $query[ 'where' ] . " AND wpml_lang.language_code = '" . ICL_LANGUAGE_CODE . "' AND wpml_lang.element_type = 'post_product'";
        }
        br_where_search( $query );
        if ( ! empty( $post__in ) ) {
            $query[ 'where' ] .= " AND {$wpdb->posts}.ID IN (\"" . implode( '","', $post__in ) . "\")";
        }
        if( function_exists('wc_get_product_visibility_term_ids') ) {
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();
            $query[ 'where' ] .= " AND {$wpdb->posts}.ID NOT IN (\"SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id='" . $product_visibility_term_ids[ 'exclude-from-catalog' ] . "'\")";
        }

        $query[ 'where' ] .= $old_join_posts;
        //$query['group_by'] = "GROUP BY {$wpdb->posts}.ID";
        $query = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );

        return $query;
    }
}
if ( ! function_exists( 'br_fontawesome_image' ) ) {
    /**
     * Public function to upload images or select it from media library
     *
     * @param string $name - input name
     * @param string $value - current value link to image
     * @param array $additional - array with additional settings array:
     *  boolean remove_button - display button to remove image
     *  string class - additional classes for input with image value link
     *  string extra - need to add data-something="5"? use this field. It will add data as is
     *
     * @return string html code with all needed blocks and buttons
     */
    function br_fontawesome_image( $name, $value, $additional = array() ) {
        $remove_button = ( isset($additional['remove_button']) ? $additional['remove_button'] : true );
        $class = ( ( isset($additional['class']) && trim( $additional['class'] ) ) ? ' ' . trim( $additional['class'] ) : '' );
        $extra = ( ( isset($additional['extra']) && trim( $additional['extra'] ) ) ? ' ' . trim( $additional['extra'] ) : '' );
        $result = '<div class="berocket_select_fontawesome berocket_select_image">';
        $result .= berocket_fa_dark();
        $result .= '<input type="hidden" name="' . $name . '" value="' . $value . '" readonly class="berocket_image_value berocket_fa_value ' . $class . '"' . $extra . '/>
        <span class="berocket_selected_image berocket_selected_fa">' . ( empty($value) ? '' : (substr($value, 0, 3) == 'fa-' ? '<i class="fa ' . $value . '"></i>' : '<image src="' . $value . '">' ) ) . '</span>
        <input type="button" class="berocket_upload_image button" value="'.__('Upload', 'BeRocket_domain').'"/>
        <input type="button" class="berocket_select_fa button" value="'.__('Font Awesome', 'BeRocket_domain').'"/>';
        if ( $remove_button ) {
            $result .= '<input type="button" class="berocket_remove_image button" value="'.__('Remove', 'BeRocket_domain').'"/>';
        }
        $result .= '</div>';

        return $result;
    }
}

if ( ! function_exists( 'berocket_fa_dark' ) ) {
    function berocket_fa_dark () {
        global $berocket_fa_dark;
        $result = '';
        if( empty($berocket_fa_dark) ) {
            $fa_icons = fa_icons_list();
            $result = '<div class="berocket_fa_dark"><div class="berocket_fa_popup">
            <input type="text" class="berocket_fa_search"><span class="berocket_fa_close"><i class="fa fa-times"></i></span>
            <div class="berocket_fa_list">';
            foreach($fa_icons as $fa_icon) {
                $result .= '<span class="berocket_fa_icon"><span class="berocket_fa_hover"></span><span class="berocket_fa_preview"><i class="fa ' . $fa_icon . '"></i><span>' . $fa_icon . '</span></span></span>';
            }
            $result .= '</div></div></div>';
            $berocket_fa_dark = true;
        }
        return $result;
    }
}
if( ! function_exists('berocket_add_filter_to_link') ) {
    function berocket_add_filter_to_link($attribute = '', $values = array(), $operator = 'OR', $remove_attribute = FALSE) {
        if( ! is_array($values) ) {
            $values = array($values);
        }
        $options = BeRocket_AAPF::get_aapf_option();
        $option_permalink = get_option( 'berocket_permalink_option' );
        $permalink_var = $option_permalink['variable'];
        $permalink_values = explode( 'values', $option_permalink['value'] );

        $current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if( ! empty($options['nice_urls']) ) {
            if( strpos($current_url, '?') === FALSE ) {
                $url_string = $current_url;
                $query_string = '';
            } else {
                list($url_string, $query_string) = explode('?', $current_url);
            }
            if( strpos($url_string, $permalink_var) === FALSE ) {
                $filters = '';
            } else {
                list($url_string, $filters) = explode($permalink_var.'/', $url_string);
            }
            if( $filters ) {
                $regex = '#(.+?)'.preg_quote($permalink_values[0]).'(.+?)'.preg_quote($permalink_values[1]).preg_quote($option_permalink['split']).'#';
                $filters = str_replace('+', '%2B', $filters);
                $filters = urldecode( $filters );
                if( preg_match('#\/page\/(\d+)#', $filters, $page_match) ) {
                    $filters = preg_replace( '#\/page\/(\d+)#', '', $filters );
                }
                if( substr($filters, -1) == '/' ) {
                    $filters = substr($filters, 0, -1);
                }
                if( strpos(substr($filters, -2),$option_permalink['split']) === FALSE ) {
                    $filters = $filters.$option_permalink['split'];
                }
                $query_filter = '';
                $matches = array();
                preg_match_all( $regex, $filters, $matches );
                for($i = 0; $i < count($matches[1]); $i++ ) {
                    if( strlen($query_filter) > 0 ) {
                        $query_filter .= '|';
                    }
                    $query_filter .= $matches[1][$i].'['.$matches[2][$i].']';
                }
                $filters = $query_filter;
            }
        } else {
            $filters = (empty($_GET['filters']) ? '' : $_GET['filters']);
            $current_url = remove_query_arg('filters', $current_url);
            if( strpos($current_url, '?') === FALSE ) {
                $url_string = $current_url;
                $query_string = '';
            } else {
                list($url_string, $query_string) = explode('?', $current_url);
            }
        }
        if( empty($options['seo_uri_decode']) ) {
            $filters = urlencode($filters);
            $filters = str_replace('+', urlencode('+'), $filters);
            $filters = urldecode($filters);
        }
        if( substr($attribute, 0, 3) == 'pa_' ) {
            $attribute = substr($attribute, 3);
        }
        if( strpos('|'.$filters, '|'.$attribute.'[') === FALSE ) {
            $filters = ( empty($filters) ? '' : $filters.'|' ).$attribute.'['.implode(($operator == 'OR' ? '-' : '+'), $values).']';
            $filter_array = explode('|', $filters);
        } else {
            $filter_array = explode('|', $filters);
            foreach($filter_array as $filter_str_i => $filter_str) {
                if( strpos($filter_str, $attribute.'[') !== FALSE ) {
                    $filter_str = str_replace($attribute.'[', '', $filter_str);
                    $filter_str = str_replace(']', '', $filter_str);
                    $filter_values = array();
                    if( strpos($filter_str, '+') !== FALSE ) {
                        $implode = '+';
                    } elseif( strpos($filter_str, '-') !== FALSE ) {
                        $implode = '-';
                    } elseif( strpos($filter_str, '_') !== FALSE ) {
                        $implode = ($operator == 'OR' ? '-' : '+');
                        $filter_str = '';
                    } else {
                        $implode = ($operator == 'OR' ? '-' : '+');
                    }
                    if( ! empty($filter_str) ) {
                        $filter_values = explode($implode, $filter_str);
                    }
                    foreach($values as $value) {
                        if( ($search_i = array_search($value, $filter_values) ) === FALSE ) {
                            if( $remove_attribute ) {
                                $filter_values = array($value);
                            } else {
                                $filter_values[] = $value;
                            }
                        } else {
                            unset($filter_values[$search_i]);
                        }
                    }
                    if( count($filter_values) ) {
                        $filter_str = $attribute.'['.implode($implode, $filter_values).']';
                        $filter_array[$filter_str_i] = $filter_str;
                    } else {
                        unset($filter_array[$filter_str_i]);
                    }
                    break;
                }
            }
        }
        if( ! empty($options['nice_urls']) ) {
            foreach($filter_array as &$filter_str) {
                $filter_str = str_replace(array('[', ']'), $permalink_values, $filter_str);
            }
            $implode = $option_permalink['split'];
        } else {
            $implode = '|';
        }
        $filters = implode($implode, $filter_array);
        if( ! empty($options['nice_urls']) ) {
            if( ! empty($filters) ) {
                $permalink_structure = get_option('permalink_structure');
                if ( $permalink_structure ) {
                    $permalink_structure = substr($permalink_structure, -1);
                    if ( $permalink_structure == '/' ) {
                        $permalink_structure = true;
                    } else {
                        $permalink_structure = false;
                    }
                } else {
                    $permalink_structure = false;
                }
                $url_string .= (substr($url_string, -1) == '/' ? '' : '/');
                $url_string = $url_string . $option_permalink['variable'] . '/' . $filters . ($permalink_structure ? '/' : '');
            }
            if( ! empty($query_string) ) {
                $url_string .= '?' . $query_string;
            }
        } else {
            if( ! empty($query_string) ) {
                $url_string .= '?' . $query_string;
            }
            $url_string = add_query_arg('filters', $filters, $url_string);
        }
        $filters = $url_string;
        return $filters;
    }
}
