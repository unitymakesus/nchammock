<?php
class BeRocket_AAPF_paid extends BeRocket_plugin_variations {
    public $plugin_name = 'ajax_filters';
    public $version_number = 20;
    public $default_permalink = array (
        'variable' => 'filters',
        'value'    => '/values',
        'split'    => '/',
    );
    function __construct() {
        parent::__construct();
        $this->defaults = array(
            'use_links_filters'         => '',
            'nice_urls'                 => '',
            'canonicalization'          => '',
            'ub_product_count'          => '1',
            'ub_product_text'           => 'products',
            'ub_product_button_text'    => 'Show',
            'object_cache'              => '',
            'search_variation_image'    => '1',
            'slider_250_fix'            => '',
            'slider_compatibility'      => '',
            'number_style'              => array(
                'thousand_separate' => '',
                'decimal_separate'  => '.',
                'decimal_number'    => '2',
            ),
        );
        add_filter( 'berocket_filter_filter_type_array', array( $this, 'filter_type_array' ) );
        add_filter( 'berocket_aapf_single_filter_conditions_list', array( $this, 'aapf_conditions' ) );
        add_filter( 'berocket_aapf_group_filters_conditions_list', array( $this, 'aapf_conditions' ) );
        add_filter( 'aapf_localize_widget_script', array($this, 'aapf_localize_widget_script') );
        add_action( 'plugins_loaded', array($this, 'plugins_loaded') );
        add_action( 'admin_init', array($this, 'admin_init') );

        //AJAX
        add_action( 'wp_ajax_nopriv_berocket_aapf_listener_pc', array( $this, 'listener_product_count' ) );
        add_action( 'wp_ajax_berocket_aapf_listener_pc', array( $this, 'listener_product_count' ) );

        //SECTIONS
        add_filter('brfr_ajax_filters_elements_above', array($this, 'section_elements_above'), $this->version_number, 3);

        //CACHE
        add_filter( 'br_get_cache', array($this, 'br_get_cache'), 10, 3 );
        add_filter( 'br_set_cache', array($this, 'br_set_cache'), 10, 5 );
    }

