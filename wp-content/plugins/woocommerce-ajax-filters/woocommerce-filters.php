<?php
/**
 * Plugin Name: WooCommerce AJAX Products Filter
 * Plugin URI: http://berocket.com/product/woocommerce-ajax-products-filter
 * Description: Unlimited AJAX products filters to make your shop perfect
 * Version: 2.0.9.3
 * Author: BeRocket
 * Requires at least: 4.0
 * Author URI: http://berocket.com
 * License: Berocket License
 * License URI: http://berocket.com/license
 * Text Domain: BeRocket_AJAX_domain
 * Domain Path: /languages/
 * WC tested up to: 3.4.4
 */
define( "BeRocket_AJAX_filters_version", '2.0.9.3' );
define( "BeRocket_AJAX_domain", 'BeRocket_AJAX_domain' ); 
define( "BeRocket_AJAX_cache_expire", '21600' ); 

define( "AAPF_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );

load_plugin_textdomain('BeRocket_AJAX_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

require_once dirname( __FILE__ ) . '/includes/admin_notices.php';
require_once dirname( __FILE__ ) . '/includes/functions.php';
require_once dirname( __FILE__ ) . '/includes/updater.php';
require_once dirname( __FILE__ ) . '/includes/wizard.php';
require_once dirname( __FILE__ ) . '/includes/paid.php';
require_once dirname( __FILE__ ) . '/includes/widget.php';
require_once dirname( __FILE__ ) . '/includes/new_filters.php';
require_once dirname( __FILE__ ) . '/includes/new_widget.php';
require_once dirname( __FILE__ ) . '/includes/divi-builder.php';
require_once dirname( __FILE__ ) . '/includes/visual-composer.php';
require_once dirname( __FILE__ ) . '/wizard/main.php';
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
foreach (glob(plugin_dir_path( __FILE__ ) . "includes/compatibility/*.php") as $filename)
{
    include_once($filename);
}

/**
 * Class BeRocket_AAPF
 */

$br_aapf_debugs = array();
class BeRocket_AAPF {
    public static $debug_mode = false;
    public static $error_log = array();
    public static $info = array( 
        'id'        => 1,
        'version'   => BeRocket_AJAX_filters_version,
        'plugin'    => '',
        'slug'      => '',
        'key'       => '',
        'name'      => ''
    );

    public static $defaults = array(
        'plugin_key'                      => '',
        'no_products_message'             => 'There are no products meeting your criteria',
        'pos_relative'                    => '1',
        'no_products_class'               => '',
        'products_holder_id'              => 'ul.products',
        'woocommerce_result_count_class'  => '.woocommerce-result-count',
        'woocommerce_ordering_class'      => 'form.woocommerce-ordering',
        'woocommerce_pagination_class'    => '.woocommerce-pagination',
        'woocommerce_removes'             => array(
            'result_count'                => '',
            'ordering'                    => '',
            'pagination'                  => '',
        ),
        'products_per_page'               => '',
        'control_sorting'                 => '',
        'use_links_filters'               => '',
        'seo_friendly_urls'               => '1',
        'seo_uri_decode'                  => '1',
        'slug_urls'                       => '',
        'nice_urls'                       => '',
        'canonicalization'                => '',
        'filters_turn_off'                => '',
        'show_all_values'                 => '',
        'hide_value'                      => array(
            'o'                           => '1',
            'sel'                         => '',
            'empty'                       => '',
            'button'                      => '',
        ),
        'use_select2'                     => '',
        'fixed_select2'                   => '',
        'first_page_jump'                 => '1',
        'scroll_shop_top'                 => '',
        'scroll_shop_top_px'              => '-180',
        'recount_products'                => '',
        'selected_area_show'              => '',
        'selected_area_hide_empty'        => '',
        'products_only'                   => '1',
        'out_of_stock_variable'           => '',
        'object_cache'                    => '',
        'ub_product_count'                => '1',
        'ub_product_text'                 => 'products',
        'ub_product_button_text'          => 'Show',
        'ajax_request_load'               => '1',
        'use_get_query'                   => '',
        'ajax_request_load_style'         => 'jquery',
        'slider_250_fix'                  => '',
        'slider_compatibility'            => '',
        'styles_in_footer'                => '',
        'product_per_row'                 => '4',
        
        'styles_input'                    => array(
            'checkbox'               => array( 'bcolor' => '', 'bwidth' => '', 'bradius' => '', 'fcolor' => '', 'backcolor' => '', 'icon' => '', 'fontsize' => '', 'theme' => '' ),
            'radio'                  => array( 'bcolor' => '', 'bwidth' => '', 'bradius' => '', 'fcolor' => '', 'backcolor' => '', 'icon' => '', 'fontsize' => '', 'theme' => '' ),
            'slider'                 => array( 'line_color' => '', 'line_height' => '', 'line_border_color' => '', 'line_border_width' => '', 'button_size' => '', 
                                               'button_color' => '', 'button_border_color' => '', 'button_border_width' => '', 'button_border_radius' => '' ),
            'pc_ub'                  => array( 'back_color' => '', 'border_color' => '', 'font_size' => '', 'font_color' => '', 'show_font_size' => '', 'close_size' => '', 
                                               'show_font_color' => '', 'show_font_color_hover' => '', 'close_font_color' => '', 'close_font_color_hover' => '' ),
            'product_count'          => 'round',
            'product_count_position' => '',
        ),
        'ajax_load_icon'                  => '',
        'ajax_load_text'                  => array(
            'top'                         => '',
            'bottom'                      => '',
            'left'                        => '',
            'right'                       => '',
        ),
        'description'                     => array(
            'show'                        => 'click',
            'hide'                        => 'click',
        ),
        'user_func'                       => array(
            'before_update'               => '',
            'on_update'                   => '',
            'after_update'                => '',
        ),
        'user_custom_css'                 => '',
        'br_opened_tab'                   => 'general',
        'number_style'                    => array(
            'thousand_separate' => '',
            'decimal_separate'  => '.',
            'decimal_number'    => '2',
        ),
        'tags_custom'                     => '1',
        'ajax_site'                       => '',
        'search_fix'                      => '1',
        'disable_font_awesome'            => '',
        'debug_mode'                      => '',
    );
    public static $default_permalink = array (
        'variable' => 'filters',
        'value'    => '/values',
        'split'    => '/',
    );
    public static $values = array(
        'settings_name' => '',
        'option_page'   => 'br-product-filters',
        'premium_slug'  => 'woocommerce-ajax-products-filter',
    );

    function __construct() {
        if(!session_id()) {
            session_start();
        }
        if( is_admin() ) {
            require_once dirname( __FILE__ ) . '/includes/wizard.php';
        }
        $error_log['000_select_status'] = array();
        register_activation_hook( __FILE__, array( __CLASS__, 'br_add_defaults' ) );
        register_uninstall_hook( __FILE__, array( __CLASS__, 'br_delete_plugin_options' ) );
        add_action( 'wpmu_new_blog', array( __CLASS__, 'new_blog' ), 10, 6 );
        add_filter( 'BeRocket_updater_add_plugin', array( __CLASS__, 'updater_info' ) );

        if ( ! function_exists('is_network_admin') || ! is_network_admin() ) {

            if ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) && br_get_woocommerce_version() >= 2.1 ) {
                $last_version = get_option('br_filters_version');
                if( $last_version === FALSE ) $last_version = 0;
                if ( version_compare($last_version, BeRocket_AJAX_filters_version, '<') ) {
                    self::update_from_older ( $last_version );
                }
                unset($last_version);

                $option = self::get_aapf_option();
                if( class_exists('BeRocket_updater') && property_exists('BeRocket_updater', 'debug_mode') ) {
                    self::$debug_mode = ! empty(BeRocket_updater::$debug_mode);
                }
                add_filter( 'BeRocket_updater_error_log', array( __CLASS__, 'add_error_log' ) );
                if ( BeRocket_AAPF::$debug_mode ) {
                    BeRocket_AAPF::$error_log['1_settings'] = $option;
                }

                add_filter('berocket_aapf_convert_limits_to_tax_query', array(__CLASS__, 'convert_limits_to_tax_query'), 10, 2);

                add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
                add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
                add_action( 'current_screen', array( __CLASS__, 'register_permalink_option' ) );
                add_action( 'wp_head', array( __CLASS__, 'wp_head' ) );
                if( ! empty($option['use_filtered_variation']) ) {
                    add_filter( 'woocommerce_loop_product_link', array( __CLASS__, 'woocommerce_loop_product_link' ), 1, 2 );
                }
                if( empty($option['styles_in_footer']) ) {
                    add_action( 'wp_head', array( __CLASS__, 'br_custom_user_css' ) );
                }
                add_shortcode( 'br_filters', array( __CLASS__, 'shortcode' ) );
                add_action( 'init', array( __CLASS__, 'create_metadata_table' ), 999999999 );
                add_action( 'br_footer_script', array( __CLASS__, 'include_all_scripts' ) );
                add_action( 'admin_menu', array( __CLASS__, 'br_add_options_page' ) );
                add_action( 'delete_transient_wc_products_onsale', array( __CLASS__, 'delete_products_not_on_sale' ) );

                add_action ( 'widgets_init', array( __CLASS__, 'widgets_init' ));
                if ( defined('DOING_AJAX') && DOING_AJAX ) {
                    $this->ajax_functions();
                }
                if ( ! defined('DOING_AJAX') || ! DOING_AJAX ) {
                    $this->not_ajax_functions();
                }
                if ( !empty( $option['nice_urls'] ) ) {
                    add_action( 'init', array( __CLASS__, 'init' ) );
                    add_filter( 'rewrite_rules_array', array( __CLASS__, 'add_rewrite_rules' ), 999999999 );
                    add_filter( 'query_vars', array( __CLASS__, 'add_queryvars' ) );
                }

                if ( isset($_GET['explode']) && $_GET['explode'] == 'explode') {
                    add_action( 'woocommerce_before_template_part', array( 'BeRocket_AAPF_Widget', 'pre_get_posts'), 999999 );
                    add_action( 'wp_footer', array( 'BeRocket_AAPF_Widget', 'end_clean'), 999999 );
                    add_action( 'init', array( 'BeRocket_AAPF_Widget', 'start_clean'), 1 );
                } else {
                    add_action( 'woocommerce_before_template_part', array( 'BeRocket_AAPF_Widget', 'rebuild'), 999999 );
                }

                if( is_array(br_get_value_from_array($option, 'elements_above_products')) && count($option['elements_above_products']) ) {
                    add_action ( br_get_value_from_array($option, 'elements_position_hook', 'woocommerce_archive_description'), array(__CLASS__, 'elements_above_products'), 1 );
                }
                if ( ! empty($option['selected_area_show']) ) {
                    add_action ( br_get_value_from_array($option, 'elements_position_hook', 'woocommerce_archive_description'), array(__CLASS__, 'selected_area'), 1 );
                }
                if ( ! empty($option['products_per_page']) && ! br_is_plugin_active( 'list-grid' ) && ! br_is_plugin_active( 'List_Grid' ) && ! br_is_plugin_active( 'more-products' ) && ! br_is_plugin_active( 'Load_More_Products' ) ) {
                    add_filter( 'loop_shop_per_page', array(__CLASS__, 'products_per_page_set'), 9999 );
                }
                if( ! empty($option['ajax_site']) ) {
                    add_action( 'wp_enqueue_scripts', array( __CLASS__, 'include_all_scripts' ) );
                }
                if( ! empty($option['search_fix']) ) {
                    add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
                }
                if( ! empty($option['canonicalization']) ) {
                    add_action('wp_head', array(__CLASS__, 'wp_head_canonical'));
                }
                if( ! empty($option['products_only']) ) {
                    add_filter('woocommerce_is_filtered', array(__CLASS__, 'woocommerce_is_filtered'));
                }
                if( ! empty($option['out_of_stock_variable']) ) {
                    include_once( dirname( __FILE__ ) . '/includes/woocommerce-variation.php' );
                }
                add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
                $plugin_base_slug = plugin_basename( __FILE__ );
                add_filter( 'plugin_action_links_' . $plugin_base_slug, array( __CLASS__, 'plugin_action_links' ) );
                add_filter( 'berocket_aapf_widget_terms', array(__CLASS__, 'wpml_attribute_slug_translate'));
                add_filter( 'is_active_sidebar', array(__CLASS__, 'is_active_sidebar'), 10, 2);
            } else {
                if( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
                    add_action( 'admin_notices', array( __CLASS__, 'update_woocommerce	' ) );
                } else {
                    add_action( 'admin_notices', array( __CLASS__, 'no_woocommerce' ) );
                }
            }
        }
    }
    public static function is_active_sidebar($is_active_sidebar, $index) {
        if( $is_active_sidebar ) {
            $sidebars_widgets = wp_get_sidebars_widgets();
            $sidebars_widgets = $sidebars_widgets[$index];
            global $wp_registered_widgets;
            $test = $wp_registered_widgets;
            if( is_array($sidebars_widgets) ) {
                foreach($sidebars_widgets as $widgets) {
                    if( strpos($widgets, 'berocket_aapf_group') === false && strpos($widgets, 'berocket_aapf_single') === false ) {
                        return $is_active_sidebar;
                    }
                }
                foreach($sidebars_widgets as $widgets) {
                    $widget_id = br_get_value_from_array($wp_registered_widgets, array($widgets, 'params', 0));
                    if( empty($widget_id) ) continue;
                    if( strpos($widgets, 'berocket_aapf_group') === false ) {
                        $widget_instances = get_option('widget_berocket_aapf_single');
                        $filters = br_get_value_from_array($widget_instances, $widget_id);
                        if( BeRocket_new_AAPF_Widget_single::check_widget_by_instance($filters) ) {
                            return $is_active_sidebar;
                        }
                    } else {
                        $widget_instances = get_option('widget_berocket_aapf_group');
                        $filters = br_get_value_from_array($widget_instances, $widget_id);
                        if( BeRocket_new_AAPF_Widget::check_widget_by_instance($filters) ) {
                            return $is_active_sidebar;
                        }
                    }
                }
                $is_active_sidebar = false;
            }
        }
        return $is_active_sidebar;
    }
    public static function products_per_page_set() {
        $option = self::get_aapf_option();
        return $option['products_per_page'];
    }
    public static function wpml_attribute_slug_translate($terms) {
        if( ! empty($terms) && is_array($terms) ) {
            foreach($terms as &$term) {
                $taxonomy = berocket_isset($term, 'taxonomy');
                if( ! empty($taxonomy) ) {
                    $taxonomy = preg_replace( '#^pa_#', '', $taxonomy );
                    $wpml_taxonomy = berocket_wpml_attribute_translate($taxonomy);
                    if( $taxonomy != $wpml_taxonomy ) {
                        $term->wpml_taxonomy = 'pa_'.$wpml_taxonomy;
                    }
                }
            }
        }
        return $terms;
    }
    public static function plugin_action_links($links) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page='.self::$values['option_page'] ) . '" title="' . __( 'View Plugin Settings', 'BeRocket_AJAX_domain' ) . '">' . __( 'Settings', 'BeRocket_AJAX_domain' ) . '</a>',
		);
		return array_merge( $action_links, $links );
    }
    public static function plugin_row_meta($links, $file) {
        $plugin_base_slug = plugin_basename( __FILE__ );
        if ( $file == $plugin_base_slug ) {
			$row_meta = array(
				'docs'    => '<a href="http://berocket.com/docs/plugin/'.self::$values['premium_slug'].'" title="' . __( 'View Plugin Documentation', 'BeRocket_AJAX_domain' ) . '" target="_blank">' . __( 'Docs', 'BeRocket_AJAX_domain' ) . '</a>',
				'premium'    => '<a href="http://berocket.com/support/product/'.self::$values['premium_slug'].'" title="' . __( 'View Premium Support Page', 'BeRocket_AJAX_domain' ) . '" target="_blank">' . __( 'Premium Support', 'BeRocket_AJAX_domain' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
    }

    function ajax_functions() {
        add_action( 'setup_theme', array( __CLASS__, 'WPML_fix' ) );
        add_action('plugins_loaded', array($this, 'wp_hook'));
        add_action( "wp_ajax_br_aapf_get_child", array ( __CLASS__, 'br_aapf_get_child' ) );
        add_action( "wp_ajax_nopriv_br_aapf_get_child", array ( __CLASS__, 'br_aapf_get_child' ) );
        add_action( "wp_ajax_aapf_color_set", array ( 'BeRocket_AAPF_Widget', 'color_listener' ) );
        BeRocket_AAPF_Widget::br_widget_ajax_set();
    }
    function wp_hook() {
        add_filter('loop_shop_columns', array( __CLASS__, 'loop_columns' ), 999 );
    }

    function not_ajax_functions() {
        add_filter( 'pre_get_posts', array( __CLASS__, 'apply_user_price' ) );
        add_filter( 'pre_get_posts', array( __CLASS__, 'apply_user_filters' ), 900000 );
        add_filter( 'woocommerce_shortcode_products_query', array( __CLASS__, 'woocommerce_shortcode_products_query' ), 10, 3 );
    }

    public static function widgets_init() {
        register_widget("BeRocket_AAPF_widget");
        register_widget("BeRocket_new_AAPF_Widget");
        register_widget("BeRocket_new_AAPF_Widget_single");
    }

    public static function add_rewrite_rules ( $rules ) {
        $option_permalink = get_option( 'berocket_permalink_option' );
        $values_split = $option_permalink['value'];
        $values_split = explode( 'values', $values_split );
        $newrules = array();
        $shop_slug = get_post(wc_get_page_id('shop'));
        $newrules[$option_permalink['variable'].'/(.*)/?'] = 'index.php?post_type=product&'.$option_permalink['variable'].'=$matches[1]';

        if ( ! empty( $shop_slug ) and is_object( $shop_slug ) and ! empty( $shop_slug->post_name ) ) {
            if ( br_get_woocommerce_version() >= 2.7 ) {
                $newrules[ $shop_slug->post_name . '/' . $option_permalink[ 'variable' ] . '/(.*)/?' ] = 'index.php?post_type=product&' . $option_permalink[ 'variable' ] . '=$matches[1]';
            } else {
                $newrules[ $shop_slug->post_name . '/' . $option_permalink[ 'variable' ] . '/(.*)/?' ] = 'index.php?pagename=' . $shop_slug->post_name . '&' . $option_permalink[ 'variable' ] . '=$matches[1]';
            }
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
                    $newrules[$product_taxonomy->rewrite['slug'].'/([^/]+)/'.$option_permalink['variable'].'/(.*)/?'] = 'index.php?'.$product_taxonomy->name.'=$matches[1]&'.$option_permalink['variable'].'=$matches[2]';
                } else {
                    $newrules[$product_taxonomy->name.'/([^/]+)/'.$option_permalink['variable'].'/(.*)/?'] = 'index.php?'.$product_taxonomy->name.'=$matches[1]&'.$option_permalink['variable'].'=$matches[2]';
                }
            }
        }

        return $newrules + $rules;
    }

    public static function wp_head_canonical() {
        global $wp_query;
        $option_permalink = get_option( 'berocket_permalink_option' );
        if( is_post_type_archive( 'product' ) ) {
            global $wp, $sitepress;
            $br_options = self::get_aapf_option();
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

    public static function init () {
        $option_permalink = get_option( 'berocket_permalink_option' );
        add_rewrite_endpoint($option_permalink['variable'], EP_PERMALINK|EP_SEARCH|EP_CATEGORIES|EP_TAGS|EP_PAGES);
        flush_rewrite_rules();
    }

    public static function woocommerce_is_filtered($filtered) {
        global $wp_query;
        $option_permalink = get_option( 'berocket_permalink_option' );
        if ( ! empty($_GET['filters']) || $wp_query->get( $option_permalink['variable'], '' ) ) {
            $filtered = true;
        }
        return $filtered;
    }

    public static function include_all_scripts() {
        /* theme scripts */
        if( defined('THE7_VERSION') && THE7_VERSION ) {
            wp_enqueue_script( 'berocket_ajax_fix-the7', plugins_url( 'js/themes/the7.js', __FILE__ ), array( 'jquery' ), BeRocket_AJAX_filters_version );
            add_filter('berocket_aapf_user_func', array(__CLASS__, 'the7_fix'));
        }
        global $wp_query, $wp, $sitepress;
        self::wp_print_special_scripts();
        $br_options = apply_filters( 'berocket_aapf_listener_br_options', BeRocket_AAPF::get_aapf_option() );
        if( ! empty($br_options['styles_in_footer']) ) {
            add_action( 'wp_footer', array( __CLASS__, 'br_custom_user_css' ) );
        }
        if( ! empty($br_options['user_func']) && is_array( $br_options['user_func'] ) ) {
            $user_func = array_merge( BeRocket_AAPF::$defaults['user_func'], $br_options['user_func'] );
        } else {
            $user_func = BeRocket_AAPF::$defaults['user_func'];
        }

        self::wp_print_footer_scripts();

        $wp_query_product_cat     = '-1';
        $wp_check_product_cat     = '1q1main_shop1q1';
        if ( ! empty($wp_query->query['product_cat']) ) {
            $wp_query_product_cat = explode( "/", $wp_query->query['product_cat'] );
            $wp_query_product_cat = $wp_query_product_cat[ count( $wp_query_product_cat ) - 1 ];
            $wp_check_product_cat = $wp_query_product_cat;
        }

        $post_temrs = "[]";
        if ( ! empty($_POST['terms']) ) {
            $post_temrs = json_encode( $_POST['terms'] );
        }

        if ( method_exists($sitepress, 'get_current_language') ) {
            $current_language = $sitepress->get_current_language();
        } else {
            $current_language = '';
        }

        $option_permalink = get_option( 'berocket_permalink_option' );
        $permalink_values = explode( 'values', $option_permalink['value'] );

        $current_page_url = preg_replace( "~paged?/[0-9]+/?~", "", home_url( $wp->request ) );
        if( ! empty($br_options['nice_urls']) ) {
            $current_page_url = preg_replace( "~".$option_permalink['variable']."/.+~", "", $current_page_url );
            $current_page_url = preg_replace( "~".urlencode($option_permalink['variable'])."/.+~", "", $current_page_url );
        }
        if( strpos($current_page_url, '?') !== FALSE ) {
            $current_page_url = explode('?', $current_page_url);
            $current_page_url = $current_page_url[0];
        }

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

        $product_taxonomy = '-1';
        if ( is_product_taxonomy() ) {
            $product_taxonomy = (empty($wp_query->query_vars['taxonomy']) ? '' : $wp_query->query_vars['taxonomy']).'|'.(empty($wp_query->query_vars['term']) ? '' : $wp_query->query_vars['term']);
        }

        $br_options['no_products_message'] = (empty($br_options['no_products_message']) ? __('There are no products meeting your criteria', 'BeRocket_AJAX_domain') : $br_options['no_products_message']);

        wp_localize_script(
            'berocket_aapf_widget-script',
            'the_ajax_script',
            apply_filters('aapf_localize_widget_script', array(
                'nice_url_variable'                    => $option_permalink['variable'],
                'nice_url_value_1'                     => $permalink_values[0],
                'nice_url_value_2'                     => $permalink_values[1],
                'nice_url_split'                       => $option_permalink['split'],
                'version'                              => BeRocket_AJAX_filters_version,
                'number_style'                         => array(
                    ( empty($br_options['number_style']['thousand_separate']) ? '' : $br_options['number_style']['thousand_separate'] ), 
                    ( empty($br_options['number_style']['decimal_separate']) ? '' : $br_options['number_style']['decimal_separate'] ), 
                    ( empty($br_options['number_style']['decimal_number']) ? '' : $br_options['number_style']['decimal_number'] )
                ),
                'current_language'                     => $current_language,
                'current_page_url'                     => $current_page_url,
                'ajaxurl'                              => admin_url( 'admin-ajax.php' ),
                'product_cat'                          => $wp_query_product_cat,
                'product_taxonomy'                     => $product_taxonomy,
                's'                                    => ( ! empty( $_GET['s'] ) ? $_GET['s'] : '' ),
                'products_holder_id'                   => ( empty($br_options['products_holder_id']) ? '' : $br_options['products_holder_id'] ),
                'result_count_class'                   => ( ! empty($br_options['woocommerce_result_count_class']) ? $br_options['woocommerce_result_count_class'] : BeRocket_AAPF::$defaults['woocommerce_result_count_class'] ),
                'ordering_class'                       => ( ! empty($br_options['woocommerce_ordering_class']) ? $br_options['woocommerce_ordering_class'] : BeRocket_AAPF::$defaults['woocommerce_ordering_class'] ),
                'pagination_class'                     => ( ! empty($br_options['woocommerce_pagination_class']) ? $br_options['woocommerce_pagination_class'] : BeRocket_AAPF::$defaults['woocommerce_pagination_class'] ),
                'control_sorting'                      => ( empty($br_options['control_sorting']) ? '' : $br_options['control_sorting'] ),
                'seo_friendly_urls'                    => ( empty($br_options['seo_friendly_urls']) ? '' : $br_options['seo_friendly_urls'] ),
                'seo_uri_decode'                       => ( empty($br_options['seo_uri_decode']) ? '' : $br_options['seo_uri_decode'] ),
                'slug_urls'                            => ( empty($br_options['slug_urls']) ? '' : $br_options['slug_urls'] ),
                'nice_urls'                            => ( empty($br_options['nice_urls']) ? '' : $br_options['nice_urls'] ),
                'ub_product_count'                     => ( empty($br_options['ub_product_count']) ? '' : $br_options['ub_product_count'] ),
                'ub_product_text'                      => ( empty($br_options['ub_product_text']) ? '' : $br_options['ub_product_text'] ),
                'ub_product_button_text'               => ( empty($br_options['ub_product_button_text']) ? '' : $br_options['ub_product_button_text'] ),
                'berocket_aapf_widget_product_filters' => $post_temrs,
                'user_func'                            => apply_filters( 'berocket_aapf_user_func', $user_func ),
                'default_sorting'                      => get_option('woocommerce_default_catalog_orderby'),
                'first_page'                           => ( empty($br_options['first_page_jump']) ? '' : $br_options['first_page_jump'] ),
                'scroll_shop_top'                      => ( empty($br_options['scroll_shop_top']) ? '' : $br_options['scroll_shop_top'] ),
                'ajax_request_load'                    => ( empty($br_options['ajax_request_load']) ? '' : $br_options['ajax_request_load'] ),
                'ajax_request_load_style'              => ( ! empty($br_options['ajax_request_load_style']) ? $br_options['ajax_request_load_style'] : BeRocket_AAPF::$defaults['ajax_request_load_style'] ),
                'use_request_method'                   => ( ! empty($br_options['ajax_request_load']) && ! empty($br_options['use_get_query']) ? 'get' : 'post' ),
                'no_products'                          => ("<p class='no-products woocommerce-info" . ( ! empty( $br_options['no_products_class'] ) ? ' '.$br_options['no_products_class'] : '' ) . "'>" . $br_options['no_products_message'] . "</p>"),
                'recount_products'                     => ( empty($br_options['recount_products']) ? '' : $br_options['recount_products'] ),
                'pos_relative'                         => ( empty($br_options['pos_relative']) ? '' : $br_options['pos_relative'] ),
                'woocommerce_removes'                  => json_encode( array( 
                                                              'result_count' => ( empty($br_options['woocommerce_removes']['result_count']) ? '' : $br_options['woocommerce_removes']['result_count'] ),
                                                              'ordering'     => ( empty($br_options['woocommerce_removes']['ordering']) ? '' : $br_options['woocommerce_removes']['ordering'] ),
                                                              'pagination'   => ( empty($br_options['woocommerce_removes']['pagination']) ? '' : $br_options['woocommerce_removes']['pagination'] ),
                                                          ) ),
                'description_show'                     => ( ! empty($br_options['description']['show']) ? $br_options['description']['show'] : 'click' ),
                'description_hide'                     => ( ! empty($br_options['description']['hide']) ? $br_options['description']['hide'] : 'click' ),
                'hide_sel_value'                       => ( empty($br_options['hide_value']['sel']) ? '' : $br_options['hide_value']['sel'] ),
                'hide_o_value'                         => ( empty($br_options['hide_value']['o']) ? '' : $br_options['hide_value']['o'] ),
                'use_select2'                          => ! empty($br_options['use_select2']),
                'hide_empty_value'                     => ( empty($br_options['hide_value']['empty']) ? '' : $br_options['hide_value']['empty'] ),
                'hide_button_value'                    => ( empty($br_options['hide_value']['button']) ? '' : $br_options['hide_value']['button'] ),
                'scroll_shop_top_px'                   => ( ! empty( $br_options['scroll_shop_top_px'] ) ? $br_options['scroll_shop_top_px'] : BeRocket_AAPF::$defaults['scroll_shop_top_px'] ),
                'load_image'                           => '<div class="berocket_aapf_widget_loading"><div class="berocket_aapf_widget_loading_container">
                                                          <div class="berocket_aapf_widget_loading_top">' . ( ! empty( $br_options['ajax_load_text']['top'] ) ? $br_options['ajax_load_text']['top'] : '' ) . '</div>
                                                          <div class="berocket_aapf_widget_loading_left">' . ( ! empty( $br_options['ajax_load_text']['left'] ) ? $br_options['ajax_load_text']['left'] : '' ) . '</div>' .
                                                          ( ! empty( $br_options['ajax_load_icon'] ) ? '<img alt="" src="'.$br_options['ajax_load_icon'].'">' : '<div class="berocket_aapf_widget_loading_image"></div>' ) .
                                                          '<div class="berocket_aapf_widget_loading_right">' . ( ! empty( $br_options['ajax_load_text']['right'] ) ? $br_options['ajax_load_text']['right'] : '' ) . '</div>
                                                          <div class="berocket_aapf_widget_loading_bottom">' . ( ! empty( $br_options['ajax_load_text']['bottom'] ) ? $br_options['ajax_load_text']['bottom'] : '' ) . '</div>
                                                          </div></div>',
                'translate'                            => array(
                                                            'show_value'        => __('Show value(s)', 'BeRocket_AJAX_domain'),
                                                            'hide_value'        => __('Hide value(s)', 'BeRocket_AJAX_domain'),
                                                            'unselect_all'      => __('Unselect all', 'BeRocket_AJAX_domain'),
                                                            'nothing_selected'  => __('Nothing is selected', 'BeRocket_AJAX_domain'),
                                                            'products'          => __('products', 'BeRocket_AJAX_domain'),
                ),
                'trailing_slash'                       => $permalink_structure,
            ) )
        );
    }

    public static function the7_fix($scripts) {
        $scripts['after_update'] = 'fixWooIsotope();fixWooOrdering(); '.$scripts['after_update'];
        return $scripts;
    }

    public static function add_queryvars( $query_vars ) {
        $option_permalink = get_option( 'berocket_permalink_option' );
        $query_vars[] = $option_permalink['variable'];
        return $query_vars;
    }

    public static function updater_info ( $plugins ) {
        $option = self::get_aapf_option();
        self::$info['key'] = ( empty($option['plugin_key']) ? '' : $option['plugin_key'] );
        self::$info['slug'] = basename( __DIR__ );
        self::$info['plugin'] = plugin_basename( __FILE__ );
        self::$info = apply_filters( 'berocket_aapf_update_info', self::$info );
        $info = get_plugin_data( __FILE__ );
        self::$info['name'] = $info['Name'];
        $plugins[] = self::$info;
        return $plugins;
    }

    public static function add_error_log( $error_log ) {
        $error_log[plugin_basename( __FILE__ )] =  self::$error_log;
        return $error_log;
    }

    public static function update_from_older( $version ) {
        $option = self::get_aapf_option();
        if ( version_compare($version, '2.0.4', '<') ) {
            $version_index = 1;
        } elseif ( version_compare($version, '2.0.5', '<') ) {
            $version_index = 2;
        } else {
            $version_index = 3;
        }

        switch ( $version_index ) {
            case 1:
                update_option('berocket_filter_open_wizard_on_settings', true);
            case 2:
                update_option( 'berocket_permalink_option', BeRocket_AAPF::$default_permalink );
                break;
        }

        update_option( 'br_filters_options', $option );
        update_option( 'br_filters_version', BeRocket_AJAX_filters_version );
    }
    
    public static function load_jquery_ui() {
    }

    public static function no_woocommerce() {
        echo '
        <div class="error">
            <p>' . __( 'Activate WooCommerce plugin before', 'BeRocket_AJAX_domain' ) . '</p>
        </div>';
    }

    public static function update_woocommerce() {
        echo '
        <div class="error">
            <p>' . __( 'Update WooCommerce plugin', 'BeRocket_AJAX_domain' ) . '</p>
        </div>';
    }

    public static function br_add_options_page() {
        add_submenu_page( 'berocket_account', __( 'Product Filters Settings', 'BeRocket_AJAX_domain' ), __( 'Product Filters', 'BeRocket_AJAX_domain' ), 'manage_options', 'br-product-filters', array(
            __CLASS__,
            'br_render_form'
        ) );
    }

    public static function shortcode( $atts = array() ) {
        if( BeRocket_AAPF::$debug_mode ) {
            if( ! isset( BeRocket_AAPF::$error_log['2_shortcodes'] ) )
            {
                BeRocket_AAPF::$error_log['2_shortcodes'] = array();
            } 
            BeRocket_AAPF::$error_log['2_shortcodes'][] = $atts;
        }
        $default = BeRocket_AAPF_Widget::$defaults;
        $a = shortcode_atts( $default, $atts );
        if ( ! empty($atts['product_cat']) ) {
            $a['product_cat'] = json_encode( explode( "|", $a['product_cat'] ) );
        }
        if ( ! empty($atts['show_page']) ) {
            $a['show_page'] = explode( "|", $a['show_page'] );
        }
        if ( ! empty($atts['include_exclude_list']) ) {
            $a['include_exclude_list'] = explode( "|", $a['include_exclude_list'] );
        }
        if ( ! empty($atts['ranges']) ) {
            $a['ranges'] = explode( "|", $a['ranges'] );
        }
        if( ! empty($atts['search_box_style']) ) {
            $a['search_box_style'] = array_merge($default['search_box_style'], (array)json_decode($atts['search_box_style']));
        }
        $a['search_box_attributes'] = $default['search_box_attributes'];
        if( ! empty($atts['search_box_attributes']) ) {
            $atts['search_box_attributes'] = (array)json_decode( $atts['search_box_attributes'] );
            if( is_array( $atts['search_box_attributes'] ) ) {
                foreach($atts['search_box_attributes'] as $attr_num => $attr_data) {
                    $a['search_box_attributes'][$attr_num] = array_merge($default['search_box_attributes'][$attr_num], (array)$attr_data);
                }
            }
        }
        $a['child_onew_childs'] = $default['child_onew_childs'];
        if( ! empty($atts['child_onew_childs']) ) {
            $atts['child_onew_childs'] = (array)json_decode( $atts['child_onew_childs'] );
            if( is_array( $atts['child_onew_childs'] ) ) {
                foreach($atts['child_onew_childs'] as $child_num => $child_data) {
                    $a['child_onew_childs'][$child_num] = array_merge($default['child_onew_childs'][$child_num], (array)$child_data);
                }
            }
        }

        $a = apply_filters( 'berocket_aapf_shortcode_options', $a );

        ob_start();
        the_widget( 'BeRocket_AAPF_widget', $a);
        return ob_get_clean();
    }

    public static function br_render_form() {
        wp_enqueue_script( 'berocket_aapf_widget-colorpicker' );
        wp_enqueue_script( 'berocket_aapf_widget-admin' );
        do_action('BeRocket_wizard_javascript');

        $plugin_info = get_plugin_data(__FILE__, false, true);
        $redirect_to_wizard = get_option('berocket_filter_open_wizard_on_settings');
        if( ! empty($redirect_to_wizard) ) {
            delete_option('berocket_filter_open_wizard_on_settings');
            wp_redirect(admin_url( 'admin.php?page=br-aapf-setup' ));
        }
        include AAPF_TEMPLATE_PATH . "admin-settings.php";
    }

    public static function woocommerce_shortcode_products_query( $query_vars, $atts = array(), $name = 'products' ) {
        
        if( ! is_shop() && ! is_product_taxonomy() && ! is_product_category() && ! is_product_tag() ) {
            $new_query_vars = $query_vars;
            $new_query_vars['nopaging'] = true;
            $new_query_vars['fields'] = 'ids';
            if( isset($new_query_vars['tax_query']) ) {
                $tax_query_reset = $new_query_vars['tax_query'];
                unset($new_query_vars['tax_query']);
            }
            $query = new WP_Query( $new_query_vars );
            if( isset($tax_query_reset) ) {
                $query->set('tax_query', $tax_query_reset);
                $new_query_vars['tax_query'] = $tax_query_reset;
                unset($tax_query_reset);
            }
            global $br_shortcode_query;
            $br_shortcode_query = $query;
            $query = self::apply_user_price( $query, true );
            $query = self::apply_user_filters( $query, true );
            $new_query_vars = $query->query_vars;
            $unset_arr = apply_filters('berocket_aapf_woocommerce_shortcode_unset_fields', array('nopaging', 'fields', 's'));
            foreach($unset_arr as $unset_el) {
                if( isset($query_vars[$unset_el]) ) {
                    $new_query_vars[$unset_el] = $query_vars[$unset_el];
                } elseif( isset($new_query_vars[$unset_el]) ) {
                    unset($new_query_vars[$unset_el]);
                }
            }
            if( BeRocket_AAPF::$debug_mode ) {
                if( ! isset(BeRocket_AAPF::$error_log['12_wc_shortcodes']) ||! is_array(BeRocket_AAPF::$error_log['12_wc_shortcodes']) ) {
                    BeRocket_AAPF::$error_log['12_wc_shortcodes'] = array();
                }
                BeRocket_AAPF::$error_log['12_wc_shortcodes'][] = array('name' => $name, 'atts' => $atts, 'query_vars' => $query_vars, 'new_query_vars' => $new_query_vars);
            }
            $query_vars = $new_query_vars;
            $query_vars['post__in'] = apply_filters( 'loop_shop_post_in', $query_vars['post__in']);
            global $br_wc_query;
            if( isset($query_vars['tax_query']) ) {
                $tax_query_reset = $query_vars['tax_query'];
                unset($query_vars['tax_query']);
            }
            $br_wc_query = new WP_Query( $query_vars );
            if( isset($tax_query_reset) ) {
                $br_wc_query->set('tax_query', $tax_query_reset);
                $query_vars['tax_query'] = $tax_query_reset;
                $br_wc_query->parse_tax_query($query_vars);
            }
        }
        return $query_vars;
    }

    public static function display_products() {
        return '';
    }

    public static function apply_user_price( $query, $is_shortcode = FALSE ) {
        $option_permalink = get_option( 'berocket_permalink_option' );
        $options = BeRocket_AAPF::get_aapf_option();if( class_exists('WC_Query') && method_exists('WC_Query', 'get_main_query') ) {
            $wc_query = WC_Query::get_main_query();
            $is_wc_main_query = $wc_query === $query;
        } else {
            $is_wc_main_query = $query->is_main_query();
        }
        if ( ( ( ! is_admin() && $is_wc_main_query ) || $is_shortcode ) && ( ! empty($_GET['filters']) || $query->get( $option_permalink['variable'], '' ) ) ) {
            br_aapf_args_converter( $query );
            if ( ! empty($_POST['price']) ) {
                add_filter( 'loop_shop_post_in', array( __CLASS__, 'price_filter' ) );
            }

            if ( ! empty($_POST['limits']) ) {
                add_filter( 'loop_shop_post_in', array( __CLASS__, 'limits_filter' ) );
            }

            if ( ! empty($_POST['add_terms']) ) {
                add_filter( 'loop_shop_post_in', array( __CLASS__, 'add_terms' ), 900 );
            }
            if( ! empty($options['products_only']) ) {
                add_filter('pre_option_woocommerce_shop_page_display', array( __CLASS__, 'display_products' ), 99999);
                add_filter('pre_option_woocommerce_category_archive_display', array( __CLASS__, 'display_products' ), 99999);
            }
        }
        return $query;
    }
    public static function apply_user_filters( $query, $is_shortcode = FALSE ) {
        $options = BeRocket_AAPF::get_aapf_option();
        if( BeRocket_AAPF::$debug_mode ) {
            if ( empty( BeRocket_AAPF::$error_log['8_1_query_in'] ) || ! is_array( BeRocket_AAPF::$error_log['8_1_query_in'] ) ) {
                BeRocket_AAPF::$error_log['8_1_query_in'] = array();
            }
            BeRocket_AAPF::$error_log['8_1_query_in'][] = $query;
            BeRocket_AAPF::$error_log['PERMALINK'] = get_option('permalink_structure');
        }
        $option_permalink = get_option( 'berocket_permalink_option' );
        if( class_exists('WC_Query') && method_exists('WC_Query', 'get_main_query') ) {
            $wc_query = WC_Query::get_main_query();
            $is_wc_main_query = $wc_query === $query || $query->is_main_query();
            if( $is_wc_main_query && ! $query->is_main_query() ) {
                $is_shortcode = true;
            }
        } else {
            $is_wc_main_query = $query->is_main_query();
        }
        if( ! empty($_GET['filters']) || $query->get( $option_permalink['variable'], '' ) ) {
            br_aapf_args_converter( $query );
        }
        if ( ( ( ! is_admin() && $is_wc_main_query ) || $is_shortcode ) && ( ! empty($_GET['filters']) || $query->get( $option_permalink['variable'], '' ) ) 
        && ( ( isset($query->query_vars['wc_query']) && $query->query_vars['wc_query'] == 'product_query' ) || ( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'product' ) ) ) {

            if( BeRocket_AAPF::$debug_mode ) {
                BeRocket_AAPF::$error_log['8_query_in'] = $query;
            }
            if( ! empty($options['products_only']) ) {
                add_filter('pre_option_woocommerce_shop_page_display', array( __CLASS__, 'display_products' ), 99999);
                add_filter('pre_option_woocommerce_category_archive_display', array( __CLASS__, 'display_products' ), 99999);
            }

            $old_post_terms                      = ( empty($_POST['terms']) ? '' : $_POST['terms'] );
            $woocommerce_hide_out_of_stock_items = BeRocket_AAPF_Widget::woocommerce_hide_out_of_stock_items();
            $meta_query                          = BeRocket_AAPF::remove_out_of_stock( array(), true, $woocommerce_hide_out_of_stock_items != 'yes' );

            $args = br_aapf_args_parser();
            if ( isset( $args['meta_query'] ) ) {
                $args['meta_query'] += $meta_query;
            } else {
                $args['meta_query'] = $meta_query;
            }
            $_POST['terms'] = $old_post_terms;

            if ( ! empty($_POST['add_terms']) ) {
                add_filter( 'loop_shop_post_in', array( __CLASS__, 'add_terms' ), 900 );
            }
            if ( ! empty($_POST['price']) ) {
                if( empty($options['slider_compatibility']) ) {
                    $min = isset( $_POST['price'][0] ) ? floatval( $_POST['price'][0] ) : 0;
                    $max = isset( $_POST['price'][1] ) ? floatval( $_POST['price'][1] ) : 9999999999;
                    if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
                        $tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
                        $class_min   = $min;

                        foreach ( $tax_classes as $tax_class ) {
                            if ( $tax_rates = WC_Tax::get_rates( $tax_class ) ) {
                                $class_min = $min - WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min, $tax_rates ) );
                            }
                        }
                        $min = $class_min;
                    }

                    $args['meta_query'][] = array(
                        'key'          => '_price',
                        'value'        => array( $min, $max ),
                        'compare'      => 'BETWEEN',
                        'type'         => 'DECIMAL',
                        'price_filter' => true,
                    );
                } else {
                    if( $is_shortcode ) {
                        $args['meta_query'][] = array( 'key' => '_price', 'compare' => 'BETWEEN', 'type' => 'NUMERIC', 'value' => array( ($_POST['price'][0]), $_POST['price'][1] ) );
                    } else {
                        add_filter( 'loop_shop_post_in', array( __CLASS__, 'price_filter' ) );
                    }
                }
            }
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
            if( empty($options['slider_compatibility']) && ! empty($_POST['limits']) ) {
                $args = apply_filters('berocket_aapf_convert_limits_to_tax_query', $args, $_POST['limits']);
            }

            $args = apply_filters( 'berocket_aapf_filters_on_page_load', $args );
            if( BeRocket_AAPF::$debug_mode ) {
                BeRocket_AAPF::$error_log['3_user_filters'] = $args;
            }

            global $berocket_filters_session;
            if( ! empty($args['tax_query']) ) {
                $_SESSION['BeRocket_filters'] = array('terms' => $_POST['terms']);
                $berocket_filters_session = $_SESSION['BeRocket_filters'];
            } else {
                if( isset($_SESSION['BeRocket_filters']) ) {
                    unset($_SESSION['BeRocket_filters']);
                }
                if( isset($berocket_filters_session) ) {
                    unset($berocket_filters_session);
                }
            }
            $args_fields = array( 'meta_key', 'tax_query', 'fields', 'where', 'join', 'meta_query', 'date_query' );
            foreach ( $args_fields as $args_field ) {
                if ( ! empty($args[ $args_field ]) ) {
                    $variable = $query->get( $args_field );
                    if( is_array($variable) ) {
                        $variable = array_merge($variable, $args[ $args_field ]);
                    } else {
                        $variable = $args[ $args_field ];
                    }
                    $query->set( $args_field, $variable );
                }
            }

            //THIS CAN BE NEW FIX FOR SORTING, BUT NOT SURE
            if( class_exists('WC_Query') &&  method_exists('WC_Query', 'product_query') ) {
                
                if( empty($_GET['orderby']) && wc_clean( get_query_var( 'orderby' ) ) && strtolower(wc_clean( get_query_var( 'order' ) )) == 'desc' ) {
                    $orderby = strtolower(wc_clean( get_query_var( 'orderby' ) ));
                    $orderby = explode(' ', $orderby);
                    $orderby = $orderby[0];
                    if( in_array($orderby, array('date')) ) {
                        $_GET['orderby'] = strtolower($orderby);
                    } else {
                        $_GET['orderby'] = strtolower($orderby.'-'.wc_clean( get_query_var( 'order' ) ));
                    }
                }
                wc()->query->product_query($query);
            }
            if( BeRocket_AAPF::$debug_mode ) {
                BeRocket_AAPF::$error_log['8_query_out'] = $query;
            }
            $query = apply_filters('berocket_filters_query_already_filtered', $query, berocket_isset($_POST['terms']), berocket_isset($_POST['limits_arr']));
        }

        if ( ( ! is_admin() && $query->is_main_query() ) || $is_shortcode ) {
            global $br_wc_query;
            $br_wc_query = $query;
        }
        if ( $is_shortcode ) {
            add_action( 'wp_footer', array( __CLASS__, 'wp_footer_widget'), 99999 );
        }

        if( BeRocket_AAPF::$debug_mode ) {
            BeRocket_AAPF::$error_log['8_2_query_out'] = $query;
        }

        return apply_filters('berocket_aapf_return_query_filtered', $query, $is_shortcode);
    }

    public static function convert_limits_to_tax_query($args, $limits) {
        $options = self::get_aapf_option();
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

    public static function remove_out_of_stock( $filtered_posts, $use_post_terms = false, $show_out_of_stock = false ) {
        global $wpdb;
        if ( $use_post_terms ) {
            $meta_query = array();
            if( ! empty($_POST['terms']) ) {
                foreach($_POST['terms'] as $term) {
                    if( $term[0] == '_stock_status' ) {
                        array_push($meta_query , array( 'key' => $term[0], 'value' => $term[3], 'compare' => '=' ) );
                    }
                }
                for ( $i = count( $_POST['terms'] ) - 1; $i >= 0; $i-- ) {
                    if ( $_POST['terms'][$i][0] ==  '_stock_status' ) {
                        unset( $_POST['terms'][$i] );
                    }
                }
            }

            if ( $show_out_of_stock ) {
                return $meta_query;
            } else {
                return array();
            }
        }

        $query_string = "
            SELECT DISTINCT ID, post_parent, post_type FROM $wpdb->posts
            INNER JOIN $wpdb->postmeta as meta ON ID = meta.post_id
            WHERE post_type IN ( 'product', 'product_variation' ) AND post_status = 'publish'
            AND meta_key = '_stock_status' AND meta_value != 'outofstock'";

        if( BeRocket_AAPF::$debug_mode ) {
            BeRocket_AAPF::$error_log['104_remove_out_of_stock_SELECT'] = $query_string;
            $wpdb->show_errors();
        }

        // TODO: split this into 2 queries(product and product_variation) this way we will not be using all data at the same time
        $matched_products_query = $wpdb->get_results( $query_string, OBJECT_K );
        unset( $query_string );
        $matched_products = array( 0 );

        if( BeRocket_AAPF::$debug_mode ) {
            BeRocket_AAPF::$error_log['000_select_status'][] = @ $wpdb->last_error;
        }

        foreach ( $matched_products_query as $product ) {
            if ( $product->post_type == 'product' )
                $matched_products[] = $product->ID;
            // TODO: check if we really need this in_array. We have array_unique after foreach. Only one should be left
            if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) )
                $matched_products[] = $product->post_parent;
        }
        if( ! empty($matched_products) && is_array($matched_products) ) {
            $matched_products = array_unique( $matched_products );
        }

        if ( sizeof( $filtered_posts ) == 0) {
            $filtered_posts = $matched_products;
        } else {
            // TODO: array_intersect will create count($filtered_posts) * count($matched_products) loops.
            // TODO: this should be handled above, in foreach
            $filtered_posts = array_intersect( $filtered_posts, $matched_products );
        }

        return (array) $filtered_posts;
    }

    public static function remove_hidden( $filtered_posts ){
        global $wpdb;

        $query_string = "
            SELECT DISTINCT ID, post_parent, post_type FROM $wpdb->posts
            INNER JOIN $wpdb->postmeta as meta ON ID = meta.post_id
            WHERE post_type IN ( 'product', 'product_variation' ) AND post_status = 'publish'
            AND meta_key = '_visibility' AND meta_value NOT IN ('hidden', 'search')";

        if( BeRocket_AAPF::$debug_mode ) {
            BeRocket_AAPF::$error_log['105_remove_hidden_SELECT'] = $query_string;
            $wpdb->show_errors();
        }

        $matched_products_query = $wpdb->get_results( $query_string, OBJECT_K );
        unset( $query_string );
        $matched_products = array( 0 );

        if( BeRocket_AAPF::$debug_mode ) {
            BeRocket_AAPF::$error_log['000_select_status'][] = @ $wpdb->last_error;
        }

        foreach ( $matched_products_query as $product ) {
            if ( $product->post_type == 'product' )
                $matched_products[] = $product->ID;
            if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) )
                $matched_products[] = $product->post_parent;
        }
        if( ! empty($matched_products) && is_array($matched_products) ) {
            $matched_products = array_unique( $matched_products );
        }

        if ( sizeof( $filtered_posts ) == 0) {
            $filtered_posts = $matched_products;
        } else {
            $filtered_posts = array_intersect( $filtered_posts, $matched_products );
        }
        return (array) $filtered_posts;
    }

    public static function limits_filter( $filtered_posts ) {
        $option = self::get_aapf_option();
        if( empty($option['slider_compatibility']) ) {
            return $filtered_posts;
        }
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

                if( BeRocket_AAPF::$debug_mode ) {
                    BeRocket_AAPF::$error_log['106_'.$v[0].'limits_filter_SELECT'] = $wpdb->prepare( $query_string, $v[0], $v[1], $v[2] );
                    $wpdb->show_errors();
                }

                $matched_products_query = $wpdb->get_results( $wpdb->prepare( $query_string, $v[0], $v[1], $v[2] ), OBJECT_K );
                unset( $query_string );

                if( BeRocket_AAPF::$debug_mode ) {
                    BeRocket_AAPF::$error_log['000_select_status'][] = @ $wpdb->last_error;
                }

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

    public static function add_terms( $filtered_posts ) {
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
                            $products = self::wc_get_product_ids_not_on_sale();
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

    public static function delete_products_not_on_sale($transient) {
        delete_transient( 'wc_products_notonsale' );
    }

    public static function wc_get_product_ids_not_on_sale() {
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

    public static function price_filter( $filtered_posts ) {
        $option = self::get_aapf_option();
        if( empty($option['slider_compatibility']) ) {
            return $filtered_posts;
        }
        global $wpdb;

        if ( ! empty($_POST['price']) || ! empty($_POST['price_ranges']) ) {
            $matched_products = array( 0 );
            if ( ! empty($_POST['price']) ) {
                $min              = floatval( $_POST['price'][0] );
                $max              = floatval( $_POST['price'][1] );

                if( BeRocket_AAPF::$debug_mode ) {
                    if( ! isset( BeRocket_AAPF::$error_log['5_price'] ) )
                    {
                        BeRocket_AAPF::$error_log['5_price'] = array();
                    } 
                    BeRocket_AAPF::$error_log['5_price']['select'] = 'from '.$min.' to '.$max;
                    $wpdb->show_errors();
                }

                $matched_products_query = apply_filters( 'woocommerce_price_filter_results', $wpdb->get_results( $wpdb->prepare( "
                    SELECT DISTINCT ID, post_parent FROM $wpdb->posts
                    INNER JOIN $wpdb->postmeta ON ID = post_id
                    WHERE post_type = 'product' AND post_status = 'publish' AND meta_key = %s AND meta_value BETWEEN %d AND %d
                ", '_price', $min, $max ), OBJECT_K ), $min, $max );
            } else {
                $values = $_POST['price_ranges'];
                $between = '';
                foreach ( $values as $value ) {
                    if ( $between ) {
                        $between .= ' OR ';
                    }
                    $between .= '( ';
                    $value = explode( '*', $value );
                    $value = $value;
                    $between .= 'meta_value BETWEEN '.($value[0] - 1).' AND '.$value[1];
                    $between .= ' )';
                }

                $matched_products_query = apply_filters( 'woocommerce_price_ranges_filter_results', $wpdb->get_results( $wpdb->prepare( "
                    SELECT DISTINCT ID, post_parent FROM $wpdb->posts
                    INNER JOIN $wpdb->postmeta ON ID = post_id
                    WHERE post_type = 'product' AND post_status = 'publish' AND meta_key = %s AND ( $between )
                ", '_price' ), OBJECT_K ), $values );

                if( BeRocket_AAPF::$debug_mode ) {
                    BeRocket_AAPF::$error_log['000_select_status'][] = @ $wpdb->last_error;
                    BeRocket_AAPF::$error_log['0099_price'][] = $wpdb->prepare( "
                        SELECT DISTINCT ID, post_parent FROM $wpdb->posts
                        INNER JOIN $wpdb->postmeta ON ID = post_id
                        WHERE post_type = 'product' AND post_status = 'publish' AND meta_key = %s AND ( $between )
                    ", '_price' );
                }
            }

            if ( $matched_products_query ) {
                foreach ( $matched_products_query as $product ) {
                    $matched_products[] = $product->ID;
                    // TODO: check if this is needed here. probably this is for product_variation
                    if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) ) {
                        $matched_products[] = $product->post_parent;
                    }
                }
                unset( $matched_products_query );
            }

            $matched_product_variations_query = apply_filters( 'woocommerce_price_filter_results', $wpdb->get_results( $wpdb->prepare( "
                SELECT DISTINCT ID, post_parent FROM $wpdb->posts
                INNER JOIN $wpdb->postmeta ON ID = post_id
                WHERE post_type = 'product_variation' AND post_status = 'publish' AND meta_key = %s AND meta_value BETWEEN %d AND %d
            ", '_price', $min, $max ), OBJECT_K ), $min, $max );

            if ( $matched_product_variations_query ) {
                foreach ( $matched_product_variations_query as $product ) {
                    if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) ) {
                        $matched_products[] = $product->post_parent;
                    }
                }
                unset( $matched_product_variations_query );
            }

            // Filter the id's
            if ( sizeof( $filtered_posts ) == 0 ) {
                $filtered_posts = $matched_products;
            } else {
                // TODO: remove array_intersect from here and check for intersect in foreach
                $filtered_posts = array_intersect( $filtered_posts, $matched_products );
            }

        }

        return (array) $filtered_posts;
    }

    /**
     * Get template part (for templates like the slider).
     *
     * @access public
     *
     * @param string $name (default: '')
     *
     * @return void
     */
    public static function br_get_template_part( $name = '' ) {
        $template = '';

        // Look in your_child_theme/woocommerce-filters/name.php
        if ( $name ) {
            $template = locate_template( "woocommerce-filters/{$name}.php" );
        }

        // Get default slug-name.php
        if ( ! $template && $name && file_exists( AAPF_TEMPLATE_PATH . "{$name}.php" ) ) {
            $template = AAPF_TEMPLATE_PATH . "{$name}.php";
        }

        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters( 'br_get_template_part', $template, $name );


        if ( $template ) {
            load_template( $template, false );
        }
    }

    public static function admin_enqueue_scripts() {
        if ( function_exists( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );
        }
    }

    public static function admin_init() {
        if ( is_admin() ) {
            /* BeRocket WordPress Admin menu styles */
            wp_register_style(
                'berocket_framework_global_admin_style',
                plugins_url( 'css/global-admin.css', __FILE__ ),
                "",
                self::$info[ 'version' ]
            );
            wp_enqueue_style( 'berocket_framework_global_admin_style' );
        }
        wp_register_style( 'berocket_aapf_widget-colorpicker-style', plugins_url( 'css/colpick.css', __FILE__ ) );
        wp_register_style( 'berocket_aapf_widget-admin-style', plugins_url( 'css/admin.css', __FILE__ ), "", BeRocket_AJAX_filters_version );
        wp_register_style( 'brjsf-ui', plugins_url( 'css/brjsf.css', __FILE__ ), "", BeRocket_AJAX_filters_version );
        wp_enqueue_style( 'berocket_aapf_widget-colorpicker-style' );
        wp_enqueue_style( 'berocket_aapf_widget-admin-style' );
        wp_register_script( 'berocket_aapf_widget-colorpicker', plugins_url( 'js/colpick.js', __FILE__ ), array( 'jquery' ) );
        wp_register_script( 'brjsf-ui', plugins_url( 'js/brjsf.js', __FILE__ ), array( 'jquery' ), BeRocket_AJAX_filters_version );
        wp_register_script( 'berocket_aapf_widget-admin', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), BeRocket_AJAX_filters_version, false );
        register_setting( 'br_filters_plugin_options', 'br_filters_options', array( __CLASS__, 'sanitize_aapf_option' ) );
    }

    public static function register_permalink_option() {
        $screen = get_current_screen();
        $default_values = self::$default_permalink;
        if($screen->id == 'options-permalink') {
            self::save_permalink_option($default_values);
            self::_register_permalink_option($default_values);
        }
        if(strpos($screen->id, 'widgets') !== FALSE || strpos($screen->id, 'br-product-filters') !== FALSE) {
            /*wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-widget ' );
            wp_enqueue_script( 'jquery-ui-selectmenu' );
            wp_enqueue_script( 'jquery-ui-accordion' );
            wp_enqueue_script( 'jquery-ui-button' );

            wp_register_style( 'jquery-ui', plugins_url( 'css/jquery-ui.min.css', __FILE__ ), "", BeRocket_AJAX_filters_version );
            wp_enqueue_style( 'jquery-ui' );*/
            
            self::register_admin_scripts();
        }
        if( BeRocket_AAPF::$debug_mode ) {
            BeRocket_AAPF::$error_log['21_current_screen'] = $screen;
        }
    }

    public static function register_admin_scripts(){
        wp_enqueue_script( 'brjsf-ui');
        wp_enqueue_style( 'brjsf-ui' );
        wp_enqueue_style( 'font-awesome' );
    }

    public static function _register_permalink_option($default_values) {
        $permalink_option = 'berocket_permalink_option';
        $option_values = get_option( $permalink_option );
        $data = shortcode_atts( $default_values, $option_values );
        update_option($permalink_option, $data);
        
        add_settings_section(
            'berocket_permalinks',
            'BeRocket AJAX Filters',
            'br_permalink_input_section_echo',
            'permalink'
        );
    }

    public static function save_permalink_option( $default_values ) {
        if ( isset( $_POST['berocket_permalink_option'] ) ) {
            $option_values    = $_POST['berocket_permalink_option'];
            $data             = shortcode_atts( $default_values, $option_values );
            $data['variable'] = $data['variable'];

            update_option( 'berocket_permalink_option', $data );
        }
    }
    public static function new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
        global $wpdb;
        if ( is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
            $old_blog = $wpdb->blogid;
            switch_to_blog($blog_id);
            self::_br_add_defaults();
            switch_to_blog($old_blog);
        }
    }

    public static function br_add_defaults( $networkwide ) {
        global $wpdb;
        if ( function_exists('is_multisite') && is_multisite() ) {
            if ( $networkwide) {
                $old_blog = $wpdb->blogid;
                $blogids  = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

                foreach ( $blogids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    self::_br_add_defaults();
                }

                switch_to_blog( $old_blog );
                return;
            }
        } 
        self::_br_add_defaults();
    }

    public static function _br_add_defaults() {
        $tmp = self::get_aapf_option();
        $tmp2 = get_option( 'berocket_permalink_option' );
        $version = get_option( 'br_filters_version' );
        if ( isset($tmp['chk_default_options_db']) and ($tmp['chk_default_options_db'] == '1' or ! is_array( $tmp )) ) {
            delete_option( 'br_filters_options' );
            update_option( 'br_filters_options', BeRocket_AAPF::$defaults );
        }
        if ( ( isset($tmp['chk_default_options_db']) and $tmp['chk_default_options_db'] == '1' ) or !is_array( $tmp2 ) ) {
            delete_option( 'berocket_permalink_option' );
            update_option( 'berocket_permalink_option', BeRocket_AAPF::$default_permalink );
        }
    }

    public static function br_delete_plugin_options($networkwide) {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) {
            if ($networkwide) {
                $old_blog = $wpdb->blogid;
                $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    self::_br_delete_plugin_options();
                }
                switch_to_blog($old_blog);
                return;
            }
        }
        self::_br_delete_plugin_options();
    }

    public static function _br_delete_plugin_options() {
        delete_option( 'br_filters_options' );
        delete_option( 'berocket_permalink_option' );
    }

    public static function convert_styles_to_string(&$style) {
        if( empty($style) || ! is_array($style) ) {
            return '';
        }
        $style_line = '';
        if ( ! empty($style['bcolor']) ) {
            $style_line .= 'border-color: ';
            if ( $style['bcolor'][0] != '#' ) {
                $style_line .= '#';
            }
            $style_line .= $style['bcolor'].'!important;';
        }
        if ( isset($style['bwidth']) )
            $style_line .= 'border-width: '.$style['bwidth'].'px!important;';
        if ( isset($style['bradius']) )
            $style_line .= 'border-radius: '.$style['bradius'].'px!important;';
        if ( isset($style['fontsize']) )
            $style_line .= 'font-size: '.$style['fontsize'].'px!important;';
        if ( ! empty($style['fcolor']) ) {
            $style_line .= 'color: ';
            if ( $style['fcolor'][0] != '#' ) {
                $style_line .= '#';
            }
            $style_line .= $style['fcolor'].'!important;';
        }
        if ( ! empty($style['backcolor']) ) {
            $style_line .= 'background-color: ';
            if ( $style['backcolor'][0] != '#' ) {
                $style_line .= '#';
            }
            $style_line .= $style['backcolor'].'!important;';
        }
        return $style_line;
    }

    public static function br_custom_user_css() {
        $options     = self::get_aapf_option();
        $replace_css = array(
            '#widget#'       => '.berocket_aapf_widget',
            '#widget-title#' => '.berocket_aapf_widget-title'
        );
        $result_css  = ( empty($options['user_custom_css']) ? '' : $options['user_custom_css'] );
        foreach ( $replace_css as $key => $value ) {
            $result_css = str_replace( $key, $value, $result_css );
        }
        $uo = br_aapf_converter_styles( (isset($options['styles']) ? $options['styles'] : array()) );
        echo '<style type="text/css">' . $result_css;
        if( ! empty($uo['style']['selected_area']) ) {
            echo ' div.berocket_aapf_widget_selected_area .berocket_aapf_widget_selected_filter a, div.berocket_aapf_selected_area_block a{'.$uo['style']['selected_area'].'}';
        }
        echo ' div.berocket_aapf_widget_selected_area .berocket_aapf_widget_selected_filter a.br_hover *, div.berocket_aapf_widget_selected_area .berocket_aapf_widget_selected_filter a.br_hover, div.berocket_aapf_selected_area_block a.br_hover{'.(isset($uo['style']['selected_area_hover']) ? $uo['style']['selected_area_hover'] : '').'}';
        if ( ! empty($options['styles_input']['checkbox']['icon']) ) {
            echo 'ul.berocket_aapf_widget li > span > input[type="checkbox"] + .berocket_label_widgets:before {display:inline-block;}';
            echo '.berocket_aapf_widget input[type="checkbox"] {display: none;}';
        }
        echo ' ul.berocket_aapf_widget li > span > input[type="checkbox"] + .berocket_label_widgets:before {';
        echo self::convert_styles_to_string($options['styles_input']['checkbox']);
        echo '}';
        echo ' ul.berocket_aapf_widget li > span > input[type="checkbox"]:checked + .berocket_label_widgets:before {';
        if ( ! empty($options['styles_input']['checkbox']['icon']) )
            echo 'content: "\\'.$options['styles_input']['checkbox']['icon'].'";';
        echo '}';
        if ( ! empty($options['styles_input']['radio']['icon']) ) {
            echo 'ul.berocket_aapf_widget li > span > input[type="radio"] + .berocket_label_widgets:before {display:inline-block;}';
            echo '.berocket_aapf_widget input[type="radio"] {display: none;}';
        }
        echo ' ul.berocket_aapf_widget li > span > input[type="radio"] + .berocket_label_widgets:before {';
        echo self::convert_styles_to_string($options['styles_input']['radio']);
        echo '}';
        echo ' ul.berocket_aapf_widget li > span > input[type="radio"]:checked + .berocket_label_widgets:before {';
        if ( ! empty($options['styles_input']['radio']['icon']) )
            echo 'content: "\\'.$options['styles_input']['radio']['icon'].'";';
        echo '}';
        echo '.berocket_aapf_widget .slide .berocket_filter_slider.ui-widget-content .ui-slider-range, .berocket_aapf_widget .slide .berocket_filter_price_slider.ui-widget-content .ui-slider-range{';
        if ( ! empty($options['styles_input']['slider']['line_color']) ) {
            echo 'background-color: ';
            if ( $options['styles_input']['slider']['line_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['slider']['line_color'].';';
        }
        echo '}';
        echo '.berocket_aapf_widget .slide .berocket_filter_slider.ui-widget-content, .berocket_aapf_widget .slide .berocket_filter_price_slider.ui-widget-content{';
        if ( isset($options['styles_input']['slider']['line_height']) )
            echo 'height: '.$options['styles_input']['slider']['line_height'].'px;';
        if ( ! empty($options['styles_input']['slider']['line_border_color']) ) {
            echo 'border-color: ';
            if ( $options['styles_input']['slider']['line_border_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['slider']['line_border_color'].';';
        }
        if ( ! empty($options['styles_input']['slider']['back_line_color']) ) {
            echo 'background-color: ';
            if ( $options['styles_input']['slider']['back_line_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['slider']['back_line_color'].';';
        }
        if ( isset($options['styles_input']['slider']['line_border_width']) )
            echo 'border-width: '.$options['styles_input']['slider']['line_border_width'].'px;';
        echo '}';
        echo '.berocket_aapf_widget .slide .berocket_filter_slider .ui-state-default, 
            .berocket_aapf_widget .slide .berocket_filter_price_slider .ui-state-default,
            .berocket_aapf_widget .slide .berocket_filter_slider.ui-widget-content .ui-state-default,
            .berocket_aapf_widget .slide .berocket_filter_price_slider.ui-widget-content .ui-state-default,
            .berocket_aapf_widget .slide .berocket_filter_slider .ui-widget-header .ui-state-default,
            .berocket_aapf_widget .slide .berocket_filter_price_slider .ui-widget-header .ui-state-default
            .berocket_aapf_widget .berocket_filter_slider.ui-widget-content .ui-slider-handle,
            .berocket_aapf_widget .berocket_filter_price_slider.ui-widget-content .ui-slider-handle{';
        if ( isset($options['styles_input']['slider']['button_size']) )
            echo 'font-size: '.$options['styles_input']['slider']['button_size'].'px;';
        if ( ! empty($options['styles_input']['slider']['button_color']) ) {
            echo 'background-color: ';
            if ( $options['styles_input']['slider']['button_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['slider']['button_color'].';';
        }
        if ( ! empty($options['styles_input']['slider']['button_border_color']) ) {
            echo 'border-color: ';
            if ( $options['styles_input']['slider']['button_border_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['slider']['button_border_color'].';';
        }
        if ( isset($options['styles_input']['slider']['button_border_width']) )
            echo 'border-width: '.$options['styles_input']['slider']['button_border_width'].'px;';
        if ( isset($options['styles_input']['slider']['button_border_radius']) )
            echo 'border-radius: '.$options['styles_input']['slider']['button_border_radius'].'px;';
        echo '}';
        echo ' .berocket_aapf_selected_area_hook div.berocket_aapf_widget_selected_area .berocket_aapf_widget_selected_filter a{'.( ! empty( $uo['style']['selected_area_block'] ) ? 'background-'.$uo['style']['selected_area_block'] : '' ).( ! empty( $uo['style']['selected_area_border'] ) ? ' border-'.$uo['style']['selected_area_border'] : '' ).'}';
        echo '.berocket_aapf_widget div.berocket_aapf_product_count_desc {';
        if ( ! empty($options['styles_input']['pc_ub']['back_color']) ) {
            echo 'background-color: ';
            if ( $options['styles_input']['pc_ub']['back_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['back_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['border_color']) ) {
            echo 'border-color: ';
            if ( $options['styles_input']['pc_ub']['border_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['border_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['font_color']) ) {
            echo 'color: ';
            if ( $options['styles_input']['pc_ub']['font_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['font_color'].';';
        }
        if ( isset($options['styles_input']['pc_ub']['font_size']) ) {
            echo 'font-size: '.$options['styles_input']['pc_ub']['font_size'].'px;';
        }
        echo '}';
        echo '.berocket_aapf_widget div.berocket_aapf_product_count_desc > span {';
        if ( ! empty($options['styles_input']['pc_ub']['back_color']) ) {
            echo 'background-color: ';
            if ( $options['styles_input']['pc_ub']['back_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['back_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['border_color']) ) {
            echo 'border-color: ';
            if ( $options['styles_input']['pc_ub']['border_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['border_color'].';';
        }
        echo '}';
        echo '.berocket_aapf_widget div.berocket_aapf_product_count_desc .berocket_aapf_widget_update_button {';
        if ( ! empty($options['styles_input']['pc_ub']['show_font_color']) ) {
            echo 'color: ';
            if ( $options['styles_input']['pc_ub']['show_font_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['show_font_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['show_font_size']) ) {
            echo 'font-size: '.$options['styles_input']['pc_ub']['show_font_size'].'px;';
        }
        echo '}';
        echo '.berocket_aapf_widget div.berocket_aapf_product_count_desc .berocket_aapf_widget_update_button:hover {';
        if ( ! empty($options['styles_input']['pc_ub']['show_font_color_hover']) ) {
            echo 'color: ';
            if ( $options['styles_input']['pc_ub']['show_font_color_hover'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['show_font_color_hover'].';';
        }
        echo '}';
        echo '.berocket_aapf_widget div.berocket_aapf_product_count_desc .berocket_aapf_close_pc {';
        if ( ! empty($options['styles_input']['pc_ub']['close_font_color']) ) {
            echo 'color: ';
            if ( $options['styles_input']['pc_ub']['close_font_color'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['close_font_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['close_size']) ) {
            echo 'font-size: '.$options['styles_input']['pc_ub']['close_size'].'px;';
        }
        echo '}';
        echo '.berocket_aapf_widget div.berocket_aapf_product_count_desc .berocket_aapf_close_pc:hover {';
        if ( ! empty($options['styles_input']['pc_ub']['close_font_color_hover']) ) {
            echo 'color: ';
            if ( $options['styles_input']['pc_ub']['close_font_color_hover'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['pc_ub']['close_font_color_hover'].';';
        }
        echo '}';
        echo 'div.berocket_single_filter_widget.berocket_hidden_clickable .berocket_aapf_widget-title_div {';
        echo self::convert_styles_to_string($options['styles_input']['onlyTitle_title']);
        echo '}';
        echo 'div.berocket_single_filter_widget.berocket_hidden_clickable.berocket_single_filter_visible .berocket_aapf_widget-title_div {';
        echo self::convert_styles_to_string($options['styles_input']['onlyTitle_titleopened']);
        echo '}';
        echo 'div.berocket_single_filter_widget.berocket_hidden_clickable .berocket_aapf_widget {';
        echo self::convert_styles_to_string($options['styles_input']['onlyTitle_filter']);
        echo '}';
        if ( ! empty($options['styles_input']['onlyTitle_filter']['fcolor']) ) {
            echo 'div.berocket_single_filter_widget.berocket_hidden_clickable .berocket_aapf_widget * {';
            echo 'color: ';
            if ( $options['styles_input']['onlyTitle_filter']['fcolor'][0] != '#' ) {
                echo '#';
            }
            echo $options['styles_input']['onlyTitle_filter']['fcolor'].';';
            echo '}';
            echo 'div.berocket_single_filter_widget.berocket_hidden_clickable .berocket_aapf_widget input {';
            echo 'color: black;';
            echo '}';
        }
        echo '</style>';
    }

    public static function create_metadata_table() {
        wp_register_style( 'select2', plugins_url( 'css/select2.min.css', __FILE__ ) );
        wp_register_style( 'br_select2', plugins_url( 'css/select2.fixed.css', __FILE__ ) );
        wp_register_script( 'select2', plugins_url( 'js/select2.min.js', __FILE__ ), array( 'jquery' ) );
        wp_register_style( 'font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );
        wp_register_style( 'berocket_aapf_widget-style', plugins_url( 'css/widget.css', __FILE__ ), "", BeRocket_AJAX_filters_version );
        wp_register_style( 'berocket_aapf_widget-scroll-style', plugins_url( 'css/scrollbar/Scrollbar.min.css', __FILE__ ), "", BeRocket_AJAX_filters_version );
        wp_register_style( 'berocket_aapf_widget-themer-style', plugins_url( 'css/styler/formstyler.css', __FILE__ ), "", BeRocket_AJAX_filters_version );
        wp_register_style( 'jquery-ui-datepick', plugins_url( 'css/jquery-ui.min.css', __FILE__ ) );

        global $wpdb;
        $is_database = get_option( 'br_filters_color_database' );
        $type        = 'berocket_term';
        $table_name  = $wpdb->prefix . $type . 'meta';
        if ( ! $is_database ) {
            if ( ! empty ( $wpdb->charset ) ) {
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            }
            if ( ! empty ( $wpdb->collate ) ) {
                $charset_collate .= " COLLATE {$wpdb->collate}";
            }

            $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
                meta_id bigint(20) NOT NULL AUTO_INCREMENT,
                {$type}_id bigint(20) NOT NULL default 0,
             
                meta_key varchar(255) DEFAULT NULL,
                meta_value longtext DEFAULT NULL,
                         
                UNIQUE KEY meta_id (meta_id)
            ) {$charset_collate};";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            update_option( 'br_filters_color_database', true );
        }
        $variable_name        = $type . 'meta';
        $wpdb->$variable_name = $table_name;
    }

    public static function wp_print_footer_scripts() {
        $br_options = self::get_aapf_option();
        if( ! $br_options['disable_font_awesome'] ) {
            wp_enqueue_style( 'font-awesome' );
        }
        wp_enqueue_style( 'berocket_aapf_widget-style' );
        wp_enqueue_style( 'berocket_aapf_widget-scroll-style' );
        wp_enqueue_style( 'berocket_aapf_widget-themer-style' );

        /* custom scrollbar */
        wp_enqueue_script( 'berocket_aapf_widget-scroll-script', plugins_url( 'js/scrollbar/Scrollbar.concat.min.js', __FILE__ ), array( 'jquery' ), BeRocket_AJAX_filters_version );

        /* themer */
        wp_enqueue_script( 'berocket_aapf_widget-themer-script', plugins_url( 'js/styler/formstyler.js', __FILE__ ), array( 'jquery' ), BeRocket_AJAX_filters_version );

        /* main scripts */
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'berocket_aapf_widget-script', plugins_url( 'js/widget.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-slider' ), BeRocket_AJAX_filters_version );
        wp_register_script( 'berocket_aapf_widget-tag_cloud', plugins_url( 'js/j.doe.cloud.min.js', __FILE__ ), array( 'jquery-ui-core' ), BeRocket_AJAX_filters_version );
        wp_register_script( 'berocket_aapf_widget-tag_cloud2', plugins_url( 'js/jquery.tagcanvas.min.js', __FILE__ ), array( 'jquery-ui-core' ), BeRocket_AJAX_filters_version );
        wp_enqueue_script( 'berocket_aapf_jquery-slider-fix', plugins_url( 'js/jquery.ui.touch-punch.min.js', __FILE__ ), array( 'jquery-ui-slider' ), BeRocket_AJAX_filters_version );
    }

    public static function wp_print_special_scripts() {
        wp_enqueue_style( 'jquery-ui-datepick' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
    }

    public static function elements_above_products() {
        $options = self::get_aapf_option();
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
    public static function selected_area() {
        $br_options = apply_filters( 'berocket_aapf_listener_br_options', self::get_aapf_option() );
        set_query_var( 'title', apply_filters( 'berocket_aapf_widget_title', ( empty($title) ? '' : $title ) ) );
        set_query_var( 'uo', br_aapf_converter_styles( ( empty($br_options['styles']) ? '' : $br_options['styles'] ) ) );
        set_query_var( 'selected_area_show', empty($br_options['selected_area_hide_empty']) );
        set_query_var( 'hide_selected_arrow', false );
        set_query_var( 'selected_is_hide', false );
        set_query_var( 'is_hooked', true );
        set_query_var( 'is_hide_mobile', false );
        br_get_template_part( 'widget_selected_area' );
        set_query_var( 'is_hooked', false );
    }

    public static function br_aapf_get_child() {
        $br_options = apply_filters( 'berocket_aapf_listener_br_options', self::get_aapf_option() );
        $taxonomy = $_POST['taxonomy'];
        $type = $_POST['type'];
        $term_id = $_POST['term_id'];
        $term_id = str_replace( '\\', '', $term_id );
        $term_id = json_decode($term_id);
        if ( $type == 'slider' ) {
            $all_terms_name = array();
            $terms_1        = get_terms( $taxonomy );
            $is_numeric = true;
            $terms = array();
            foreach ( $terms_1 as $term_ar ) {
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
            $start_terms    = array_search( $term_id[0], $all_terms_name );
            $end_terms      = array_search( $term_id[1], $all_terms_name );
            $all_terms_name = array_slice( $all_terms_name, $start_terms, ( $end_terms - $start_terms + 1 ) );
            foreach ( $all_terms_name as $term_name ) {
                $term_id = get_term_by ( 'name', $term_name, $taxonomy );
                $args_terms = array(
                    'orderby'    => 'id',
                    'order'      => 'ASC',
                    'hide_empty' => false,
                    'parent'     => $term_id->term_id,
                );
                $current_terms = get_terms( $taxonomy, $args_terms );
                foreach ( $current_terms as $current_term ) {
                    $terms[] = $current_term;
                }
            }
            echo json_encode($terms);
        } else {
            if( is_array($term_id) && count($term_id) > 0 ) {
                $terms = array();
                foreach ( $term_id as $parent ) {
                    $args_terms = array(
                        'orderby'    => 'id',
                        'order'      => 'ASC',
                        'hide_empty' => false,
                        'parent'     => $parent,
                    );
                    if( $taxonomy == 'product_cat' ) {
                        $current_terms = BeRocket_AAPF_Widget::get_product_categories( '', $parent, array(), 0, 0, true );
                    } else {
                        $current_terms = get_terms( $taxonomy, $args_terms );
                    }
                    if( ! is_array( $current_terms ) ) {
                        $current_terms = array();
                    }
                    $new_terms = BeRocket_AAPF_Widget::get_attribute_values( $taxonomy, 'id', ( empty($br_options['show_all_values']) ), ! empty($br_options['recount_products']), $current_terms );
                    if ( is_array( $new_terms ) ) {
                        foreach ( $new_terms as $key => $term_val ) {
                            $new_terms[$key]->color = get_metadata( 'berocket_term', $term_val->term_id, 'color' );
                            $new_terms[$key]->r_class = '';
                            if( ! empty($br_options['hide_value']['o']) && isset($term_val->count) && $term_val->count == 0 ) {
                                $new_terms[$key]->r_class += 'berocket_hide_o_value ';
                            }
                        }
                    }
                    $terms = array_merge( $terms, $new_terms );
                }
                echo json_encode($terms);
            } else {
                echo json_encode($term_id);
            }
        }
        wp_die();
    }

    public static function WPML_fix() {
        global $sitepress;
        if ( method_exists( $sitepress, 'switch_lang' )
             && isset( $_POST['current_language'] )
             && $_POST['current_language'] !== $sitepress->get_default_language()
        ) {
            $sitepress->switch_lang( $_POST['current_language'], true );
        }
    }

    public static function loop_columns($per_row) {
        $options = self::get_aapf_option();
        $per_row = ( ( empty($options['product_per_row']) || ! (int) $options['product_per_row'] || (int) $options['product_per_row'] < 1 ) ? $per_row : (int) $options['product_per_row'] );
        return $per_row;
    }

    public static function order_by_popularity_post_clauses( $args ) {
        global $wpdb;
        $args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";
        return $args;
    }

    public static function order_by_rating_post_clauses( $args ) {
        global $wpdb;
        $args['fields'] .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";
        $args['where'] .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";
        $args['join'] .= "
            LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
            LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
            ";
        $args['orderby'] = "average_rating DESC, $wpdb->posts.post_date DESC";
        $args['groupby'] = "$wpdb->posts.ID";
        return $args;
    }
    public static function sanitize_aapf_option( $input ) {
        $default = BeRocket_AAPF::$defaults;
        $result = self::recursive_array_set( $default, $input );
        return $result;
    }
    public static function recursive_array_set( $default, $options ) {
        foreach( $default as $key => $value ) {
            if( array_key_exists( $key, $options ) ) {
                if( is_array( $value ) ) {
                    if( is_array( $options[$key] ) ) {
                        $result[$key] = self::recursive_array_set( $value, $options[$key] );
                    } else {
                        $result[$key] = self::recursive_array_set( $value, array() );
                    }
                } else {
                    $result[$key] = $options[$key];
                }
            } else {
                if( is_array( $value ) ) {
                    $result[$key] = self::recursive_array_set( $value, array() );
                } else {
                    $result[$key] = '';
                }
            }
        }
        foreach( $options as $key => $value ) {
            if( ! array_key_exists( $key, $result ) ) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    public static function get_aapf_option() {
        $options = get_option( 'br_filters_options' );
        if ( ! empty($options) && is_array ( $options ) ) {
            $options = array_merge( BeRocket_AAPF::$defaults, $options );
        } else {
            $options = BeRocket_AAPF::$defaults;
        }
        return $options;
    }
    public static function wp_footer_widget() {
        global $br_widget_ids;
        if( isset( $br_widget_ids ) && is_array( $br_widget_ids ) && count( $br_widget_ids ) > 0 ) {
            echo '<div class="berocket_wc_shortcode_fix" style="display: none;">';
            foreach ( $br_widget_ids as $widget ) {
                $widget['instance']['br_wp_footer'] = true;
                if( empty($widget['instance']['is_new_widget']) ) {
                    the_widget( 'BeRocket_AAPF_widget', $widget['instance'], $widget['args']);
                } else {
                    the_widget( 'BeRocket_new_AAPF_Widget_single', $widget['instance'], $widget['args']);
                }
            }
            echo '</div>';
        }
    }
    public static function get_attribute_for_variation_link($product, $filters) {
        $attributes = $product->get_variation_attributes();
        $filter_attribute = array();
        if( ! empty($filters) && is_array($filters) ) {
            foreach($filters as $term) {
                if( empty($attributes[$term[0]]) || ! empty($filter_attribute[$term[0]]) ) continue;
                if( in_array($term[3], $attributes[$term[0]]) ) {
                    $filter_attribute[$term[0]] = $term[3];
                }
            }
        }
        return $filter_attribute;
    }
    public static function wp_head() {
        $options = self::get_aapf_option();
        if( ! empty($options['use_filtered_variation']) ) {
            if( ! empty($_SESSION['BeRocket_filters']) && is_product()) {
                $product_id = get_the_ID();
                $product = wc_get_product($product_id);
                if( $product->is_type('variable') ) {
                    if( ! empty($_SESSION['BeRocket_filters']['terms']) ) {
                        $filter_attribute = self::get_attribute_for_variation_link($product, $_SESSION['BeRocket_filters']['terms']);
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
    }
    public static function woocommerce_loop_product_link($link, $product) {
        global $berocket_filters_session;
        if( $product->is_type('variable') && ! empty($berocket_filters_session) ) {
            if( ! empty($berocket_filters_session['terms']) ) {
                $filter_attribute = self::get_attribute_for_variation_link($product, $berocket_filters_session['terms']);
                foreach($filter_attribute as $attribute_name => $attribute_val) {
                    $link = add_query_arg('attribute_'.$attribute_name, $attribute_val, $link);
                }
            }
        }
        return $link;
    }


}

new BeRocket_AAPF;

?>