    function settings_page($data) {
        $data['General']['hide_values']['items']['hide_value_button'] = array(
            "type"      => "checkbox",
            "name"      => array("hide_value", 'button'),
            "value"     => '1',
            'label_for'  => __('Hide "Show/Hide value(s)" button', 'BeRocket_AJAX_domain'),
        );
        $data['General'] = berocket_insert_to_array(
            $data['General'],
            'recount_products',
            array(
                'object_cache' => array(
                    "label"     => __( 'Data cache', "BeRocket_AJAX_domain" ),
                    "name"     => "object_cache",   
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => '', 'text' => __('Disable', 'BeRocket_AJAX_domain')),
                        array('value' => 'wordpress', 'text' => __('WordPress Cache', 'BeRocket_AJAX_domain')),
                        array('value' => 'persistent', 'text' => __('Persistent Cache Plugins', 'BeRocket_AJAX_domain')),
                    ),
                    "value"    => '',
                ),
                'thousand_separate' => array(
                    "label"     => __( 'Thousand Separator', "BeRocket_AJAX_domain" ),
                    "type"      => "text",
                    "name"      => array('number_style', 'thousand_separate'),
                    "value"     => $this->defaults["number_style"]["thousand_separate"],
                ),
                'decimal_separate' => array(
                    "label"     => __( 'Decimal Separator', "BeRocket_AJAX_domain" ),
                    "type"      => "text",
                    "name"      => array('number_style', 'decimal_separate'),
                    "value"     => $this->defaults["number_style"]["decimal_separate"],
                ),
                'decimal_number' => array(
                    "label"     => __( 'Number Of Decimal', "BeRocket_AJAX_domain" ),
                    "type"      => "number",
                    "name"      => array('number_style', 'decimal_number'),
                    "value"     => $this->defaults["number_style"]["decimal_number"],
                ),
            )
        );
        $data['SEO'] = berocket_insert_to_array(
            $data['SEO'],
            'seo_friendly_urls',
            array(
                'use_links_filters' => array(
                    "label"     => __( 'Use links in checkbox and radio filters', "BeRocket_AJAX_domain" ),
                    "type"      => "checkbox",
                    "name"      => "use_links_filters",
                    "value"     => '1',
                    "class"     => "berocket_use_links_filters",
                ),
            )
        );
        $data['SEO'] = berocket_insert_to_array(
            $data['SEO'],
            'slug_urls',
            array(
                'nice_urls' => array(
                    "label"     => __( 'Nice URL', "BeRocket_AJAX_domain" ),
                    "type"      => "checkbox",
                    "name"      => "nice_urls",
                    "value"     => '1',
                    'class'     => 'berocket_nice_url',
                    'label_for' => __("Works only with SEO friendly urls. WordPress permalinks must be set to Post name(Custom structure: /%postname%/ )", 'BeRocket_AJAX_domain'),
                ),
                'canonicalization' => array(
                    "label"     => __( 'Canonicalization', "BeRocket_AJAX_domain" ),
                    "type"      => "checkbox",
                    "name"      => "canonicalization",
                    "value"     => '1',
                    'class'     => 'berocket_canonicalization',
                    'label_for' => __("Use canonical tag on WooCommerce pages", 'BeRocket_AJAX_domain'),
                ),
            )
        );
        $data['Elements']['elements_position_hook']['label'] = __( 'Elements position', "BeRocket_AJAX_domain" );
        $data['Elements']['ub_product_count'] = array(
            "label"     => __( 'Show products count before filtering', "BeRocket_AJAX_domain" ),
            'items' => array(
                'ub_product_count' => array(
                    "type"      => "checkbox",
                    "name"      => "ub_product_count",
                    "value"     => '1',
                    'label_for'  => __("Show products count before filtering, when using update button", 'BeRocket_AJAX_domain') . '<br>',
                ),
                'ub_product_text' => array(
                    "type"      => "text",
                    "name"      => "ub_product_text",
                    "value"     => $this->defaults["ub_product_text"],
                    'label_for'  => __("Text that means products", 'BeRocket_AJAX_domain') . '<br>',
                ),
                'ub_product_button_text' => array(
                    "type"      => "text",
                    "name"      => "ub_product_button_text",
                    "value"     => $this->defaults["ub_product_button_text"],
                    'label_for'  => __("Text for show button", 'BeRocket_AJAX_domain') . '<br>',
                ),
            )
        );
        $data['Elements']['elements_above'] = array(
            "section"   => "elements_above",
            "value"     => "",
        );
        $data['Advanced'] = berocket_insert_to_array(
            $data['Advanced'],
            'out_of_stock_variable',
            array(
                'search_variation_image' => array(
                    "label"     => __( 'Display variation image', "BeRocket_AJAX_domain" ),
                    "type"      => "checkbox",
                    "name"      => "search_variation_image",
                    "value"     => '1',
                    'label_for' => __('Display variation image instead variable image on filtering', 'BeRocket_AJAX_domain'),
                ),
            )
        );
        $data['Advanced'] = berocket_insert_to_array(
            $data['Advanced'],
            'use_get_query',
            array(
                'slider_250_fix' => array(
                    "label"     => __( 'Slider has a lot of values', "BeRocket_AJAX_domain" ),
                    "type"      => "checkbox",
                    "name"      => "slider_250_fix",
                    "value"     => '1',
                    'label_for' => __('Enable it if slider has more than 250 values. Hierarchical taxonomy can work incorrect with sliders', 'BeRocket_AJAX_domain'),
                ),
                'slider_compatibility' => array(
                    "label"     => __( 'Old slider compatibility', "BeRocket_AJAX_domain" ),
                    "type"      => "checkbox",
                    "name"      => "slider_compatibility",
                    "value"     => '1',
                    'label_for' => __('Enable it only if you have some problem with slider filters', 'BeRocket_AJAX_domain'),
                ),
            )
        );
        $data['Advanced'] = berocket_insert_to_array(
            $data['Advanced'],
            'disable_font_awesome',
            array(
                'use_filtered_variation' => array(
                    "label"     => __( 'Use filtered variation link and session', "BeRocket_AJAX_domain" ),
                    "type"      => "checkbox",
                    "name"      => "use_filtered_variation",
                    "value"     => '1',
                ),
                'use_filtered_variation_once' => array(
                    "label"     => __( 'Use filtered variation only after search', "BeRocket_AJAX_domain" ),
                    "type"      => "checkbox",
                    "name"      => "use_filtered_variation_once",
                    "value"     => '1',
                ),
            )
        );
        return $data;
    }
    function section_elements_above ( $item, $options ) {
        $html = '<tr>
            <th scope="row">' . __('Elements above products', 'BeRocket_AJAX_domain') . '</th>
            <td>';
                $posts_args = array(
                    'posts_per_page'   => -1,
                    'offset'           => 0,
                    'category'         => '',
                    'category_name'    => '',
                    'include'          => '',
                    'exclude'          => '',
                    'meta_key'         => '',
                    'meta_value'       => '',
                    'post_type'        => 'br_filters_group',
                    'post_mime_type'   => '',
                    'post_parent'      => '',
                    'author'           => '',
                    'post_status'      => 'publish',
                    'fields'           => 'ids',
                    'suppress_filters' => false 
                );
                $posts_array = new WP_Query($posts_args);
                $br_filters_group = $posts_array->posts;
                $html .= '<div>' . __('Group', 'BeRocket_AJAX_domain') . '<select>';
                foreach($br_filters_group as $post_id) {
                    $html .= '<option data-name="' . get_the_title($post_id) . '" value="' . $post_id . '">' . get_the_title($post_id) . ' (ID:' . $post_id . ')</option>';
                }
                $html .= '</select><button class="button berocket_elements_above_group" type="button">'.__('Add group', 'BeRocket_AJAX_domain').'</button></div>';
                $html .= '<ul class="berocket_elements_above_products">';
                if( is_array(br_get_value_from_array($options, 'elements_above_products')) ) {
                    foreach($options['elements_above_products'] as $post_id) {
                        $post_type = get_post_type($post_id);
                        $html .= '<li class="berocket_elements_added_' . $post_id . '"><fa class="fa fa-bars"></fa>
                            <input type="hidden" name="br_filters_options[elements_above_products][]" value="' . $post_id . '">
                            ' . get_the_title($post_id) . ' (ID:' . $post_id . ')
                            <i class="fa fa-times"></i>
                        </li>';
                    }
                }
                $html .= '</ul>';
                wp_enqueue_script('jquery-color');
                wp_enqueue_script('jquery-ui-sortable');
                $html .= "<script>
                    jQuery(document).on('click', '.berocket_elements_above_group', function(event) {
                        event.preventDefault();
                        var selected = jQuery(this).prev().find(':selected');
                        post_id = selected.val();
                        post_title = selected.text();
                        if( ! jQuery('.berocket_elements_added_'+post_id).length ) {
                            var html = '<li class=\"berocket_elements_added_'+post_id+'\"><fa class=\"fa fa-bars\"></fa>';
                            html += '<input type=\"hidden\" name=\"br_filters_options[elements_above_products][]\" value=\"'+post_id+'\">';
                            html += post_title;
                            html += '<i class=\"fa fa-times\"></i></li>';
                            jQuery('.berocket_elements_above_products').append(jQuery(html));
                        } else {
                            jQuery('.berocket_elements_added_'+post_id).css('background-color', '#ee3333').clearQueue().animate({backgroundColor:'#eeeeee'}, 1000);
                        }
                    });
                    jQuery(document).on('click', '.berocket_elements_above_products .fa-times', function(event) {
                        jQuery(this).parents('li').first().remove();
                    });
                    jQuery(document).ready(function() {
                        if(typeof(jQuery( \".berocket_elements_above_products\" ).sortable) == 'function') {
                            jQuery( \".berocket_elements_above_products\" ).sortable({axis:\"y\", handle:\".fa-bars\", placeholder: \"berocket_sortable_space\"});
                        }
                    });
                </script>
<style>
.berocket_elements_above_products li {
font-size: 2em;
border: 2px solid rgb(153, 153, 153);
background-color: rgb(238, 238, 238);
padding: 5px;
line-height: 1.1em;
}
.berocket_elements_above_products li .fa-bars {
margin-right: 0.5em;
cursor: move;
}
.berocket_elements_above_products small {
font-size: 0.5em;
line-height: 2em;
vertical-align: middle;
}
.berocket_elements_above_products li .fa-times {
margin-left: 0.5em;
cursor: pointer;
float: right;
}
.berocket_elements_above_products li .fa-times:hover {
color: black;
}
.berocket_elements_above_products .berocket_edit_filter {
vertical-align: middle;
font-size: 0.5em;
float: right;
line-height: 2em;
height: 2em;
display: inline-block;
}
.berocket_elements_above_products .berocket_sortable_space {
border: 2px dashed #aaa;
background: white;
font-size: 2em;
height: 1.1em;
box-sizing: content-box;
padding: 5px;
}
</style>
            </td>
        </tr>";
        return $html;
    }
    function filter_type_array($filter_type_array) {
        $filter_type_array = berocket_insert_to_array($filter_type_array, 'product_cat', array('custom_product_cat' => array(
            'name' => __('Product Category', 'BeRocket_AJAX_domain'),
            'sameas' => 'custom_taxonomy',
            'attribute' => 'product_cat',
        )), true);
        return $filter_type_array;
    }
    function aapf_conditions($conditions) {
        $conditions[] = 'condition_page_woo_attribute';
        $conditions[] = 'condition_page_woo_search';
        $conditions[] = 'condition_page_woo_category';
        return $conditions;
    }
    function aapf_localize_widget_script($localize) {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        $permalink_values = explode( 'values', $option_permalink['value'] );
        $localize['ub_product_count']           = ( empty($options['ub_product_count']) ? '' : $options['ub_product_count'] );
        $localize['ub_product_text']            = ( empty($options['ub_product_text']) ? '' : $options['ub_product_text'] );
        $localize['ub_product_button_text']     = ( empty($options['ub_product_button_text']) ? '' : $options['ub_product_button_text'] );
        $localize['number_style']               = array(
            ( empty($options['number_style']['thousand_separate'])  ? '' : $options['number_style']['thousand_separate'] ), 
            ( empty($options['number_style']['decimal_separate'])   ? '' : $options['number_style']['decimal_separate']  ), 
            ( empty($options['number_style']['decimal_number'])     ? '' : $options['number_style']['decimal_number']    )
        );
        $localize['hide_button_value']          = ( empty($options['hide_value']['button']) ? '' : $options['hide_value']['button'] );
        $localize['nice_urls']                  = ( empty($options['nice_urls']) ? '' : $options['nice_urls'] );
        $localize['nice_url_variable']          = $option_permalink['variable'];
        $localize['nice_url_value_1']           = $permalink_values[0];
        $localize['nice_url_value_2']           = $permalink_values[1];
        $localize['nice_url_split']             = $option_permalink['split'];
        return $localize;
    }
    function plugins_loaded() {
        $BeRocket_AAPF_group_filters = BeRocket_AAPF_group_filters::getInstance();
        $BeRocket_AAPF_group_filters->add_meta_box('search_box', __( 'Search Box', 'BeRocket_AJAX_domain' ), array($this, 'search_box'));
        $this->global_settings();
        $this->group_add();
        $this->filter_add();
    }
    function admin_init() {
        $admin_js = 'berocket_admin_filter_types_by_attr.ranges = "<option value="ranges">Ranges</option>";
        berocket_admin_filter_types.custom_taxonomy.push("slider");
        berocket_admin_filter_types.attribute.push("slider");
        berocket_admin_filter_types.filter_by.push("slider");
        berocket_admin_filter_types.price.push("ranges");
        ';
        wp_add_inline_script('berocket_aapf_widget-admin', $admin_js);
    }
    function search_box($post) {
        $BeRocket_AAPF_group_filters = BeRocket_AAPF_group_filters::getInstance();
        wp_enqueue_script('jquery-ui-sortable');
        $filters = $BeRocket_AAPF_group_filters->get_option($post->ID);
        $post_name = $BeRocket_AAPF_group_filters->post_name;
        $categories = BeRocket_AAPF_Widget::get_product_categories( @ json_decode( $instance['product_cat'] ) );
        $categories = BeRocket_AAPF_Widget::set_terms_on_same_level( $categories );
        include AAPF_TEMPLATE_PATH . "paid/filters_search_box.php";
    }
    //GLOBAL SETTINGS
    function global_settings() {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $option = $BeRocket_AAPF->get_option();
        if( is_array(br_get_value_from_array($option, 'elements_above_products')) && count($option['elements_above_products']) ) {
            add_action ( br_get_value_from_array($option, 'elements_position_hook', 'woocommerce_archive_description'), array($this, 'elements_above_products'), 1 );
        }
        if ( ! empty( $option['nice_urls'] ) ) {
            add_action( 'init', array( $this, 'nice_url_init' ) );

            add_filter( 'rewrite_rules_array', array( $this, 'add_rewrite_rules' ), 999999999 );
            add_filter( 'query_vars', array( $this, 'add_queryvars' ) );
            add_filter( 'berocket_aapf_current_page_url', array($this, 'current_page_url'), 10, 2 );
            add_filter( 'berocket_aapf_is_filtered_page_check', array($this, 'is_filtered_with_nice_url'), 10, 3 );
            add_action( 'br_aapf_args_converter_before', array($this, 'br_aapf_args_converter'), 10, 1 );
            add_filter( 'berocket_add_filter_to_link_explode', array($this, 'add_filter_to_link_explode') );
            add_filter( 'berocket_add_filter_to_link_filters_str', array($this, 'add_filter_to_link_filters_str') );
            add_filter( 'berocket_add_filter_to_link_implode', array($this, 'add_filter_to_link_implode') );
        }
        if( ! empty( $option['use_links_filters'] ) ) {
            add_filter('berocket_check_radio_color_filter_term_text', array($this, 'check_radio_color_filter_term_text'), 10, 4);
        }
        if( ! empty($option['canonicalization']) ) {
            add_action('wp_head', array($this, 'wp_head_canonical'));
        }
        if( ! empty($option['search_variation_image']) ) {
            include_once( dirname( __FILE__ ) . '/paid/woocommerce-variation-image.php' );
        }
        if( ! empty($option['use_filtered_variation']) || ! empty($option['use_filtered_variation_once']) ) {
            add_filter( 'woocommerce_loop_product_link', array( $this, 'woocommerce_loop_product_link' ), 1, 2 );
        }
        if( ! empty($option['use_filtered_variation']) && ! is_admin() ) {            
            if(!session_id()) {
                session_start();
            }
            add_action( 'wp_head', array( $this, 'wp_head' ) );
        }
        add_filter('berocket_aapf_convert_limits_to_tax_query', array($this, 'convert_limits_to_tax_query'));
        if( empty($options['slider_compatibility']) ) {
            add_filter( 'berocket_aapf_filters_on_page_load', array($this, 'convert_limits_to_tax_query') );
        } else {
            add_filter( 'loop_shop_post_in', array( $this, 'limits_filter' ) );
            add_filter( 'berocket_aapf_limits_filter_function', array( $this, 'limits_filter' ) );
        }
        add_filter( 'loop_shop_post_in', array( $this, 'add_terms' ), 900 );
        add_action( 'berocket_aapf_wizard_attribute_count_hide_values', array( $this, 'wizard_attribute_count_hide_values' ), 10, 1 );
    }
    function wizard_attribute_count_hide_values($option) {
        ?>
        <div><label><input name="berocket_aapf_wizard_settings[hide_value][button]" class="attribute_count_preset_16" type="checkbox" value="1"
        <?php if( ! empty($option['hide_value']['button']) ) echo " checked"; ?>>
        <?php _e('Hide "Show/Hide value(s)" button', 'BeRocket_AJAX_domain') ?>
        </label></div>
        <?php
    }
    function elements_above_products() {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        $elements_above_products = br_get_value_from_array($options, 'elements_above_products');
        if( ! is_array($elements_above_products) ) {
            $elements_above_products = array();
        }
        foreach($elements_above_products as $element_above_products) {
            echo '<div class="berocket_element_above_products">';
            the_widget( 'BeRocket_new_AAPF_Widget', array('group_id' => $element_above_products));
            echo '</div>';
        }
    }
    function convert_limits_to_tax_query($args) {
        if ( ! empty($_POST['price_ranges']) ) {
            if ( ! isset( $args['meta_query'] ) ) {
                $args['meta_query'] = array();
            }
            $price_range_query = array( 'relation' => 'OR' );
            foreach ( $_POST['price_ranges'] as $range ) {
                $range = explode( '*', $range );
                $price_range_query[] = array( 'key' => '_price', 'compare' => 'BETWEEN', 'type' => 'NUMERIC', 'value' => array( ($range[0] - 1), $range[1] ) );
            }
            $args['meta_query'][] = $price_range_query;
        }
        if( empty($_POST['limits']) ) {
            return $args;
        }
        $limits = $_POST['limits'];
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        $tax_query = (! isset($args['tax_query']) || ! is_array($args['tax_query']) ? array() : $args['tax_query']);
        if ( ! empty($limits) ) {

            foreach ( $limits as $v ) {
                if( $v[0] == 'pa__date' ) {
                    $from = date('Y-m-d 00:00:00', strtotime($v[1]));
                    $to = date('Y-m-d 23:59:59', strtotime($v[2]));
                    $args['date_query'] = array(
                        'after' => $from,
                        'before' => $to,
                    );
                    continue;
                }
                $v[1] = urldecode( $v[1] );
                $v[2] = urldecode( $v[2] );
                $all_terms_name = array();
                $all_terms_slug = array();
                $terms = get_terms( $v[0] );
                $is_numeric = true;
                $is_with_string = false;
                if( is_wp_error ( $all_terms_name ) ) {
                    BeRocket_updater::$error_log[] = $all_terms_name->errors;
                }
                if( ! is_numeric($v[1]) || ! is_numeric($v[2]) ) {
                    $is_with_string = true;
                }
                foreach ( $terms as $term ) {
                    if( ! is_numeric( substr( $term->name[0], 0, 1 ) ) ) {
                        $is_numeric = false;
                    }
                    if( ! is_numeric( $term->name ) ) {
                        $is_with_string = true;
                    }
                    array_push( $all_terms_name, $term->slug );
                    array_push( $all_terms_slug, $term->name );
                }
                if( $is_numeric ) {
                    array_multisort( $all_terms_slug, SORT_NUMERIC, $all_terms_name, $all_terms_slug );
                } else {
                    array_multisort( $all_terms_name, $all_terms_name, $all_terms_slug );
                }
                $taxonomy_terms = get_terms(array('fields' => 'id=>slug', 'taxonomy' => $v[0]));
                if( $is_with_string ) {
                    $start_terms    = array_search( $v[1], $all_terms_name );
                    $end_terms      = array_search( $v[2], $all_terms_name );
                    $all_terms_name = array_slice( $all_terms_name, $start_terms, ( $end_terms - $start_terms + 1 ) );
                    $search = $all_terms_name;
                } else {
                    $start_terms = false;
                    $end_terms = false;
                    $previous_pos = false;
                    $search = array();
                    foreach($all_terms_slug as $term_pos => $term) {
                        if( $term >= $v[1] && $start_terms === false ) {
                            $start_terms = $term_pos;
                        }
                        if( $end_terms === false ) {
                            if( $term > $v[2] ) {
                                if( $previous_pos !== false ) {
                                    $end_terms = $previous_pos;
                                }
                            } elseif( $term == $v[2] ) {
                                $end_terms = $term_pos;
                            }
                        }
                        $previous_pos = $term_pos;
                    }
                    if( $start_terms > $end_terms ) {
                        $search = array();
                    } elseif( $v[1] > $v[2] ) {
                        $search = array();
                    } else {
                        $search = array_slice( $all_terms_name, $start_terms, ( $end_terms - $start_terms + 1 ) );
                    }
                }
                $ids_array = array();
                foreach($search as $search_el) {
                    $id = array_search($search_el, $taxonomy_terms);
                    if( $id !== FALSE ) {
                        $ids_array[] = $id;
                    }
                }
                if( empty($_POST['limits_arr']) ) {
                    $_POST['limits_arr'] = array();
                }
                $_POST['limits_arr'][$v[0]] = $ids_array;
                if( ! empty($options['slider_250_fix']) ) {
                    $args_send = array();
                    if( ! empty($ids_array) && is_array($ids_array) && count($ids_array) ) {
                        $args_send = apply_filters('berocket_aapf_tax_query_attribute', array(
                            'taxonomy'          => $v[0],
                            'field'             => 'id',
                            'terms'             => $ids_array,
                            'operator'          => 'IN',
                            'include_children'  => false,
                            'is_berocket'       => true
                        ));
                    }
                } else {
                    $args_send = array('relation' => 'OR');
                    foreach($ids_array as $id) {
                        $args_send[] = apply_filters('berocket_aapf_tax_query_attribute', array(
                            'taxonomy'          => $v[0],
                            'field'             => 'id',
                            'terms'             => $id,
                            'operator'          => 'IN',
                            'include_children'  => false,
                            'is_berocket'       => true
                        ));
                    }
                }
                $tax_query['relation'] = 'AND';
                $tax_query[] = $args_send;
                unset($search);
            }
        }
        $args['tax_query'] = $tax_query;
        return $args;
    }
    function limits_filter( $filtered_posts ) {
        if( empty($_POST['limits']) ) {
            return $filtered_posts;
        }
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $option = $BeRocket_AAPF->get_option();
        global $wpdb;

        if ( ! empty($_POST['limits']) ) {
            $matched_products = false;

            foreach ( $_POST['limits'] as $v ) {
                if( $v[0] == 'pa__date' ) {
                    $from = date('Y-m-d 00:00:00', strtotime($v[1]));
                    $to = date('Y-m-d 23:59:59', strtotime($v[2]));
                    $query_string = "
                        SELECT DISTINCT ID, post_parent, post_type FROM $wpdb->posts
                        WHERE ( post_type = 'product' OR post_type = 'product_variation') AND post_status = 'publish'
                        AND post_modified >= '{$from}' AND post_modified <= '{$to}'";
                    $matched_products_query = $wpdb->get_results( $query_string, OBJECT_K );
                    $matched_products = array( 0 );
                    foreach ( $matched_products_query as $product ) {
                        if( $product->post_type == 'product' ) {
                            $matched_products[] = $product->ID;
                        }
                        if ( $product->post_parent > 0  ) {
                            $matched_products[] = $product->post_parent;
                        }
                    }
                    continue;
                }
                $v[1] = urldecode( $v[1] );
                $v[2] = urldecode( $v[2] );
                $all_terms_name = array();
                $all_terms_slug = array();
                $terms = get_terms( $v[0] );
                $is_numeric = true;
                $is_with_string = false;
                if( is_wp_error ( $all_terms_name ) ) {
                    BeRocket_updater::$error_log[] = $all_terms_name->errors;
                }
                if( ! is_numeric($v[1]) || ! is_numeric($v[2]) ) {
                    $is_with_string = true;
                }
                foreach ( $terms as $term ) {
                    if( ! is_numeric( substr( $term->name[0], 0, 1 ) ) ) {
                        $is_numeric = false;
                    }
                    if( ! is_numeric( $term->name ) ) {
                        $is_with_string = true;
                    }
                    array_push( $all_terms_name, $term->slug );
                    array_push( $all_terms_slug, $term->name );
                }
                if( $is_with_string ) {
                    if( $is_numeric ) {
                        array_multisort( $all_terms_slug, SORT_NUMERIC, $all_terms_name, $all_terms_slug );
                    } else {
                        array_multisort( $all_terms_name, $all_terms_name, $all_terms_slug );
                    }
                    $start_terms    = array_search( $v[1], $all_terms_name );
                    $end_terms      = array_search( $v[2], $all_terms_name );
                    $all_terms_name = array_slice( $all_terms_name, $start_terms, ( $end_terms - $start_terms + 1 ) );
                    $all_terms_name_text = implode( "','", $all_terms_name );
                    $all_terms_name_text = str_replace( '%', '%%', $all_terms_name_text );
                    $CAST           = "t.slug IN ('" . $all_terms_name_text . "')";
                } else {
                    $CAST = "t.name BETWEEN %f AND %f";
                }

                $query_string = "
                    SELECT DISTINCT ID, post_parent, cast(t.name as decimal) FROM $wpdb->posts
                    INNER JOIN $wpdb->term_relationships as tr ON ID = tr.object_id
                    INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
                    INNER JOIN $wpdb->terms as t ON t.term_id = tt.term_id
                    WHERE post_type = 'product' AND post_status = 'publish'
                    AND tt.taxonomy = %s AND " . $CAST . "
                ";

                $matched_products_query = $wpdb->get_results( $wpdb->prepare( $query_string, $v[0], $v[1], $v[2] ), OBJECT_K );
                unset( $query_string );

                if ( $matched_products_query ) {
                    if ( $matched_products === false ) {
                        $matched_products = array( 0 );
                        foreach ( $matched_products_query as $product ) {
                            $matched_products[] = $product->ID;
                            // TODO: probably this is not needed as this is for product_variation, not sure
                            if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) ) {
                                $matched_products[] = $product->post_parent;
                            }
                        }
                    } else {
                        $new_products = array( 0 );
                        foreach ( $matched_products_query as $product ) {
                            if ( in_array( $product->ID, $matched_products ) ) {
                                $new_products[] = $product->ID;
                                // TODO: probably this is not needed as this is for product_variation, not sure
                                if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $new_products ) ) {
                                    $new_products[] = $product->post_parent;
                                }
                            }
                        }
                        $matched_products = $new_products;
                        unset( $new_products );
                    }
                    unset( $matched_products_query );
                }

                $matched_product_variations_query = $wpdb->get_results( $wpdb->prepare( "
                    SELECT DISTINCT ID, post_parent, t.name as t_name_num FROM $wpdb->posts
                    INNER JOIN $wpdb->term_relationships as tr ON ID = tr.object_id
                    INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
                    INNER JOIN $wpdb->terms as t ON t.term_id = tt.term_id
                    WHERE post_type = 'product_variation' AND post_status = 'publish'
                    AND tt.taxonomy = %s AND " . $CAST . "
                ", $v[0], $v[1], $v[2] ), OBJECT_K );

                if ( $matched_product_variations_query ) {
                    if ( $matched_products === false ) {
                        $matched_products = array( 0 );
                        foreach ( $matched_product_variations_query as $product ) {
                            if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) ) {
                                $matched_products[] = $product->post_parent;
                            }
                        }
                    } else {
                        $new_products = array( 0 );
                        foreach ( $matched_product_variations_query as $product ) {
                            if ( in_array( $product->ID, $matched_products ) ) {
                                if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $new_products ) ) {
                                    $new_products[] = $product->post_parent;
                                }
                            }
                        }
                        $matched_products = $new_products;
                        unset( $new_products );
                    }
                    unset( $matched_product_variations_query );
                }
            }

            if ( $matched_products === false ) {
                $matched_products = array( 0 );
            } else {
                // TODO: need to remove array_unique and check if unique in the loop
                if( ! empty($matched_products) && is_array($matched_products) ) {
                    $matched_products = array_unique( $matched_products );
                }
            }

            // Filter the id's
            if ( sizeof( $filtered_posts ) == 0 ) {
                $filtered_posts = $matched_products;
            } else {
                // TODO: need to remove array_intersect and check if intersect in the loop
                $filtered_posts = array_intersect( $filtered_posts, $matched_products );
            }
        }

        return (array) $filtered_posts;
    }
    //NICE URL
    function nice_url_init () {
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        add_rewrite_endpoint($option_permalink['variable'], EP_PERMALINK|EP_SEARCH|EP_CATEGORIES|EP_TAGS|EP_PAGES);
        flush_rewrite_rules();
    }
    function add_rewrite_rules ( $rules ) {
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        $values_split = $option_permalink['value'];
        $values_split = explode( 'values', $values_split );
        $newrules = array();
        $shop_id = wc_get_page_id('shop');
        $shop_page_slug = _x( 'shop', 'default-slug', 'woocommerce' );
        if( ! empty($shop_id) ) {
            $shop_slug = get_post($shop_id);
            $newrules[$option_permalink['variable'].'/(.*)/?'] = 'index.php?post_type=product&'.$option_permalink['variable'].'=$matches[1]';
            if ( ! empty( $shop_slug ) and is_object( $shop_slug ) and ! empty( $shop_slug->post_name ) ) {
                $shop_page_slug = $shop_slug->post_name;
            }
        }

        if ( br_get_woocommerce_version() >= 2.7 ) {
            $newrules[ $shop_page_slug . '/' . $option_permalink[ 'variable' ] . '/(.*)/?' ] = 'index.php?post_type=product&' . $option_permalink[ 'variable' ] . '=$matches[1]';
        } else {
            $newrules[ $shop_page_slug . '/' . $option_permalink[ 'variable' ] . '/(.*)/?' ] = 'index.php?pagename=' . $shop_slug->post_name . '&' . $option_permalink[ 'variable' ] . '=$matches[1]';
        }

        $category_base = get_option( 'woocommerce_permalinks' );
        $tag_base = $category_base['tag_base'];
        $category_base = $category_base['category_base'];

        if ( empty($category_base) ) {
            $category_base = _x( 'product-category', 'slug', 'woocommerce' );
        }
        $newrules[$category_base.'/(.+?)/'.$option_permalink['variable'].'/(.*)/?'] = 'index.php?product_cat=$matches[1]&'.$option_permalink['variable'].'=$matches[2]';

        if ( empty($tag_base) ) {
            $tag_base = _x( 'product-tag', 'slug', 'woocommerce' );
        }
        $newrules[$tag_base.'/([^/]+)/'.$option_permalink['variable'].'/(.*)/?'] = 'index.php?product_tag=$matches[1]&'.$option_permalink['variable'].'=$matches[2]';

        $product_taxonomies = get_object_taxonomies('product');
        
        $product_taxonomies = array_diff($product_taxonomies, array('product_type', 'product_visibility', 'product_cat', 'product_tag', 'product_shipping_class'));
        foreach($product_taxonomies as $product_taxonomy) {
            $product_taxonomy = get_taxonomy($product_taxonomy);
            if( ! empty($product_taxonomy->public) ) {
                if( ! empty($product_taxonomy->rewrite) && ! empty($product_taxonomy->rewrite['slug']) ) {
                    $taxonomy_base = $product_taxonomy->rewrite['slug'];
                } else {
                    $taxonomy_base = $product_taxonomy->name;
                }
                if( $taxonomy_base[0] == '/' ) {
                    $taxonomy_base = substr($taxonomy_base, 1);
                }
                $newrules[$taxonomy_base.'/([^/]+)/'.$option_permalink['variable'].'/(.*)/?'] = 'index.php?'.$product_taxonomy->name.'=$matches[1]&'.$option_permalink['variable'].'=$matches[2]';
            }
        }

        return $newrules + $rules;
    }
    function add_queryvars( $query_vars ) {
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        $query_vars[] = $option_permalink['variable'];
        return $query_vars;
    }
    function current_page_url($current_page_url, $br_options) {
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        $permalink_values = explode( 'values', $option_permalink['value'] );
        $current_page_url = preg_replace( "~".$option_permalink['variable']."/.+~", "", $current_page_url );
        $current_page_url = preg_replace( "~".urlencode($option_permalink['variable'])."/.+~", "", $current_page_url );
        return $current_page_url;
    }
    function is_filtered_with_nice_url($check, $func, $query = false) {
        if( $query === false ) {
            global $wp_query;
            $query = $wp_query;
        }
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        $check = ( $check || $query->get( $option_permalink['variable'], '' ) );
        return $check;
    }
    function br_aapf_args_converter($query) {
        global $wp_rewrite;
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        if( $query->get( $option_permalink['variable'], '' ) ) {
            $values_split = $option_permalink['value'];
            $values_split = explode( 'values', $values_split );
            $regex = '#(.+?)'.preg_quote($values_split[0]).'(.+?)'.preg_quote($values_split[1].$option_permalink['split']).'#';
            $filters = $query->get( $option_permalink['variable'], '' );
            $filters = str_replace('+', '%2B', $filters);
            $filters = urldecode( $filters );
            
            if( preg_match('#\/'.$wp_rewrite->pagination_base.'\/(\d+)#', $filters, $page_match) ) {
                $filters = preg_replace( '#\/'.$wp_rewrite->pagination_base.'\/(\d+)#', '', $filters );
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
    }
    function add_filter_to_link_explode($vars) {
        global $wp_rewrite;
        list($url_string, $query_string, $filters) = $vars;
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        $permalink_var = $option_permalink['variable'];
        $permalink_values = explode( 'values', $option_permalink['value'] );
        if( strpos($url_string, $permalink_var) === FALSE ) {
            $filters = '';
        } else {
            list($url_string, $filters) = explode($permalink_var.'/', $url_string);
        }
        if( $filters ) {
            $regex = '#(.+?)'.preg_quote($permalink_values[0]).'(.+?)'.preg_quote($permalink_values[1]).preg_quote($option_permalink['split']).'#';
            $filters = str_replace('+', '%2B', $filters);
            $filters = urldecode( $filters );
            if( preg_match('#\/'.$wp_rewrite->pagination_base.'\/(\d+)#', $filters, $page_match) ) {
                $filters = preg_replace( '#\/'.$wp_rewrite->pagination_base.'\/(\d+)#', '', $filters );
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
        return array($url_string, $query_string, $filters);
    }
    function add_filter_to_link_filters_str($vars) {
        list($filter_array, $implode) = $vars;
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        $permalink_var = $option_permalink['variable'];
        $permalink_values = explode( 'values', $option_permalink['value'] );
        foreach($filter_array as &$filter_str) {
            $filter_str = str_replace(array('[', ']'), $permalink_values, $filter_str);
        }
        $implode = $option_permalink['split'];
        return array($filter_array, $implode);
    }
    function add_filter_to_link_implode($vars) {
        list($url_string, $query_string, $filters) = $vars;
        $option_permalink = get_option( 'berocket_permalink_option' );
        $option_permalink = array_merge($this->default_permalink, $option_permalink);
        $permalink_var = $option_permalink['variable'];
        $permalink_values = explode( 'values', $option_permalink['value'] );
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
            $filters = '';
        }
        return array($url_string, $query_string, $filters);
    }
    //REPLACE LINK FOR VARIABLE PRODUCTS
    public function woocommerce_loop_product_link($link, $product) {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        global $berocket_filters_session;
        if( $product->is_type('variable') && ! empty($berocket_filters_session) ) {
            if( ! empty($berocket_filters_session['terms']) ) {
                $filter_attribute = $BeRocket_AAPF->get_attribute_for_variation_link($product, $berocket_filters_session['terms']);
                foreach($filter_attribute as $attribute_name => $attribute_val) {
                    $link = add_query_arg('attribute_'.$attribute_name, $attribute_val, $link);
                }
            }
        }
        return $link;
    }
    public function wp_head() {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        if( ! empty($_SESSION['BeRocket_filters']) && is_product()) {
            $product_id = get_the_ID();
            $product = wc_get_product($product_id);
            if( $product->is_type('variable') ) {
                if( ! empty($_SESSION['BeRocket_filters']['terms']) ) {
                    $filter_attribute = $BeRocket_AAPF->get_attribute_for_variation_link($product, $_SESSION['BeRocket_filters']['terms']);
                    foreach($filter_attribute as $attribute_name => $attribute_val) {
                        if( empty($_REQUEST['attribute_'.$attribute_name]) ) {
                            $_REQUEST['attribute_'.$attribute_name] = $attribute_val;
                        }
                    }
                }
            }
            if( ! empty($options['use_filtered_variation_once']) ) {
                unset($_SESSION['BeRocket_filters']);
            }
        }
    }
    //CHECKBOX/RADIO/COLOR WITH LINKS
    function check_radio_color_filter_term_text($text, $term, $operator, $single) {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $option = $BeRocket_AAPF->get_option();
        $term_taxonomy_echo = berocket_isset($term, 'wpml_taxonomy');
        if( empty($term_taxonomy_echo) ) {
            $term_taxonomy_echo = berocket_isset($term, 'taxonomy');
        }
        $text = '<a href="'.berocket_add_filter_to_link($term_taxonomy_echo, berocket_isset($term, (! empty($option['slug_urls']) ? 'slug' : 'term_id')), $operator, $single).'">'.$text.'</a>';
        return $text;
    }
    //CANONICAL URL
    function wp_head_canonical() {
        global $wp_query;
        $option_permalink = get_option( 'berocket_permalink_option' );
        if( is_post_type_archive( 'product' ) ) {
            global $wp, $sitepress;
            $BeRocket_AAPF = BeRocket_AAPF::getInstance();
            $br_options = $BeRocket_AAPF->get_option();
            $current_page_url = preg_replace( "~paged?/[0-9]+/?~", "", home_url( $wp->request ) );
            if( ! empty($br_options['nice_urls']) ) {
                $current_page_url = preg_replace( "~".$option_permalink['variable']."/.+~", "", $current_page_url );
                $current_page_url = preg_replace( "~".urlencode($option_permalink['variable'])."/.+~", "", $current_page_url );
            }
            if( strpos($current_page_url, '?') !== FALSE ) {
                $current_page_url = explode('?', $current_page_url);
                $current_page_url = $current_page_url[0];
            }
            echo '<link rel="canonical" href="' . $current_page_url . '">';
        }
    }
    //GROUP SETTINGS
    function group_add() {
        add_action( 'berocket_aapf_filters_group_settings', array($this, 'group_settings'), 10, 3 );
        add_filter( 'berocket_aapf_group_before_all', array($this, 'search_box_before_group_start'), 10, 2 );
        add_filter( 'berocket_aapf_group_after_all', array($this, 'search_box_after_group_end'), 10, 2 );
        add_filter( 'berocket_aapf_group_before_filter', array($this, 'search_box_before_group_filter'), 10, 2 );
        add_filter( 'berocket_aapf_group_after_filter', array($this, 'search_box_after_group_filter'), 10, 2 );
        add_filter( 'berocket_aapf_group_new_args', array($this, 'group_new_args'), 10, 2 );
        add_filter( 'berocket_aapf_group_new_args_filter', array($this, 'group_new_args_filter'), 10, 3 );
    }
    function group_settings($filters, $post_name, $post) {
        include AAPF_TEMPLATE_PATH . "paid/filters_group.php";
    }
    //GROUP SEARCH BOX
    function search_box_before_group_start($custom_vars, $filters) {
        if( ! empty($filters['search_box']) ) {
            $search_box_main_class = array('berocket_search_box_block');
            if( ! empty($filters['hide_group']['mobile']) ) {
                $search_box_main_class[] = 'berocket_hide_single_widget_on_mobile';
            }
            if( ! empty($filters['hide_group']['tablet']) ) {
                $search_box_main_class[] = 'berocket_hide_single_widget_on_tablet';
            }
            if( ! empty($filters['hide_group']['desktop']) ) {
                $search_box_main_class[] = 'berocket_hide_single_widget_on_desktop';
            }
            $search_box_link_type = br_get_value_from_array($filters, 'search_box_link_type');
            $search_box_url = br_get_value_from_array($filters, 'search_box_url');
            $search_box_style = br_get_value_from_array($filters, 'search_box_style');
            $search_box_category = br_get_value_from_array($filters, 'search_box_category');
            if( $search_box_link_type == 'shop_page' ) {
                if( function_exists('wc_get_page_id') ) {
                    $search_box_url = get_permalink( wc_get_page_id( 'shop' ) );
                } else {
                    $search_box_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
                }
            } elseif( $search_box_link_type == 'category' ) {
                $search_box_url = get_term_link( $search_box_category, 'product_cat' );
            }
            $sb_style = '';
            if ( $search_box_style['position'] == 'horizontal' ) {
                $sb_count = count($filters['filters']);
                if( $search_box_style['search_position'] == 'before_after' ) {
                    $sb_count += 2;
                } else {
                    $sb_count++;
                }
                $search_box_width = (int)(100 / $sb_count);
                $sb_style .= 'width:'.$search_box_width.'%;display:inline-block;padding: 4px;';
            }
            $search_box_button_class = 'search_box_button_class_'.rand();
            $sbb_style = '';
            if( ! empty($search_box_style['background']) ) {
                $sbb_style .= 'background-color:'.($search_box_style['background'][0] == '#' ? $search_box_style['background'] : '#'.$search_box_style['background']).';';
            }
            $sbb_style .= 'opacity:'.$search_box_style['back_opacity'].';';
            if( ! empty($title) ) { ?><h3 class="widget-title berocket_aapf_widget-title" style="<?php echo ( empty($uo['style']['title']) ? '' : $uo['style']['title'] ) ?>"><span><?php echo $title; ?></span></h3><?php }
            echo '<div class="'.implode(' ', $search_box_main_class).'">';
            echo '<div class="berocket_search_box_background" style="'.$sbb_style.'"></div>';
            echo '<div class="berocket_search_box_background_all">';
            $sbb_style = '';
            if( ! empty($search_box_style['button_background']) ) {
                $sbb_style .= 'background-color:'.($search_box_style['button_background'][0] == '#' ? $search_box_style['button_background'] : '#'.$search_box_style['button_background']).';';
            }
            if( ! empty($search_box_style['text_color']) ) {
                $sbb_style .= 'color:'.($search_box_style['text_color'][0] == '#' ? $search_box_style['text_color'] : '#'.$search_box_style['text_color']).';';
            }
            if( ! empty($search_box_style['button_background_over']) ) {
                $sbb_style_hover = 'background-color:'.($search_box_style['button_background_over'][0] == '#' ? $search_box_style['button_background_over'] : '#'.$search_box_style['button_background_over']).';';
            }
            if( ! empty($search_box_style['text_color_over']) ) {
                $sbb_style_hover .= 'color:'.($search_box_style['text_color_over'][0] == '#' ? $search_box_style['text_color_over'] : '#'.$search_box_style['text_color_over']).';';
            }
            if ( $search_box_style['search_position'] == 'before' || $search_box_style['search_position'] == 'before_after' ) {
                echo '<div style="'.$sb_style.'"><a data-url="'.$search_box_url.'" class="'.$search_box_button_class.' berocket_search_box_button">'.$search_box_style['search_text'].'</a></div>';
            }
            $custom_vars['sbb_style'] = $sbb_style;
            $custom_vars['sbb_style_hover'] = $sbb_style_hover;
            $custom_vars['sb_style'] = $sb_style;
            $custom_vars['search_box_button_class'] = $search_box_button_class;
            $custom_vars['search_box_link_type'] = $search_box_link_type;
            $custom_vars['search_box_url'] = $search_box_url;
            $custom_vars['search_box_style'] = $search_box_style;
            $custom_vars['search_box_category'] = $search_box_category;
        }
        return $custom_vars;
    }
    function search_box_after_group_end($custom_vars, $filters) {
        extract($custom_vars);
        if( ! empty($filters['search_box']) ) {
            if ( $search_box_style['search_position'] == 'after' || $search_box_style['search_position'] == 'before_after' ) {
                echo '<div style="'.$sb_style.'">
                <a data-url="'.$search_box_url.'" 
                class="'.$search_box_button_class.' berocket_search_box_button">
                '.$search_box_style['search_text'].'</a></div>';
            }
            echo '</div></div>';
            echo '<style>.'.$search_box_button_class.'{'.$sbb_style.'}.'.$search_box_button_class.':hover{'.$sbb_style_hover.'}</style>';
        }
        return $custom_vars;
    }
    function search_box_before_group_filter($custom_vars, $filters) {
        extract($custom_vars);
        if( ! empty($filters['search_box']) ) {
            echo '<div style="'.$sb_style.'">';
        }
        return $custom_vars;
    }
    function search_box_after_group_filter($custom_vars, $filters) {
        if( ! empty($filters['search_box']) ) {
            echo '</div>';
        }
        return $custom_vars;
    }
    //GROUP INLINE
    function group_new_args($new_args, $filters) {
        $additional_class = array();
        if( ! empty($filters['hide_group']['mobile']) ) {
            $additional_class[] = 'berocket_hide_single_widget_on_mobile';
        }
        if( ! empty($filters['hide_group']['tablet']) ) {
            $additional_class[] = 'berocket_hide_single_widget_on_tablet';
        }
        if( ! empty($filters['hide_group']['desktop']) ) {
            $additional_class[] = 'berocket_hide_single_widget_on_desktop';
        }
        $style = '';
        if( ! empty($filters['hidden_clickable']) ) {
            $additional_class[] = 'berocket_hidden_clickable';
            if( ! empty($filters['display_inline']) ) {
                $additional_class[] = 'berocket_inline_clickable';
            }
            if( ! empty($filters['hidden_clickable_hover']) ) {
                $additional_class[] = 'berocket_inline_clickable_hover';
            }
            $new_args['filter_data'] = array(
                'widget_is_hide' => 1,
                'widget_collapse_disable' => 0,
            );
        } elseif( ! empty($filters['display_inline']) ) {
            $additional_class[] = 'berocket_inline_filters';
            if( ! empty($filters['display_inline_count']) ) {
                $additional_class[] = 'berocket_inline_filters_count_'.$filters['display_inline_count'];
            }
            $style .= 'opacity:0!important;';
        }
        if( empty($new_args['inline_style']) ) {
            $new_args['inline_style'] = '';
        }
        $new_args['inline_style'] .= $style;
        if( empty($new_args['additional_class']) || ! is_array($new_args['additional_class']) ) {
            $new_args['additional_class'] = array();
        }
        $new_args['additional_class'] = array_merge($new_args['additional_class'], $additional_class);
        return $new_args;
    }
    function group_new_args_filter($new_args, $filters, $filter) {
        if( ! empty($filters['hidden_clickable']) && $widget_inline_width = br_get_value_from_array($filters, array('filters_data', $filter, 'width')) ) {
            if(strpos($widget_inline_width, 'px') === FALSE
            && strpos($widget_inline_width, '%') === FALSE
            && strpos($widget_inline_width, 'em') === FALSE ) {
                $widget_inline_width = $widget_inline_width.'px';
            }
            $new_args['widget_inline_style'] = "width:{$widget_inline_width}!important;";
        } else {
            $new_args['widget_inline_style'] = "";
        }
        return $new_args;
    }
    //Show products count before filtering
    function listener_product_count(){
        global $wp_query, $wp_rewrite;
        $br_options = BeRocket_AAPF::get_aapf_option();

        $wp_query = BeRocket_AAPF_Widget::listener_wp_query();

        $product_count = $wp_query->found_posts;
        
        echo json_encode( array( 'product_count' => $product_count ) );

        die();
    }
    //CACHE OPTIONS
    function br_get_cache( $return, $key, $group ){
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $option = $BeRocket_AAPF->get_option();
        $cache_type = $option['object_cache'];
        $language = br_get_current_language_code();
        $group = $group.$language;
        if ( $cache_type == 'wordpress' ) {
            $return = get_site_transient( md5($group.$key) );
        } elseif ( $cache_type == 'persistent' ) {
            $return = wp_cache_get( $key, $group );
        }
        return $return;
    }
    function br_set_cache( $return, $key, $value, $group, $expire ){
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $option = $BeRocket_AAPF->get_option();
        $cache_type = $option['object_cache'];
        $language = br_get_current_language_code();
        $group = $group.$language;
        if ( $cache_type == 'wordpress' ) {
            set_site_transient( md5($group.$key), $value, $expire );
        } elseif ( $cache_type == 'persistent' ) {
            wp_cache_add( $key, $value, $group, $expire );
        }
        return $return;
    }
    //FILTER
    function filter_add() {
        add_filter( 'berocket_filter_filter_type_array', array($this, 'filter_filter_type_array') );
        add_filter( 'berocket_admin_filter_types_by_attr', array($this, 'admin_filter_types_by_attr') );
        add_filter( 'berocket_widget_widget_type_array', array($this, 'widget_widget_type_array') );
        add_filter( 'berocket_custom_post_br_product_filter_default_settings', array($this, 'single_filter_default_settings') );
        add_filter( 'berocket_widget_advanced_settings_elements', array($this, 'widget_advanced_settings_elements'), 10, 3 );
        add_filter( 'berocket_widget_attribute_type_terms', array($this, 'widget_attribute_type_terms'), 10, 4 );
        add_filter( 'berocket_widget_load_template_name', array($this, 'widget_load_template_name'), 10, 1 );
        add_filter( 'berocket_aapf_widget_display_custom_filter', array($this, 'widget_display_custom_filter'), 10, 5 );
        add_filter( 'berocket_radio_filter_term_name', array($this, 'filter_term_name'), 10, 2 );
        add_filter( 'berocket_widget_color_image_temp_meta_class_init', array($this, 'temp_meta_class_init'), 10, 2 );
        add_filter( 'berocket_widget_color_image_temp_meta_ready', array($this, 'temp_meta_class_ready'), 10, 3 );
        add_filter( 'berocket_widget_color_image_temp_span_class', array($this, 'temp_span_class'), 10, 3 );
        add_filter( 'berocket_widget_aapf_start_temp_class', array($this, 'start_temp_class'), 10, 1 );
        add_filter( 'berocket_aapf_widget_include_exclude_items', array($this, 'hook_include_exclude_items'), 10, 2 );

        add_action( 'berocket_widget_filter_post_end', array($this, 'widget_filter_post_end'), 10, 2 );
        add_action( 'berocket_widget_filter_advanced_settings_end', array($this, 'widget_filter_advanced_settings_end'), 10, 2 );
        add_action( 'berocket_widget_filter_output_limitation_end', array($this, 'widget_filter_output_limitation_end'), 10, 2 );
    }
    function filter_filter_type_array($filter_type) {
        $filter_type = berocket_insert_to_array(
            $filter_type,
            'tag',
            array(
                'product_cat' => array(
                    'name' => __('Product sub-categories', 'BeRocket_AJAX_domain'),
                    'sameas' => 'product_cat',
                ),
                'custom_taxonomy' => array(
                    'name' => __('Custom Taxonomy', 'BeRocket_AJAX_domain'),
                    'sameas' => 'custom_taxonomy',
                ),
                '_stock_status' => array(
                    'name' => __('Stock status', 'BeRocket_AJAX_domain'),
                    'sameas' => '_stock_status',
                ),
                'date' => array(
                    'name' => __('Date', 'BeRocket_AJAX_domain'),
                    'sameas' => 'date',
                ),
                '_sale' => array(
                    'name' => __('Sale', 'BeRocket_AJAX_domain'),
                    'sameas' => '_sale',
                ),
            )
        );
        return $filter_type;
    }
    function admin_filter_types_by_attr($vars) {
        list($berocket_admin_filter_types, $berocket_admin_filter_types_by_attr) = $vars;
        $berocket_admin_filter_types_by_attr['ranges'] = array('value' => 'ranges', 'text' => 'Ranges');
        $berocket_admin_filter_types['custom_taxonomy'][] = "slider";
        $berocket_admin_filter_types['attribute'][] = "slider";
        $berocket_admin_filter_types['filter_by'][] = "slider";
        $berocket_admin_filter_types['price'][] = "ranges";
        return array($berocket_admin_filter_types, $berocket_admin_filter_types_by_attr);
    }
    function widget_widget_type_array($widget_types) {
        $widget_types['search_box'] = __('Search Box', 'BeRocket_AJAX_domain');
        return $widget_types;
    }
    function single_filter_default_settings($default_settings) {
        $default_settings = array_merge(
            $default_settings,
            array(
                'child_parent'                  => '',
                'child_parent_depth'            => '1',
                'child_parent_no_values'        => '',
                'child_parent_previous'         => '',
                'child_parent_no_products'      => '',
                'child_onew_count'              => '1',
                'child_onew_childs'             => array(
                    1                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    2                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    3                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    4                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    5                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    6                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    7                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    8                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    9                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                    10                              => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
                ),
                'search_box_link_type'          => 'shop_page',
                'search_box_url'                => '',
                'search_box_category'           => '',
                'search_box_count'              => '1',
                'search_box_attributes'             => array(
                    1                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    2                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    3                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    4                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    5                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    6                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    7                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    8                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    9                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                    10                              => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
                ),
                'search_box_style'              => array(
                    'position'                      => 'vertical',
                    'search_position'               => 'after',
                    'search_text'                   => 'Search',
                    'background'                    => 'bbbbff',
                    'back_opacity'                  => '0',
                    'button_background'             => '888800',
                    'button_background_over'        => 'aaaa00',
                    'text_color'                    => '000000',
                    'text_color_over'               => '000000',
                ),
                'ranges'                        => array( 1, 10 ),
                'hide_first_last_ranges'        => '',
                'include_exclude_select'        => '',
                'include_exclude_list'          => array(),
            )
        );
        
        return $default_settings;
    }
    function widget_filter_post_end($post_name, $instance) {
        $attributes        = br_aapf_get_attributes();
        $categories        = BeRocket_AAPF_Widget::get_product_categories( @ json_decode( $instance['product_cat'] ) );
        $categories        = BeRocket_AAPF_Widget::set_terms_on_same_level( $categories );
        $tags              = get_terms( 'product_tag' );
        $custom_taxonomies = get_taxonomies( array( "_builtin" => false, "public" => true ) );
        ?>
<div class="berocket_aapf_admin_search_box"<?php if( $instance['widget_type'] != 'search_box' ) echo ' style="display:none;"'; ?>>
    <div class="br_accordion">
        <h3><?php _e('Attributes', 'BeRocket_AJAX_domain') ?></h3>
        <div>
            <div>
                <label><?php _e('URL to search', 'BeRocket_AJAX_domain') ?></label>
                <select name="<?php echo $post_name.'[search_box_link_type]'; ?>" class="berocket_search_link_select br_select_menu_left">
                    <option value="shop_page"<?php if ($instance['search_box_link_type'] == 'shop_page' ) echo ' selected'; ?>><?php _e('Shop page', 'BeRocket_AJAX_domain') ?></option>
                    <option value="category"<?php if ($instance['search_box_link_type'] == 'category' ) echo ' selected'; ?>><?php _e('Category page', 'BeRocket_AJAX_domain') ?></option>
                    <option value="url"<?php if ($instance['search_box_link_type'] == 'url' ) echo ' selected'; ?>><?php _e('URL', 'BeRocket_AJAX_domain') ?></option>
                </select>
            </div>
            <div class="berocket_search_link berocket_search_link_category"<?php if( $instance['search_box_link_type'] != 'category' ) echo ' style="display:none;"'; ?>>
                <label><?php _e('Category', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo $post_name.'[search_box_category]'; ?>">
                <?php 
                $instance['search_box_category'] = ( empty($instance['search_box_category']) ? '' : urldecode($instance['search_box_category']) );
                foreach( $categories as $category ){
                    echo '<option value="'.$category->slug.'"'.($instance['search_box_category'] == $category->slug ? ' selected' : '').'>'.$category->name.'</option>';
                } ?>
                </select>
            </div>
            <div class="berocket_search_link berocket_search_link_url"<?php if( $instance['search_box_link_type'] != 'url' ) echo ' style="display:none;"'; ?>>
                <label><?php _e('URL for search', 'BeRocket_AJAX_domain') ?></label>
                <input class="br_admin_full_size" id="<?php echo 'search_box_url'; ?>" name="<?php echo $post_name.'[search_box_url]'; ?>" type="text" value="<?php echo $instance['search_box_url']; ?>">
            </div>
            <div>
                <label><?php _e('Attributes count', 'BeRocket_AJAX_domain') ?></label>
                <select id="<?php echo 'search_box_count'; ?>" name="<?php echo $post_name.'[search_box_count]'; ?>" class="br_search_box_count br_select_menu_left">
                    <?php 
                    for ( $i = 1; $i < 11; $i++ ) {
                        echo '<option value="'.$i.'"'.($instance['search_box_count'] == $i ? ' selected' : '').'>'.$i.'</option>';
                    }
                    ?>
                </select>
            </div>
            <?php for( $i = 1; $i < 11; $i++ ) {
                echo '<div class="berocket_search_box_attribute_'.$i.'"'.($instance['search_box_count'] >= $i ? '' : ' style="display:none;"').'>';
                ?>
                <div class="br_accordion">
                    <h3><?php _e('Attribute', 'BeRocket_AJAX_domain') ?> <?php echo $i; ?></h3>
                    <div class="br_search_box_attribute_block">
                        <div>
                            <label class="br_admin_center" for="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_title"><?php _e('Title', 'BeRocket_AJAX_domain') ?> </label>
                            <input class="br_admin_full_size" id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_title" type="text" name="<?php echo $post_name.'[search_box_attributes]'; ?>[<?php echo $i; ?>][title]" value="<?php echo $instance['search_box_attributes'][$i]['title']; ?>"/>
                        </div>
                        <div class="br_admin_half_size_left">
                            <label class="br_admin_center"><?php _e('Filter By', 'BeRocket_AJAX_domain') ?></label>
                            <select id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>" name="<?php echo $post_name.'[search_box_attributes]'; ?>[<?php echo $i; ?>][type]" class="br_search_box_attribute_type br_select_menu_left">
                                <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'type')) == 'attribute' ) echo 'selected'; ?> value="attribute"><?php _e('Attribute', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'type')) == 'tag' ) echo 'selected'; ?> value="tag"><?php _e('Tag', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'type')) == 'custom_taxonomy' ) echo 'selected'; ?> value="custom_taxonomy"><?php _e('Custom Taxonomy', 'BeRocket_AJAX_domain') ?></option>
                            </select>
                        </div>
                        <div class="br_admin_half_size_right br_search_box_attribute_attribute_block" <?php if ( $instance['search_box_attributes'][$i]['type'] and $instance['search_box_attributes'][$i]['type'] != 'attribute') echo 'style="display: none;"'; ?>>
                            <label class="br_admin_center"><?php _e('Attribute', 'BeRocket_AJAX_domain') ?></label>
                            <select id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_attribute" name="<?php echo $post_name.'[search_box_attributes]'; ?>[<?php echo $i; ?>][attribute]" class="br_search_box_attribute_attribute br_select_menu_right">
                                <?php foreach ( $attributes as $k => $v ) { ?>
                                    <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'attribute')) == $k ) echo 'selected'; ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="br_admin_half_size_right br_search_box_attribute_custom_taxonomy_block" <?php if ( $instance['search_box_attributes'][$i]['type'] != 'custom_taxonomy') echo 'style="display: none;"'; ?>>
                            <label class="br_admin_center"><?php _e('Custom Taxonomies', 'BeRocket_AJAX_domain') ?></label>
                            <select id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_custom" name="<?php echo $post_name.'[search_box_attributes]'; ?>[<?php echo $i; ?>][custom_taxonomy]" class="br_search_box_attribute_custom_taxonomy br_select_menu_right">
                                <?php foreach( $custom_taxonomies as $k => $v ){ ?>
                                    <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'custom_taxonomy')) == $k ) echo 'selected'; ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="br_clearfix"></div>
                        <div>
                            <label class="br_admin_center"><?php _e('Type', 'BeRocket_AJAX_domain') ?></label>
                            <select id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_visual_type" name="<?php echo $post_name.'[search_box_attributes]'; ?>[<?php echo $i; ?>][visual_type]" class="br_select_menu_left">
                                <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'visual_type')) == 'select' ) echo 'selected'; ?> value="select"><?php _e('Select', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'visual_type')) == 'checkbox' ) echo 'selected'; ?> value="checkbox"><?php _e('Checkbox', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'visual_type')) == 'radio' ) echo 'selected'; ?> value="radio"><?php _e('Radio', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'visual_type')) == 'color' ) echo 'selected'; ?> value="color"><?php _e('Color', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( br_get_value_from_array($instance, array('search_box_attributes', $i, 'visual_type')) == 'image' ) echo 'selected'; ?> value="image"><?php _e('Image', 'BeRocket_AJAX_domain') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <?php
                echo '</div>';
            } ?>
            <div class="br_clearfix"></div>
        </div>
    </div>
    <div class="br_accordion">
        <h3><?php _e('Styles', 'BeRocket_AJAX_domain') ?></h3>
        <div>
            <div>
                <label><?php _e('Elements position', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo $post_name.'[search_box_style]'; ?>[position]">
                    <option value="vertical"<?php if( br_get_value_from_array($instance, array('search_box_style', 'position')) == 'vertical' ) echo ' selected'; ?>><?php _e('Vertical', 'BeRocket_AJAX_domain') ?></option>
                    <option value="horizontal"<?php if( br_get_value_from_array($instance, array('search_box_style', 'position')) == 'horizontal' ) echo ' selected'; ?>><?php _e('Horizontal', 'BeRocket_AJAX_domain') ?></option>
                </select>
            </div>
            <div>
                <label><?php _e('Search button position', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo $post_name.'[search_box_style]'; ?>[search_position]">
                    <option value="before"<?php if( br_get_value_from_array($instance, array('search_box_style', 'search_position')) == 'before' ) echo ' selected'; ?>><?php _e('Before', 'BeRocket_AJAX_domain') ?></option>
                    <option value="after"<?php if( br_get_value_from_array($instance, array('search_box_style', 'search_position')) == 'after' ) echo ' selected'; ?>><?php _e('After', 'BeRocket_AJAX_domain') ?></option>
                    <option value="before_after"<?php if( br_get_value_from_array($instance, array('search_box_style', 'search_position')) == 'before_after' ) echo ' selected'; ?>><?php _e('Before and after', 'BeRocket_AJAX_domain') ?></option>
                </select>
            </div>
            <div>
                <label><?php _e('Search button text', 'BeRocket_AJAX_domain') ?></label>
                <input type="text" class="br_admin_full_size" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'search_text')); ?>" name="<?php echo $post_name.'[search_box_style]'; ?>[search_text]">
            </div>
            <div>
                <label><?php _e('Background color', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'background'), '000000'); ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'background')) ?>" name="<?php echo $post_name.'[search_box_style]'; ?>[background]">
            </div>
            <div>
                <label><?php _e('Background transparency', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo $post_name.'[search_box_style]'; ?>[back_opacity]">
                    <?php
                    $back_opacity = array(
                        '0'     => __('100%', 'BeRocket_AJAX_domain'),
                        '0.1'   => __('90%', 'BeRocket_AJAX_domain'),
                        '0.2'   => __('80%', 'BeRocket_AJAX_domain'),
                        '0.3'   => __('70%', 'BeRocket_AJAX_domain'),
                        '0.4'   => __('60%', 'BeRocket_AJAX_domain'),
                        '0.5'   => __('50%', 'BeRocket_AJAX_domain'),
                        '0.6'   => __('40%', 'BeRocket_AJAX_domain'),
                        '0.7'   => __('30%', 'BeRocket_AJAX_domain'),
                        '0.8'   => __('20%', 'BeRocket_AJAX_domain'),
                        '0.9'   => __('10%', 'BeRocket_AJAX_domain'),
                        '1'     => __('0%', 'BeRocket_AJAX_domain'),
                    );
                    foreach($back_opacity as $key => $value) {
                        echo '<option value="', $key, '"', 
                        ( (br_get_value_from_array($instance, array('search_box_style', 'back_opacity')) == $key) ? ' selected' : '' ),
                        '>', $value, '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label><?php _e('Button background color', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'button_background'), '000000'); ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'button_background')) ?>" name="<?php echo $post_name.'[search_box_style]'; ?>[button_background]">
            </div>
            <div>
                <label><?php _e('Button background color on mouse over', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'button_background_over'), '000000'); ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'button_background_over')) ?>" name="<?php echo $post_name.'[search_box_style]'; ?>[button_background_over]">
            </div>
            <div>
                <label><?php _e('Button text color', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'text_color'), '000000') ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'text_color')) ?>" name="<?php echo $post_name.'[search_box_style]'; ?>[text_color]">
            </div>
            <div>
                <label><?php _e('Button text color on mouse over', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'text_color_over'), '000000') ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'text_color_over')) ?>" name="<?php echo $post_name.'[search_box_style]'; ?>[text_color_over]">
            </div>
        </div>
    </div>
</div>
        <?php
    }
    function widget_filter_advanced_settings_end($post_name, $instance) {
        ?>
            <div class="br_aapf_child_parent_selector" <?php if ( $instance['filter_type'] == 'attribute' and $instance['attribute'] == 'price'  or $instance['filter_type'] == 'product_cat' or $instance['filter_type'] == '_stock_status' or $instance['filter_type'] == 'tag' or $instance['type'] == 'slider' or $instance['filter_type'] == 'date' or $instance['filter_type'] == '_sale' or $instance['filter_type'] == '_rating' ) echo " style='display: none;'"; ?>>
                <div>
                    <label class="br_admin_center"><?php _e('Child/Parent Limitation', 'BeRocket_AJAX_domain') ?></label>
                    <select name="<?php echo $post_name.'[child_parent]'; ?>" class="br_select_menu_left berocket_aapf_widget_child_parent_select">
                        <option value="" <?php if ( ! $instance['child_parent'] ) echo 'selected' ?>><?php _e('Default', 'BeRocket_AJAX_domain') ?></option>
                        <option value="depth" <?php if ( $instance['child_parent'] == 'depth' ) echo 'selected' ?>><?php _e('Child Count', 'BeRocket_AJAX_domain') ?></option>
                        <option value="parent" <?php if ( $instance['child_parent'] == 'parent' ) echo 'selected' ?>><?php _e('Parent', 'BeRocket_AJAX_domain') ?></option>
                        <option value="child" <?php if ( $instance['child_parent'] == 'child' ) echo 'selected' ?>><?php _e('Child', 'BeRocket_AJAX_domain') ?></option>
                    </select>
                </div>
                <div class="berocket_aapf_widget_child_parent_depth_block" <?php if( $instance['child_parent'] != 'child' ) echo 'style="display: none;"'; ?>>
                    <label for="<?php echo 'child_parent_depth'; ?>" class="br_admin_full_size"><?php _e('Child depth', 'BeRocket_AJAX_domain') ?></label>
                    <input name="<?php echo $post_name.'[child_parent_depth]'; ?>" id="<?php echo 'child_parent_depth'; ?>" type="number" min="1" value="<?php echo $instance['child_parent_depth']; ?>">
                    <div>
                        <label><?php _e('"No values" messages', 'BeRocket_AJAX_domain') ?></label>
                        <input class="br_admin_full_size" name="<?php echo $post_name.'[child_parent_no_values]'; ?>" type="text" value="<?php echo $instance['child_parent_no_values']; ?>">
                    </div>
                    <div>
                        <label><?php _e('"Select previous" messages', 'BeRocket_AJAX_domain') ?></label>
                        <input class="br_admin_full_size" name="<?php echo $post_name.'[child_parent_previous]'; ?>" type="text" value="<?php echo $instance['child_parent_previous']; ?>">
                    </div>
                    <div>
                        <label><?php _e('"No Products" messages', 'BeRocket_AJAX_domain') ?></label>
                        <input class="br_admin_full_size" name="<?php echo $post_name.'[child_parent_no_products]'; ?>" type="text" value="<?php echo $instance['child_parent_no_products']; ?>">
                    </div>
                </div>
                <div class="berocket_aapf_widget_child_parent_one_widget" <?php if( $instance['child_parent'] != 'depth' ) echo 'style="display: none;"'; ?>>
                    <label for="<?php echo 'child_onew_count'; ?>" class="br_admin_full_size"><?php _e('Child count', 'BeRocket_AJAX_domain') ?></label>
                    <select class="br_onew_child_count_select br_select_menu_left" id="<?php echo 'child_onew_count'; ?>" name="<?php echo $post_name.'[child_onew_count]'; ?>">
                        <?php 
                        $instance['child_onew_count'] = (int)$instance['child_onew_count'];
                        if ( $instance['child_onew_count'] < 1 ) {
                            $instance['child_onew_count'] = 1;
                        } 
                        for($i = 1; $i < 11; $i++) {
                            echo '<option value="'.$i.'"'.($instance['child_onew_count'] == $i ? ' selected' : '').'>'.$i.'</option>';
                        }
                        ?>
                    </select>
                    <?php 
                    for($i = 1; $i < 11; $i++) {
                        ?>
                        <div class="child_onew_childs_settings child_onew_childs_<?php echo $i; ?>"<?php if($i > $instance['child_onew_count']) echo ' style="display:none;"'; ?>>
                            <h4 class="br_admin_full_size"><?php echo __('Child', 'BeRocket_AJAX_domain').' '.$i; ?></h4>
                            <div>
                                <label><?php _e('Title', 'BeRocket_AJAX_domain') ?></label>
                                <input class="br_admin_full_size" name="<?php echo $post_name.'[child_onew_childs]'.'['.$i.'][title]'; ?>" type="text" value="<?php echo $instance['child_onew_childs'][$i]['title']; ?>">
                            </div>
                            <div>
                                <label><?php _e('"No products" messages', 'BeRocket_AJAX_domain') ?></label>
                                <input class="br_admin_full_size" name="<?php echo $post_name.'[child_onew_childs]'.'['.$i.'][no_product]'; ?>" type="text" value="<?php echo $instance['child_onew_childs'][$i]['no_product']; ?>">
                            </div>
                            <div>
                                <label><?php _e('"No values" messages', 'BeRocket_AJAX_domain') ?></label>
                                <input class="br_admin_full_size" name="<?php echo $post_name.'[child_onew_childs]'.'['.$i.'][no_values]'; ?>" type="text" value="<?php echo $instance['child_onew_childs'][$i]['no_values']; ?>">
                            </div>
                            <div>
                                <label><?php _e('"Select previous" messages', 'BeRocket_AJAX_domain') ?></label>
                                <input class="br_admin_full_size" name="<?php echo $post_name.'[child_onew_childs]'.'['.$i.'][previous]'; ?>" type="text" value="<?php echo $instance['child_onew_childs'][$i]['previous']; ?>">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        <?php
    }
    function widget_filter_output_limitation_end($post_name, $instance) {
        $taxonomy_name = false;
        if( $instance['filter_type'] == 'custom_taxonomy' ) {
            $taxonomy_name = $instance['custom_taxonomy'];
        } elseif( $instance['filter_type'] == 'attribute' && $instance['attribute'] != 'price' ) {
            $taxonomy_name = $instance['attribute'];
        }
        ?>
        <div class="include_exclude_select"<?php if( $taxonomy_name === false ) echo ' style="display: none;"' ?>>
            <select name="<?php echo $post_name.'[include_exclude_select]'; ?>">
                <option value=""><?php _e('Disabled', 'BeRocket_AJAX_domain') ?></option>
                <option value="include"<?php if( $instance['include_exclude_select'] == 'include' ) echo ' selected'; ?>><?php _e('Display only', 'BeRocket_AJAX_domain') ?></option>
                <option value="exclude"<?php if( $instance['include_exclude_select'] == 'exclude' ) echo ' selected'; ?>><?php _e('Remove', 'BeRocket_AJAX_domain') ?></option>
            </select>
            <label><?php _e('values selected in Include / Exclude List', 'BeRocket_AJAX_domain') ?></label>
        </div>
        <div class="include_exclude_list" data-name="<?php echo $post_name.'[include_exclude_list]'; ?>"<?php if( empty($instance['include_exclude_select']) ) echo ' style="display: none;"'; ?>>
            <?php
            if( $taxonomy_name !== false ) {
                $list = BeRocket_AAPF_Widget::include_exclude_terms_list($taxonomy_name, $instance['include_exclude_list']);
                $list = str_replace('%field_name%', $post_name.'[include_exclude_list]', $list);
                echo $list;
            }
            ?>
        </div>
        <?php
    }
    function widget_advanced_settings_elements($advanced_settings_elements, $post_name, $instance) {
        $advanced_settings_elements = berocket_insert_to_array(
            $advanced_settings_elements,
            'attribute_count',
            array(
                'slider_default' => '
                    <div class="berocket_attributes_slider_data"'
                    .( ( ( $instance['filter_type'] != 'custom_taxonomy' and $instance['filter_type'] != 'attribute' ) or $instance['type'] != 'slider' or ( $instance['filter_type'] == 'attribute' && $instance['attribute'] == 'price' )) ? ' style="display:none;"' : '' ).'>
                        <input id="slider_default" type="checkbox" name="'.$post_name.'[slider_default]"'.( empty($instance['slider_default']) ? '' : ' checked').' value="1">
                        <label for="slider_default">'.__('Use default values for slider', 'BeRocket_AJAX_domain').'</label>
                    </div>
                ',
            )
        );
        $advanced_settings_elements = berocket_insert_to_array(
            $advanced_settings_elements,
            'widget_is_hide',
            array(
                'show_product_count_per_attr' =>'
                    <div class="berocket_aapf_widget_admin_non_price_tag_cloud"'
                    .( ( $instance['type'] == 'tag_cloud' || $instance['type'] == 'slider' ) ? ' style="display:none;"' : '' ).'>
                        <input id="show_product_count_per_attr" type="checkbox" name="'.$post_name.'[show_product_count_per_attr]"'.( empty($instance['show_product_count_per_attr']) ? '' : ' checked' ).' value="1" />
                        <label for="show_product_count_per_attr">'.__('Show product count per attribute value?', 'BeRocket_AJAX_domain').'</label>
                    </div>
                ',
            )
        );
        $advanced_settings_elements = berocket_insert_to_array(
            $advanced_settings_elements,
            'hide_child_attributes',
            array(
                'values_per_row' => '
                    <div class="br_admin_full_size"'.( ( ( ! $instance['filter_type'] or $instance['filter_type'] == 'attribute' ) and $instance['attribute'] == 'price' or $instance['filter_type'] == 'product_cat' or $instance['type'] == 'slider' or $instance['type'] == 'select' or $instance['type'] == 'tag_cloud' or ( $instance['filter_type'] == 'custom_taxonomy' and $instance['custom_taxonomy'] == 'product_cat' ) ) ? " style='display: none;'" : '' ).'>
                        <label class="br_admin_center">'.__('Values per row', 'BeRocket_AJAX_domain').'</label>
                        <select id="values_per_row" name="'.$post_name.'[values_per_row]" class="berocket_aapf_widget_admin_values_per_row br_select_menu_left">
                            <option'.( ( empty($instance['operator']) || $instance['values_per_row'] == '1' ) ? ' selected' : '' ).' value="1">Default</option>
                            <option'.( $instance['values_per_row'] == '2' ? ' selected' : '' ).' value="2">2</option>
                            <option'.( $instance['values_per_row'] == '3' ? ' selected' : '' ).' value="3">3</option>
                            <option'.( $instance['values_per_row'] == '4' ? ' selected' : '' ).' value="4">4</option>
                        </select>
                    </div>
                ',
            )
        );
        return $advanced_settings_elements;
    }
    function widget_attribute_type_terms($vars, $attr_type, $attr_filter_type, $instance) {
        extract($instance);
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $br_options = $BeRocket_AAPF->get_option();
        list($terms_error_return, $terms_ready, $terms, $type) = $vars;
        if ( $attr_filter_type == 'attribute' ) {
            if ( $type == 'ranges' && $attr_type == 'price' ) {
                $terms_ready = true;
                if ( count( $ranges ) < 2 ) {
                    $terms_error_return = 'ranges < 2';
                    $terms = $ranges;
                    return array($terms_error_return, $terms_ready, $terms, $type);
                }
                $terms = array();
                $ranges[0]--;
                if( ! empty($hide_first_last_ranges) ) {
                    $price_range = br_get_cache( 'price_range', $wp_check_product_cat );
                    if ( $price_range === false ) {
                        $price_range = BeRocket_AAPF_Widget::get_price_range( $wp_query_product_cat, $woocommerce_hide_out_of_stock_items );
                        br_set_cache( 'price_range', $price_range, $wp_check_product_cat, BeRocket_AJAX_cache_expire );
                    }
                }
                for ( $i = 1; $i < count( $ranges ); $i++ ) {
                    $add_term_ranges = true;
                    if( ! empty($hide_first_last_ranges) ) {
                        if ( ! empty( $price_range ) and count( $price_range ) >= 2 ) {
                            if( $price_range[0] >= $ranges[$i] ) {
                                $add_term_ranges = false;
                            }
                            if( $price_range[1] <= $ranges[$i - 1] ) {
                                $add_term_ranges = false;
                            }
                        }
                    }
                    if( $add_term_ranges ) {
                        $t_id = ($ranges[$i - 1] + 1).'*'.$ranges[$i];
                        $t_name = ( ! empty( $icon_before_value ) ? ( ( substr( $icon_before_value, 0, 3) == 'fa-' ) ? '<i class="fa '.$icon_before_value.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon_before_value.'" alt=""></i>' ) : '' ).$text_before_price.(apply_filters( 'woocommerce_price_filter_widget_min_amount', $ranges[$i - 1]) + 1).$text_after_price.( ! empty( $icon_after_value ) ? ( ( substr( $icon_after_value, 0, 3) == 'fa-' ) ? '<i class="fa '.$icon_after_value.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon_after_value.'" alt=""></i>' ) : '' ).
                        ' - '.
                        ( ! empty( $icon_before_value ) ? ( ( substr( $icon_before_value, 0, 3) == 'fa-' ) ? '<i class="fa '.$icon_before_value.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon_before_value.'" alt=""></i>' ) : '' ).$text_before_price.apply_filters( 'woocommerce_price_filter_widget_max_amount', $ranges[$i]).$text_after_price.( ! empty( $icon_after_value ) ? ( ( substr( $icon_after_value, 0, 3) == 'fa-' ) ? '<i class="fa '.$icon_after_value.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon_after_value.'" alt=""></i>' ) : '' );
                        $term = array( 'term_id' => $t_id, 'slug' => $t_id, 'name' => $t_name, 'count' => 1, 'taxonomy' => $attribute );
                        $term = (object)$term;
                        if( ! empty($br_options['recount_products']) && ! empty($show_product_count_per_attr) ) {
                            $this->price_range_count($term, $ranges[$i - 1], $ranges[$i]);
                        }
                        $terms[] = $term;
                    }
                }
            } elseif ( $attr_type == '_stock_status' ) {
                $terms_ready = true;
                $terms = array();
                array_push($terms, (object)array('term_id' => '1', 'name' => __('In stock', 'BeRocket_AJAX_domain'), 'slug' => 'instock', 'taxonomy' => '_stock_status', 'count' => 1));
                array_push($terms, (object)array('term_id' => '2', 'name' => __('Out of stock', 'BeRocket_AJAX_domain'), 'slug' => 'outofstock', 'taxonomy' => '_stock_status', 'count' => 1));
                $terms = BeRocket_AAPF_Widget::get_attribute_values( $attr_type, 'id', ( empty($br_options['show_all_values']) ), ( ! empty($br_options['recount_products']) ), $terms, ( isset($cat_value_limit) ? $cat_value_limit : null ), $operator );
            } elseif ( $attr_type == '_sale' ) {
                $terms_ready = true;
                $terms = array();
                array_push($terms, (object)array('term_id' => '1', 'name' => __('On sale', 'BeRocket_AJAX_domain'), 'slug' => 'sale', 'taxonomy' => '_sale', 'count' => 1));
                array_push($terms, (object)array('term_id' => '2', 'name' => __('Not on sale', 'BeRocket_AJAX_domain'), 'slug' => 'notsale', 'taxonomy' => '_sale', 'count' => 1));
                $terms = BeRocket_AAPF_Widget::get_attribute_values( $attr_type, 'id', ( empty($br_options['show_all_values']) ), ( ! empty($br_options['recount_products']) ), $terms, ( isset($cat_value_limit) ? $cat_value_limit : null ), $operator );
            }
        } elseif( $attr_filter_type == 'date' ) {
            $terms_ready = true;
            $type = 'date';
        }
        return array($terms_error_return, $terms_ready, $terms, $type);
    }
    function widget_load_template_name($name) {
        if( in_array($name, array('date', 'ranges')) ) {
            $name = 'paid/'.$name;
        }
        return $name;
    }
    function price_range_count($term, $from, $to) {
        if( class_exists('WP_Meta_Query') && class_exists('WP_Tax_Query') ) {
            global $wpdb, $wp_query;
            $old_join_posts = '';
            $has_new_function = method_exists('WC_Query', 'get_main_query') && method_exists('WC_Query', 'get_main_meta_query') && method_exists('WC_Query', 'get_main_tax_query');
            if( $has_new_function ) {
                $WC_Query_get_main_query = WC_Query::get_main_query();
                $has_new_function = ! empty($WC_Query_get_main_query);
            }
            if( ! $has_new_function ) {
                $old_query_vars = BeRocket_AAPF_Widget::old_wc_compatible($wp_query);
                $old_meta_query = (empty( $old_query_vars[ 'meta_query' ] ) || ! is_array($old_query_vars[ 'meta_query' ]) ? array() : $old_query_vars['meta_query']);
                $old_tax_query = (empty($old_query_vars['tax_query']) || ! is_array($old_query_vars[ 'tax_query' ]) ? array() : $old_query_vars['tax_query']);
            } else {
                $old_query_vars = BeRocket_AAPF_Widget::old_wc_compatible($wp_query, true);
            }
            if( ! empty( $old_query_vars['posts__in'] ) ) {
                $old_join_posts = " AND {$wpdb->posts}.ID IN (".implode(',', $old_query_vars['posts__in']).") ";
            }
            if( $has_new_function ) {
                $tax_query  = WC_Query::get_main_tax_query();
            } else {
                $tax_query = $old_tax_query;
            }
            if( $has_new_function ) {
                $meta_query  = WC_Query::get_main_meta_query();
            } else {
                $meta_query = $old_meta_query;
            }
            foreach( $meta_query as $key => $val ) {
                if( is_array($val) ) {
                    if ( ! empty( $val['price_filter'] ) || ! empty( $val['rating_filter'] ) ) {
                        unset( $meta_query[ $key ] );
                    }
                    if( isset( $val['relation']) ) {
                        unset($val['relation']);
                        foreach( $val as $key2 => $val2 ) {
                            if ( isset( $val2['key'] ) && $val2['key'] == '_price' ) {
                                if ( isset( $meta_query[ $key ][ $key2 ] ) ) unset( $meta_query[ $key ][ $key2 ] );
                            }
                        }
                        if( count($meta_query[ $key ]) <= 1 ) {
                            unset( $meta_query[ $key ] );
                        }
                    } else {
                        if ( isset( $val['key'] ) && $val['key'] == '_price' ) {
                            if ( isset( $meta_query[ $key ] ) ) unset( $meta_query[ $key ] );
                        }
                    }
                }
            }
            $queried_object = $wp_query->get_queried_object_id();
            if( ! empty($queried_object) ) {
                $query_object = $wp_query->get_queried_object();
                if( ! empty($query_object->taxonomy) && ! empty($query_object->slug) ) {
                    $tax_query[ $query_object->taxonomy ] = array(
                        'taxonomy' => $query_object->taxonomy,
                        'terms'    => array( $query_object->slug ),
                        'field'    => 'slug',
                    );
                }
            }
            $meta_query      = new WP_Meta_Query( $meta_query );
            $tax_query       = new WP_Tax_Query( $tax_query );
            $meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
            $tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );

            // Generate query
            $query           = array();
            $query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as range_count";
            $query['from']   = "FROM {$wpdb->posts}";
            $query['join']   = "
                INNER JOIN {$wpdb->postmeta} AS price_term ON {$wpdb->posts}.ID = price_term.post_id
                " . $tax_query_sql['join'] . $meta_query_sql['join'];
            $query['where']   = "
                WHERE {$wpdb->posts}.post_type IN ( 'product' )
                AND " . br_select_post_status() . "
                " . $tax_query_sql['where'] . $meta_query_sql['where'] . "
                AND price_term.meta_key = '_price' AND price_term.meta_value >= {$from} AND price_term.meta_value <= {$to}
            ";
            if( defined( 'WCML_VERSION' ) && defined('ICL_LANGUAGE_CODE') ) {
                $query['join'] = $query['join']." INNER JOIN {$wpdb->prefix}icl_translations as wpml_lang ON ( {$wpdb->posts}.ID = wpml_lang.element_id )";
                $query['where'] = $query['where']." AND wpml_lang.language_code = '".ICL_LANGUAGE_CODE."' AND wpml_lang.element_type = 'post_product'";
            }
            br_where_search( $query );
            $query['where'] .= $old_join_posts;
            $query             = apply_filters( 'woocommerce_get_filtered_ranges_product_counts_query', $query );
            $query             = implode( ' ', $query );

            $results           = $wpdb->get_results( $query );
            if( isset( $results[0]->range_count ) ) {
                $term->count = $results[0]->range_count;
            }
        }
        return $term;
    }
    function add_terms( $filtered_posts ) {
        if ( empty($_POST['add_terms']) ) {
            return $filtered_posts;
        }
        global $berocket_post_before_add_terms;
        if( ! empty($_POST['add_terms']) && is_array($_POST['add_terms']) ) {
            if( ! isset($berocket_post_before_add_terms) ) {
                $berocket_post_before_add_terms = $filtered_posts;
            }
            $add_terms = array('_sale' => array());
            foreach($_POST['add_terms'] as $terms) {
                if( isset($add_terms[$terms[0]]) ) {
                    $add_terms[$terms[0]][] = $terms[1];
                }
            }
            foreach($add_terms as $term_name => $terms) {
                if( count($terms) > 0 ) {
                    $term_posts = array(0);
                    if($term_name == '_sale') {
                        if( in_array('2', $terms) ) {
                            $products = $this->wc_get_product_ids_not_on_sale();
                            $term_posts = array_merge($term_posts, $products);
                            unset($products);
                        }
                        if( in_array('1', $terms) ) {
                            $products = wc_get_product_ids_on_sale();
                            $term_posts = array_merge($term_posts, $products);
                            unset($products);
                        }
                    }
                    if ( sizeof( $filtered_posts ) == 0 ) {
                        $filtered_posts = $term_posts;
                    } else {
                        $filtered_posts = array_intersect( $filtered_posts, $term_posts );
                    }
                }
            }
        }
        return $filtered_posts;
    }
    function wc_get_product_ids_not_on_sale() {
        global $wpdb;

        // Load from cache
        $product_ids_not_on_sale = get_transient( 'wc_products_notonsale' );

        // Valid cache found
        if ( false !== $product_ids_not_on_sale ) {
            return $product_ids_not_on_sale;
        }
        delete_transient( 'wc_products_onsale' );
        $product_ids_on_sale = wc_get_product_ids_on_sale();

        $on_sale_posts = $wpdb->get_results( "
            SELECT post.ID, post.post_parent FROM `$wpdb->posts` AS post
            LEFT JOIN `$wpdb->postmeta` AS meta ON post.ID = meta.post_id
            WHERE post.post_type IN ( 'product', 'product_variation' )
                AND post.post_status = 'publish'
                AND meta.meta_key = '_price'
                AND post.ID NOT IN (".implode(',', $product_ids_on_sale).")
                AND post.post_parent NOT IN (".implode(',', $product_ids_on_sale).")
            GROUP BY post.ID;
        " );

        $product_ids_not_on_sale = array_unique( array_map( 'absint', array_merge( wp_list_pluck( $on_sale_posts, 'ID' ), array_diff( wp_list_pluck( $on_sale_posts, 'post_parent' ), array( 0 ) ) ) ) );

        set_transient( 'wc_products_notonsale', $product_ids_not_on_sale, DAY_IN_SECONDS * 30 );

        return $product_ids_not_on_sale;
    }
    function widget_display_custom_filter($return, $widget_type, $instance, $args, $widget_instance) {
        if ( $widget_type == 'search_box' ) {
            extract($instance);
            extract($args);
            if( $search_box_link_type == 'shop_page' ) {
                if( function_exists('wc_get_page_id') ) {
                    $search_box_url = get_permalink( wc_get_page_id( 'shop' ) );
                } else {
                    $search_box_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
                }
            } elseif( $search_box_link_type == 'category' ) {
                $search_box_url = get_term_link( $search_box_category, 'product_cat' );
            }
            $sb_style = '';
            if ( $search_box_style['position'] == 'horizontal' ) {
                $sb_count = $search_box_count;
                if( $search_box_style['search_position'] == 'before_after' ) {
                    $sb_count += 2;
                } else {
                    $sb_count++;
                }
                $search_box_width = (int)(100 / $sb_count);
                $sb_style .= 'width:'.$search_box_width.'%;display:inline-block;padding: 4px;';
            }
            echo $before_widget;
            $search_box_button_class = 'search_box_button_class_'.rand();
            $sbb_style = '';
            if( ! empty($search_box_style['background']) ) {
                $sbb_style .= 'background-color:'.($search_box_style['background'][0] == '#' ? $search_box_style['background'] : '#'.$search_box_style['background']).';';
            }
            $sbb_style .= 'opacity:'.$search_box_style['back_opacity'].';';
            if( ! empty($title) ) { ?><h3 class="widget-title berocket_aapf_widget-title" style="<?php echo ( empty($uo['style']['title']) ? '' : $uo['style']['title'] ) ?>"><span><?php echo $title; ?></span></h3><?php }
            echo '<div class="berocket_search_box_block">';
            echo '<div class="berocket_search_box_background" style="'.$sbb_style.'"></div>';
            echo '<div class="berocket_search_box_background_all">';
            $sbb_style = '';
            if( ! empty($search_box_style['button_background']) ) {
                $sbb_style .= 'background-color:'.($search_box_style['button_background'][0] == '#' ? $search_box_style['button_background'] : '#'.$search_box_style['button_background']).';';
            }
            if( ! empty($search_box_style['text_color']) ) {
                $sbb_style .= 'color:'.($search_box_style['text_color'][0] == '#' ? $search_box_style['text_color'] : '#'.$search_box_style['text_color']).';';
            }
            if( ! empty($search_box_style['button_background_over']) ) {
                $sbb_style_hover = 'background-color:'.($search_box_style['button_background_over'][0] == '#' ? $search_box_style['button_background_over'] : '#'.$search_box_style['button_background_over']).';';
            }
            if( ! empty($search_box_style['text_color_over']) ) {
                $sbb_style_hover .= 'color:'.($search_box_style['text_color_over'][0] == '#' ? $search_box_style['text_color_over'] : '#'.$search_box_style['text_color_over']).';';
            }
            if ( $search_box_style['search_position'] == 'before' || $search_box_style['search_position'] == 'before_after' ) {
                echo '<div style="'.$sb_style.'"><a data-url="'.$search_box_url.'" class="'.$search_box_button_class.' berocket_search_box_button">'.$search_box_style['search_text'].'</a></div>';
            }
            for($i = 1; $i <= $search_box_count; $i++) {
                echo '<div style="'.$sb_style.'">';
                $current_box = $search_box_attributes[$i];
                $widget_search = new BeRocket_AAPF_Widget();
                $BeRocket_AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
                $search_instance = $BeRocket_AAPF_single_filter->default_settings;
                $search_instance['filter_type'] = ( empty($current_box['type']) ? '' : $current_box['type'] );
                $search_instance['attribute'] = ( empty($current_box['attribute']) ? '' : $current_box['attribute'] );
                $search_instance['custom_taxonomy'] = ( empty($current_box['custom_taxonomy']) ? '' : $current_box['custom_taxonomy'] );
                $search_instance['type'] = ( empty($current_box['visual_type']) ? '' : $current_box['visual_type'] );
                $search_instance['height'] = ( empty($current_box['height']) ? '' : $current_box['height'] );
                $search_instance['scroll_theme'] = ( empty($current_box['scroll_theme']) ? '' : $current_box['scroll_theme'] );
                $search_instance['selected_area_show'] = ( empty($current_box['selected_area_show']) ? '' : $current_box['selected_area_show'] );
                $search_instance['hide_selected_arrow'] = ( empty($current_box['hide_selected_arrow']) ? '' : $current_box['hide_selected_arrow'] );
                $search_instance['selected_is_hide'] = ( empty($current_box['selected_is_hide']) ? '' : $current_box['selected_is_hide'] );
                $search_instance['is_hide_mobile'] = ( empty($current_box['is_hide_mobile']) ? '' : $current_box['is_hide_mobile'] );
                $search_instance['cat_propagation'] = ( empty($current_box['cat_propagation']) ? '' : $current_box['cat_propagation'] );
                $search_instance['cat_propagation'] = ( empty($current_box['cat_propagation']) ? '' : $current_box['cat_propagation'] );
                $search_instance['product_cat'] = ( empty($current_box['product_cat']) ? '' : $current_box['product_cat'] );
                $search_instance['show_page'] = ( empty($current_box['show_page']) ? '' : $current_box['show_page'] );
                $search_instance['cat_value_limit'] = ( empty($current_box['cat_value_limit']) ? '' : $current_box['cat_value_limit'] );
                $search_instance['widget_id'] = $widget_instance->id;
                $search_instance['widget_id_number'] = $widget_instance->number;

                $widget_search->widget(array('before_widget' => '<h4>'.$current_box['title'].'</h4>', 'after_widget' =>''), $search_instance);
                echo '</div>';
            }
            if ( $search_box_style['search_position'] == 'after' || $search_box_style['search_position'] == 'before_after' ) {
                echo '<div style="'.$sb_style.'">
                <a data-url="'.$search_box_url.'" 
                class="'.$search_box_button_class.' berocket_search_box_button">
                '.$search_box_style['search_text'].'</a></div>';
            }
            echo '</div></div>';
            echo '<style>.'.$search_box_button_class.'{'.$sbb_style.'}.'.$search_box_button_class.':hover{'.$sbb_style_hover.'}</style>';
            echo $after_widget;
            $return = true;
        }
        if( !( $instance['filter_type'] == 'attribute'
        && ( $instance['attribute'] == 'price' || $instance['attribute'] == 'product_cat' ) )
        || $instance['filter_type'] == 'product_cat'
        || $instance['filter_type'] == '_stock_status'
        || $instance['filter_type'] == 'tag'
        || $instance['type'] == 'slider' ) {
            if( ! empty($instance['child_parent']) && $instance['child_parent'] == 'depth' ) {
                $count = ( empty($instance['child_onew_count']) ? '' : $instance['child_onew_count'] );
                $title = ( empty($instance['title']) ? '' : $instance['title'] );
                $instance['child_parent'] = 'parent';
                $childs = ( empty($instance['child_onew_childs']) ? '' : $instance['child_onew_childs'] );
                
                $BeRocket_AAPF_Widget = new BeRocket_AAPF_Widget();
                $BeRocket_AAPF_Widget->widget( $args, $instance );
                $instance['child_parent'] = 'child';
                for( $i = 1; $i <= $count; $i++ ) {
                    $child = $childs[$i];
                    $new_args = $args;
                    $instance['child_parent_depth'] = $i;
                    $instance['title'] = $childs[$i]['title'];
                    $instance['child_parent_no_values'] = ( empty($childs[$i]['no_values']) ? '' : $childs[$i]['no_values'] );
                    $instance['child_parent_previous'] = ( empty($childs[$i]['previous']) ? '' : $childs[$i]['previous'] );
                    $instance['child_parent_no_products'] = ( empty($childs[$i]['no_product']) ? '' : $childs[$i]['no_product'] );
                    $BeRocket_AAPF_Widget = new BeRocket_AAPF_Widget();
                    $BeRocket_AAPF_Widget->widget( $new_args, $instance );
                }
                $return = true;
            }
        }
        return $return;
    }
    function filter_term_name($name, $term) {
        $show_product_count_per_attr = get_query_var('show_product_count_per_attr');
        if( ! empty($show_product_count_per_attr) ) {
            $name = $name . ' <span class="berocket_aapf_count">' . berocket_isset($term, 'count') . '</span>';
        }
        return $name;
    }
    function temp_meta_class_init($meta_class, $term) {
        $show_product_count_per_attr = get_query_var('show_product_count_per_attr');
        if( ! empty($show_product_count_per_attr) ) {
            $meta_class = berocket_isset($term, 'count');
        }
        return $meta_class;
    }
    function temp_meta_class_ready($vars, $term, $meta_color_init) {
        list($meta_class, $meta_after, $meta_color) = $vars;
        $show_product_count_per_attr = get_query_var('show_product_count_per_attr');
        if( ! empty($show_product_count_per_attr) ) {
            $type = get_query_var('type');
            if( $type == 'color' ) {
                if( count($meta_color_init) != 1 ) {
                    $meta_class .= '<span class="berocket_color_span_absolute"><span>'.berocket_isset($term, 'count').'</span></span>';
                }
            } elseif( $type == 'image' ) {
                if ( ! empty($meta_color_init[0]) ) {
                    $meta_after = '<span class="berocket_aapf_count">'.$term->count.'</span>';
                }
            }
        }
        return array($meta_class, $meta_after, $meta_color);
    }
    function temp_span_class($class, $vars, $term) {
        list($meta_class, $meta_after, $meta_color) = $vars;
        $show_product_count_per_attr = get_query_var('show_product_count_per_attr');
        if( ! empty($show_product_count_per_attr) && empty($meta_after) ) {
            $class .= ' berocket_aapf_count';
        }
        return $class;
    }
    function start_temp_class($class) {
        $child_parent = get_query_var('child_parent');
        $attribute = get_query_var('attribute');
        $child_parent_depth = get_query_var('child_parent_depth');
        $values_per_row = get_query_var('values_per_row');
        if( $child_parent == 'child' ) {
            $class .= ' '.$attribute.'_'.$child_parent.'_'.berocket_isset($child_parent_depth);
        }
        if( ! empty($values_per_row) ) {
            $class .= ' '.'berocket_values_'.$values_per_row;
        }
        return $class;
    }
    function hook_include_exclude_items($terms, $instance) {
        $include_exclude_select = br_get_value_from_array($instance, 'include_exclude_select');
        $include_exclude_list = br_get_value_from_array($instance, 'include_exclude_list');
        $terms = $this->include_exclude_items($terms, $include_exclude_select, $include_exclude_list);
        return $terms;
    }

    function include_exclude_items($terms, $include_exclude_select, $include_exclude_list) {
        if ( isset($terms) && is_array($terms) && count( $terms ) > 0 ) {
            if( $include_exclude_select == 'include' ) {
                $new_terms = array();
                foreach($terms as $term) {
                    if( in_array($term->term_id, $include_exclude_list) ) {
                        $new_terms[] = $term;
                    }
                }
                $terms = $new_terms;
            } elseif( $include_exclude_select == 'exclude' ) {
                $new_terms = array();
                foreach($terms as $term) {
                    if( ! in_array($term->term_id, $include_exclude_list) ) {
                        $new_terms[] = $term;
                    }
                }
                $terms = $new_terms;
            }
        }
        return $terms;
    }
}
new BeRocket_AAPF_paid();
