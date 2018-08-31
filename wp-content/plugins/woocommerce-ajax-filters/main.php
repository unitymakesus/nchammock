<?php
define( "BeRocket_AJAX_domain", 'BeRocket_AJAX_domain'); 
define( "BeRocket_AJAX_cache_expire", '21600' ); 
define( "AAPF_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('BeRocket_AJAX_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'berocket/framework.php');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once dirname( __FILE__ ) . '/wizard/main.php';
foreach (glob(plugin_dir_path( __FILE__ ) . "includes/compatibility/*.php") as $filename)
{
    include_once($filename);
}
$br_aapf_debugs = array();
class BeRocket_AAPF extends BeRocket_Framework {
    public static $settings_name = 'br_filters_options';
    public $info, $defaults, $values, $notice_array, $conditions;
    protected static $instance;
    public static $debug_mode = false;
    public static $error_log = array();
    public $default_permalink = array (
        'variable' => 'filters',
        'value'    => '/values',
        'split'    => '/',
    );
    function __construct () {
        if( is_admin() ) {
            require_once dirname( __FILE__ ) . '/includes/wizard.php';
        }
        $this->info = array(
            'id'          => 1,
            'version'     => BeRocket_AJAX_filters_version,
            'plugin'      => '',
            'slug'        => '',
            'key'         => '',
            'name'        => '',
            'plugin_name' => 'ajax_filters',
            'full_name'   => 'WooCommerce AJAX Products Filter',
            'norm_name'   => 'Product Filters',
            'price'       => '',
            'domain'      => 'BeRocket_AJAX_domain',
            'templates'   => AAPF_TEMPLATE_PATH,
            'plugin_file' => BeRocket_AJAX_filters_file,
            'plugin_dir'  => __DIR__,
        );
        $this->defaults = array(
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
            'attribute_count'                 => '',
            'control_sorting'                 => '1',
            'seo_friendly_urls'               => '1',
            'seo_uri_decode'                  => '1',
            'slug_urls'                       => '',
            'filters_turn_off'                => '',
            'show_all_values'                 => '',
            'hide_value'                      => array(
                'o'                           => '1',
                'sel'                         => '',
                'empty'                       => '1',
                'button'                      => '1',
            ),
            'use_select2'                     => '',
            'fixed_select2'                   => '',
            'first_page_jump'                 => '1',
            'scroll_shop_top'                 => '',
            'scroll_shop_top_px'              => '-180',
            'recount_products'                => '1',
            'selected_area_show'              => '',
            'selected_area_hide_empty'        => '',
            'products_only'                   => '1',
            'out_of_stock_variable'           => '1',
            'out_of_stock_variable_reload'    => '1',
            'alternative_load'                => '',
            'alternative_load_type'           => 'wpajax',
            'use_get_query'                   => '1',
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
            'custom_css'                 => '',
            'br_opened_tab'                   => 'general',
            'tags_custom'                     => '1',
            'ajax_site'                       => '',
            'search_fix'                      => '1',
            'disable_font_awesome'            => '',
            'debug_mode'                      => '',
            'ajax_request_load'               => '1',
            'ajax_request_load_style'         => 'jquery'
        );
        $this->values = array(
            'settings_name' => 'br_filters_options',
            'option_page'   => 'br-product-filters',
            'premium_slug'  => 'woocommerce-ajax-products-filter',
            'free_slug'     => 'woocommerce-ajax-filters',
        );
        $this->feature_list = array();
        parent::__construct( $this );

        if ( ! function_exists('is_network_admin') || ! is_network_admin() ) {
            if ( $this->init_validation() ) {
                $last_version = get_option('br_filters_version');
                if( $last_version === FALSE ) $last_version = 0;
                if ( version_compare($last_version, BeRocket_AJAX_filters_version, '<') ) {
                    $this->update_from_older ( $last_version );
                }
                unset($last_version);

                $option = $this->get_option();
                if( class_exists('BeRocket_updater') && property_exists('BeRocket_updater', 'debug_mode') ) {
                    self::$debug_mode = ! empty(BeRocket_updater::$debug_mode);
                }
                add_filter( 'BeRocket_updater_error_log', array( $this, 'add_error_log' ) );
                if ( self::$debug_mode ) {
                    self::$error_log['1_settings'] = $option;
                }


                add_action( 'admin_init', array( $this, 'admin_init' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
                add_action( 'current_screen', array( $this, 'register_permalink_option' ) );
                add_shortcode( 'br_filters', array( $this, 'shortcode' ) );
                add_action( 'init', array( $this, 'create_metadata_table' ), 999999999 );
                add_action( 'br_footer_script', array( $this, 'include_all_scripts' ) );
                add_action( 'delete_transient_wc_products_onsale', array( $this, 'delete_products_not_on_sale' ) );

                add_action ( 'widgets_init', array( $this, 'widgets_init' ));
                if ( defined('DOING_AJAX') && DOING_AJAX ) {
                    $this->ajax_functions();
                }
                if ( ! is_admin() ) {
                    if(!session_id()) {
                        session_start();
                    }
                    if( empty($option['styles_in_footer']) ) {
                        add_action( 'wp_head', array( $this, 'br_custom_user_css' ) );
                    }
                    if ( ! defined('DOING_AJAX') || ! DOING_AJAX ) {
                        $this->not_ajax_functions();
                    }

                    if ( isset($_GET['explode']) && $_GET['explode'] == 'explode') {
                        add_action( 'woocommerce_before_template_part', array( 'BeRocket_AAPF_Widget', 'pre_get_posts'), 999999 );
                        add_action( 'wp_footer', array( 'BeRocket_AAPF_Widget', 'end_clean'), 999999 );
                        add_action( 'init', array( 'BeRocket_AAPF_Widget', 'start_clean'), 1 );
                    } else {
                        add_action( 'woocommerce_before_template_part', array( 'BeRocket_AAPF_Widget', 'rebuild'), 999999 );
                    }
                    if ( ! empty($option['selected_area_show']) ) {
                        add_action ( br_get_value_from_array($option, 'elements_position_hook', 'woocommerce_archive_description'), array($this, 'selected_area'), 1 );
                    }
                    if( ! empty($option['ajax_site']) ) {
                        add_action( 'wp_enqueue_scripts', array( $this, 'include_all_scripts' ) );
                    }
                    add_filter( 'is_active_sidebar', array($this, 'is_active_sidebar'), 10, 2);
                }
                if ( ! empty($option['products_per_page']) && ! br_is_plugin_active( 'list-grid' ) && ! br_is_plugin_active( 'List_Grid' ) && ! br_is_plugin_active( 'more-products' ) && ! br_is_plugin_active( 'Load_More_Products' ) ) {
                    add_filter( 'loop_shop_per_page', array($this, 'products_per_page_set'), 9999 );
                }
                if( ! empty($option['products_only']) ) {
                    add_filter('woocommerce_is_filtered', array($this, 'woocommerce_is_filtered'));
                }
                if( ! empty($option['search_fix']) ) {
                    add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
                }
                if( ! empty($option['out_of_stock_variable']) ) {
                    include_once( dirname( __FILE__ ) . '/includes/addons/woocommerce-variation.php' );
                }
                $plugin_base_slug = plugin_basename( __FILE__ );
                add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
                add_filter( 'plugin_action_links_' . $plugin_base_slug, array( $this, 'plugin_action_links' ) );
                add_filter( 'berocket_aapf_widget_terms', array($this, 'wpml_attribute_slug_translate'));
                add_filter ( 'BeRocket_updater_menu_order_custom_post', array($this, 'menu_order_custom_post') );
            } else {
                if( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
                    add_action( 'admin_notices', array( $this, 'update_woocommerce	' ) );
                } else {
                    add_action( 'admin_notices', array( $this, 'no_woocommerce' ) );
                }
            }
        }
    }
    function init_validation() {
        return ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) && 
            br_get_woocommerce_version() >= 2.1 );
    }
    public function init () {
        parent::init();
        
        $option = $this->get_option();
        if( ! empty($option['disable_font_awesome']) ) {
            wp_dequeue_style( 'font-awesome' );
        }
    }
    public function admin_settings( $tabs_info = array(), $data = array() ) {
        $redirect_to_wizard = get_option('berocket_filter_open_wizard_on_settings');
        if( ! empty($redirect_to_wizard) ) {
            delete_option('berocket_filter_open_wizard_on_settings');
            wp_redirect(admin_url( 'admin.php?page=br-aapf-setup' ));
        }
        wp_enqueue_script( 'berocket_aapf_widget-admin' );
        add_filter('brfr_data_ajax_filters', array($this, 'admin_settings_additional'));
        parent::admin_settings(
            array(
                'General' => array(
                    'icon' => 'cog',
                ),
                'Elements' => array(
                    'icon' => 'bars',
                ),
                'Selectors' => array(
                    'icon' => 'circle-o',
                ),
                'SEO' => array(
                    'icon' => 'html5',
                ),
                'Advanced' => array(
                    'icon' => 'cogs',
                ),
                'Design' => array(
                    'icon' => 'eye',
                ),
                'JavaScript/CSS' => array(
                    'icon' => 'css3',
                ),
                'Filters' => array(
                    'icon' => 'plus-square',
                    'link' => admin_url( 'edit.php?post_type=br_product_filter' ),
                ),
                'License' => array(
                    'icon' => 'unlock-alt',
                    'link' => admin_url( 'admin.php?page=berocket_account' )
                ),
            ),
            array(
                'General' => array(
                    'setup_wizard' => array(
                        "section"   => "setup_wizard",
                        "value"     => "",
                    ),
                    'no_products_message' => array(
                        "label"     => __( '"No Products" message', "BeRocket_AJAX_domain" ),
                        "type"      => "text",
                        "name"      => "no_products_message",
                        "value"     => $this->defaults["no_products_message"]
                    ),
                    'products_per_page' => array(
                        "label"     => __( 'Products Per Page', "BeRocket_AJAX_domain" ),
                        "type"      => "number",
                        "name"      => "products_per_page",
                        "value"     => $this->defaults["products_per_page"]
                    ),
                    'attribute_count' => array(
                        "label"     => __( 'Attribute Values count', "BeRocket_AJAX_domain" ),
                        "type"      => "number",
                        "name"      => "attribute_count",
                        "value"     => $this->defaults["attribute_count"],
                        'label_for'  => '<br>' . __( 'Attribute Values count that will be displayed. Other values will be hidden and can be displayed by pressing the button. Option <strong>Hide "Show/Hide value(s)" button</strong> must be disabled', 'BeRocket_AJAX_domain' ),
                    ),
                    'control_sorting' => array(
                        "label"     => __( 'Sorting control', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "control_sorting",
                        "value"     => '1',
                        'label_for'  => __("Take control over WooCommerce's sorting selectbox?", 'BeRocket_AJAX_domain'),
                    ),
                    'hide_values' => array(
                        'label' => __('Hide values', 'BeRocket_AJAX_domain'),
                        'items' => array(
                            'hide_value_o' => array(
                                "type"      => "checkbox",
                                "name"      => array("hide_value", 'o'),
                                "value"     => '1',
                                'label_for'  => __("Hide values without products", 'BeRocket_AJAX_domain'),
                            ),
                            'hide_value_sel' => array(
                                "type"      => "checkbox",
                                "name"      => array("hide_value", 'sel'),
                                "value"     => '1',
                                'label_for'  => __("Hide selected values", 'BeRocket_AJAX_domain'),
                            ),
                            'hide_value_empty' => array(
                                "type"      => "checkbox",
                                "name"      => array("hide_value", 'empty'),
                                "value"     => '1',
                                'label_for'  => __("Hide empty widget", 'BeRocket_AJAX_domain'),
                            ),
                        ),
                    ),
                    'first_page_jump' => array(
                        "label"     => __( 'Jump to first page', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "first_page_jump",
                        "value"     => '1',
                        'label_for'  => __("Check if you want load first page after filters change", 'BeRocket_AJAX_domain'),
                    ),
                    'scroll_shop_top' => array(
                        "label"     => __( 'Scroll page to the top', "BeRocket_AJAX_domain" ),
                        "items"     => array(
                            array(
                                "type"      => "checkbox",
                                "name"      => "scroll_shop_top",
                                "class"     => "br_scroll_shop_top",
                                "value"     => '1',
                                'label_for' => __("Check if you want scroll page to the top of shop after filters change", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            array(
                                "type"      => "number",
                                "name"      => "scroll_shop_top_px",
                                "class"     => "br_scroll_shop_top_px",
                                "value"     => $this->defaults["scroll_shop_top_px"],
                                'label_for' => __("px from products top.", 'BeRocket_AJAX_domain') . ' ' . __('Use this to fix top scroll.', 'BeRocket_AJAX_domain'),
                            )
                        ),
                    ),
                    'select2' => array(
                        "label"     => __( 'Select2', "BeRocket_AJAX_domain" ),
                        'items' => array(
                            'use_select2' => array(
                                "type"      => "checkbox",
                                "name"      => "use_select2",
                                "class"     => "br_use_select2",
                                "value"     => '1',
                                'label_for' => __("Use Select2 script for dropdown menu", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'fixed_select2' => array(
                                "type"      => "checkbox",
                                "name"      => "fixed_select2",
                                "class"     => "br_fixed_select2",
                                "value"     => '1',
                                'label_for' => __("Fixed CSS styles for Select2 (do not enable if Select2 work correct. Option can break Select2 in other plugins or themes)", 'BeRocket_AJAX_domain'),
                            ),
                        )
                    ),
                    'recount_products' => array(
                        "label"     => __( 'Reload amount of products', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "recount_products",
                        "value"     => '1',
                        "class"     => "reload_amount_of_products",
                        'label_for'  => __('Use filters on products count display', 'BeRocket_AJAX_domain') . '<p class="notice notice-error">' . __("Can slow down page load and filtering speed. Also do not use it with more then 5000 products.", 'BeRocket_AJAX_domain') . '</p>',
                    ),
                ),
                'Elements' => array(
                    'elements_position_hook' => array(
                        "label"     => __( 'Selected filters position', "BeRocket_AJAX_domain" ),
                        "name"     => "elements_position_hook",   
                        "type"     => "selectbox",
                        "options"  => array(
                            array('value' => 'woocommerce_archive_description', 'text' => __('WooCommerce Description(in header)', 'BeRocket_AJAX_domain')),
                            array('value' => 'woocommerce_before_shop_loop', 'text' => __('WooCommerce Before Shop Loop', 'BeRocket_AJAX_domain')),
                            array('value' => 'woocommerce_after_shop_loop', 'text' => __('WooCommerce After Shop Loop', 'BeRocket_AJAX_domain')),
                        ),
                        "value"    => 'woocommerce_archive_description',
                    ),
                    'selected_area' => array(
                        "label"     => __( 'Show selected filters', "BeRocket_AJAX_domain" ),
                        'items' => array(
                            'selected_area_show' => array(
                                "type"      => "checkbox",
                                "name"      => "selected_area_show",
                                "class"     => "br_selected_area_show",
                                "value"     => '1',
                                'label_for'  => __("Show selected filters above products", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'selected_area_hide_empty' => array(
                                "type"      => "checkbox",
                                "name"      => "selected_area_hide_empty",
                                "class"     => "br_selected_area_hide_empty",
                                "value"     => '1',
                                'label_for'  => __("Hide selected filters area if nothing selected(affect only area above products)", 'BeRocket_AJAX_domain'),
                            ),
                        )
                    ),
                ),
                'Selectors' => array(
                    'autoselector_set' => array(
                        "section"   => "autoselector",
                        "value"     => "",
                    ),
                    'products_holder_id' => array(
                        "label"     => __( 'Products selector', "BeRocket_AJAX_domain" ),
                        "type"      => "text",
                        "name"      => 'products_holder_id',
                        "value"     => $this->defaults["products_holder_id"],
                        "class"     => "berocket_aapf_products_selector",
                        'label_for' => '<br>' . __("Selector for tag that is holding products. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain'),
                    ),
                    'result_count' => array(
                        "label"     => __( 'Product count selector', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "woocommerce_result_count_class" => array(
                                "type"      => "text",
                                "name"      => 'woocommerce_result_count_class',
                                "value"     => $this->defaults["woocommerce_result_count_class"],
                                "class"     => "berocket_aapf_product_count_selector",
                                'label_for' => '<br>' . __('Selector for tag with product result count("Showing 1–8 of 61 results"). Don\'t change this if you don\'t know what it is', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'woocommerce_removes' => array(
                                "type"      => "checkbox",
                                "name"      => array("woocommerce_removes", "result_count"),
                                "value"     => '1',
                                'label_for' => __("Enable if page doesn't have product count block", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                        ),
                    ),
                    'ordering' => array(
                        "label"     => __( 'Product order by selector', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "woocommerce_ordering_class" => array(
                                "type"      => "text",
                                "name"      => 'woocommerce_ordering_class',
                                "value"     => $this->defaults["woocommerce_ordering_class"],
                                'label_for' => '<br>' . __("Selector for order by form with drop down menu. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'woocommerce_removes' => array(
                                "type"      => "checkbox",
                                "name"      => array("woocommerce_removes", "ordering"),
                                "value"     => '1',
                                'label_for' => __('Enable if page doesn\'t have order by drop down menu', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                        ),
                    ),
                    'pagination' => array(
                        "label"     => __( 'Products pagination selector', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "woocommerce_pagination_class" => array(
                                "type"      => "text",
                                "name"      => 'woocommerce_pagination_class',
                                "value"     => $this->defaults["woocommerce_pagination_class"],
                                "class"     => "berocket_aapf_pagination_selector",
                                'label_for' => '<br>' . __("Selector for tag that is holding products. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'woocommerce_removes' => array(
                                "type"      => "checkbox",
                                "name"      => array("woocommerce_removes", "pagination"),
                                "value"     => '1',
                                'label_for' => __('Enable if page doesn\'t have pagination.<strong>Page with lazy load also has pagination</strong>', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                        ),
                    ),
                ),
                'SEO' => array(
                    'seo_friendly_urls' => array(
                        "label"     => __( 'SEO friendly urls', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "seo_friendly_urls",
                        "value"     => '1',
                        'class'     => 'berocket_seo_friendly_urls',
                        'label_for' => __("If this option is on url will be changed when filter is selected/changed", 'BeRocket_AJAX_domain'),
                    ),
                    'slug_urls' => array(
                        "label"     => __( 'Use slug in URL', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "slug_urls",
                        "value"     => '1',
                        'class'     => 'berocket_use_slug_in_url',
                        'label_for' => __("Use attribute slug instead ID", 'BeRocket_AJAX_domain'),
                    ),
                    'seo_uri_decode' => array(
                        "label"     => __( 'URL decode', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "seo_uri_decode",
                        "value"     => '1',
                        'class'     => 'berocket_uri_decode',
                        'label_for' => __("Decode all symbols in URL to prevent errors on server side", 'BeRocket_AJAX_domain'),
                    ),
                ),
                'Advanced' => array(
                    'pos_relative' => array(
                        "label"     => __( 'Add position relative to products holder', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "pos_relative",
                        "value"     => '1',
                        'label_for' => __('Fix for correct displaying loading block', 'BeRocket_AJAX_domain'),
                    ),
                    'no_products_class' => array(
                        "label"     => __( '"No Products" class', "BeRocket_AJAX_domain" ),
                        "type"      => "text",
                        "name"      => 'no_products_class',
                        "value"     => $this->defaults["no_products_class"],
                        'label_for' => '<br>' . __('Add class and use it to style "No Products" box', 'BeRocket_AJAX_domain'),
                    ),
                    'filters_turn_off' => array(
                        "label"     => __( 'Turn all filters off', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "filters_turn_off",
                        "value"     => '1',
                        'label_for' => __("If you want to hide filters without losing current configuration just turn them off", 'BeRocket_AJAX_domain'),
                    ),
                    'show_all_values' => array(
                        "label"     => __( 'Show all values', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "show_all_values",
                        "value"     => '1',
                        'label_for' => __('Check if you want to show not used attribute values too', 'BeRocket_AJAX_domain'),
                    ),
                    'products_only' => array(
                        "label"     => __( 'Display products', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "products_only",
                        "value"     => '1',
                        'label_for' => __('Display always products when filters selected. Use this when you have categories and subcategories on shop pages, but you want to display products on filtering', 'BeRocket_AJAX_domain'),
                    ),
                    'out_of_stock_variable' => array(
                        "label"     => __( 'Hide out of stock variable', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "out_of_stock_variable" => array(
                                "type"      => "checkbox",
                                "name"      => 'out_of_stock_variable',
                                "value"     => '1',
                                "class"     => "out_of_stock_variable",
                                'label_for' => __('Hide variable products if variations with selected filters out of stock', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'out_of_stock_variable_reload' => array(
                                "type"      => "checkbox",
                                "name"      => "out_of_stock_variable_reload",
                                "value"     => '1',
                                "class"     => "out_of_stock_variable_reload",
                                'label_for' => __('Use it for attributes values to display more correct count with option Reload amount of products', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                        ),
                    ),
                    'use_get_query' => array(
                        "label"     => __('GET query', 'BeRocket_AJAX_domain'),
                        "type"      => "checkbox",
                        "name"      => "use_get_query",
                        "value"     => '1',
                        'label_for' => __('Use GET query instead POST for filtering', 'BeRocket_AJAX_domain') . '<br>',
                    ),
                    'styles_in_footer' => array(
                        "label"     => __( 'Display styles only for pages with filters', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "styles_in_footer",
                        "value"     => '1',
                        'label_for' => __('On some sites it can cause some visual problem on page loads', 'BeRocket_AJAX_domain'),
                    ),
                    'product_per_row' => array(
                        "label"     => __( 'Product per row fix', "BeRocket_AJAX_domain" ),
                        "type"      => "number",
                        "name"      => "product_per_row",
                        "value"     => $this->defaults["product_per_row"],
                        'label_for' => '<br>' . __('Change this only if after filtering count of products per row changes.', 'BeRocket_AJAX_domain'),
                    ),
                    'ajax_site' => array(
                        "label"     => __( 'Fix for sites with AJAX', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "ajax_site",
                        "value"     => '1',
                        'label_for' => __('Add JavaScript files to all pages.', 'BeRocket_AJAX_domain'),
                    ),
                    'search_fix' => array(
                        "label"     => __( 'Search page fix', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "search_fix",
                        "value"     => '1',
                        'label_for' => __('Disable redirection, when search page return only one product', 'BeRocket_AJAX_domain'),
                    ),
                    'tags_custom' => array(
                        "label"     => __( 'Use Tags like custom taxonomy', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "tags_custom",
                        "value"     => '1',
                        'label_for' => __('Try to enable this if widget with tags didn\'t work.', 'BeRocket_AJAX_domain'),
                    ),
                    'disable_font_awesome' => array(
                        "label"     => __( 'Disable loading Font Awesome on front end', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "disable_font_awesome",
                        "value"     => '1',
                        'label_for' => __('Don\'t loading css file for Font Awesome on site front end. Use this only if you doesn\'t uses Font Awesome icons in widgets or you have Font Awesome in your theme.', 'BeRocket_AJAX_domain'),
                    ),
                    'replace_old_widget' => array(
                        "section"   => "replace_old_widget",
                        "value"     => "",
                    ),
                    'alternative_load' => array(
                        "label"     => __( 'Alternative Load (DEPRECATED)', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "alternative_load" => array(
                                "type"      => "checkbox",
                                "name"      => 'alternative_load',
                                "value"     => '1',
                                "class"     => "load_fix_ajax_request_load",
                                'label_for' => __('Use it on your own risk. Some features do not work with alternative load methods. All this methods are not supported. If you have problems with any of them just turn this option off', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'alternative_load_type' => array(
                                "name"     => "alternative_load_type",
                                "class"    => "ajax_request_load_style",
                                "type"     => "selectbox",
                                "options"  => array(
                                    array('value' => 'wpajax', 'text' => __('WordPress AJAX (deprecated)', 'BeRocket_AJAX_domain')),
                                    array('value' => 'php', 'text' => __('PHP (deprecated)', 'BeRocket_AJAX_domain')),
                                    array('value' => 'js', 'text' => __('JavaScript (deprecated)', 'BeRocket_AJAX_domain')),
                                ),
                                "value"    => '',
                                "label_be_for" => __('Use', 'BeRocket_AJAX_domain'),
                                "label_for" => __('load method', 'BeRocket_AJAX_domain') . '<br>'
                                . '<p class="notice notice-error">' . __('Some features do not work with alternative load method', 'BeRocket_AJAX_domain') . '</p>',
                            ),
                        ),
                    ),
                ),
                'Design' => array(
                    'design' => array(
                        'section' => 'design',
                        "value"   => "",
                    ),
                ),
                'JavaScript/CSS' => array(
                    'before_update' => array(
                        "label"     => __( 'Before Update:', "BeRocket_AJAX_domain" ),
                        "type"      => "textarea",
                        "name"      => array("user_func", "before_update"),
                        "value"     => $this->defaults["user_func"]["before_update"],
                        "label_for" => __( "If you want to add own actions on filter activation, eg: alert('1');", "BeRocket_AJAX_domain" ),
                    ),
                    'on_update' => array(
                        "label"     => __( 'On Update:', "BeRocket_AJAX_domain" ),
                        "type"      => "textarea",
                        "name"      => array("user_func", "on_update"),
                        "value"     => $this->defaults["user_func"]["on_update"],
                        "label_for" => __( "If you want to add own actions right on products update. You can manipulate data here, try: data.products = 'Ha!';", "BeRocket_AJAX_domain" ),
                    ),
                    'after_update' => array(
                        "label"     => __( 'After Update:', "BeRocket_AJAX_domain" ),
                        "type"      => "textarea",
                        "name"      => array("user_func", "after_update"),
                        "value"     => $this->defaults["user_func"]["after_update"],
                        "label_for" => __( "If you want to add own actions after products updated, eg: alert('1');", "BeRocket_AJAX_domain" ),
                    ),
                    'custom_css' => array(
                        'section' => 'custom_css',
                        "value"   => "",
                    ),
                ),
            )
        );
    }
    public function admin_settings_additional($data) {
        if ( br_is_plugin_active( 'list-grid' ) || br_is_plugin_active( 'List_Grid' ) || br_is_plugin_active( 'more-products' ) || br_is_plugin_active( 'Load_More_Products' ) ) {
            unset($data['General']['products_per_page']);
        }
        return $data;
    }
    public function section_setup_wizard ( $item, $options ) {
        $html = '';
        if( apply_filters('br_filters_options-setup_wizard-show', true) ) {
            $html .= '<tr>
                <th scope="row">' . __('SETUP WIZARD', 'BeRocket_AJAX_domain') . '</th>
                <td>
                    <a class="button" href="' . admin_url( 'admin.php?page=br-aapf-setup' ) . '">' . __('RUN SETUP WIZARD', 'BeRocket_AJAX_domain') . '</a>
                    <div>
                        ' . __('Run it to setup plugin options step by step', 'BeRocket_AJAX_domain') . '
                    </div>
                </td>
            </tr>';
        }
        return $html;
    }
    public function section_autoselector ( $item, $options ) {
        do_action('BeRocket_wizard_javascript');
        $html = '<tr>
            <th scope="row">' . __('Get selectors automatically', 'BeRocket_AJAX_domain') . '</th>
            <td>
                <h4>' . __('How it work:', 'BeRocket_AJAX_domain') . '</h4>
                <ol>
                    <li>' . __('Run Auto-selector', 'BeRocket_AJAX_domain') . '</li>
                    <li>' . __('Wait until end <strong style="color:red;">do not close this page</strong>', 'BeRocket_AJAX_domain') . '</li>
                    <li>' . __('Save settings with new selectors', 'BeRocket_AJAX_domain') . '</li>
                </ol>
                ' . BeRocket_wizard_generate_autoselectors(array('products' => '.berocket_aapf_products_selector', 'pagination' => '.berocket_aapf_pagination_selector', 'result_count' => '.berocket_aapf_product_count_selector')) . '
            </td>
        </tr>';
        return $html;
    }
    public function section_replace_old_widget ( $item, $options ) {
        $html = '<tr>
            <th scope="row">' . __('Replace old widgets', 'BeRocket_AJAX_domain') . '</th>
            <td>';
            $old_filter_widgets = get_option('widget_berocket_aapf_widget');
                if( ! is_array($old_filter_widgets) ) {
                    $old_filter_widgets = array();
                }
                foreach ($old_filter_widgets as $key => $value) {
                    if (!is_numeric($key)) {
                        unset($old_filter_widgets[$key]);
                    }
                }
                $html .= '<span 
                    class="button berocket_replace_deprecated_with_new' . ( !count($old_filter_widgets) ? ' berocket_ajax_sending' : '' ) . '"
                    data-ready="' . __('Widget replaced', 'BeRocket_AJAX_domain') . '"
                    data-loading="' . __('Replacing widgets... Please wait', 'BeRocket_AJAX_domain') . '"';
                    if( !count($old_filter_widgets) ) $html .= ' disabled="disabled"';
                    $html .= '>';
                    if( count($old_filter_widgets) ) { 
                        $html .= __('Replace widgets', 'BeRocket_AJAX_domain'); 
                    } else {
                        $html .= __('No old widgets', 'BeRocket_AJAX_domain');
                    }
                $html .= '</span>
                <p>' . __('Replace deprecated widgets with new single filter widgets', 'BeRocket_AJAX_domain') . '</p>
                <script>
                    jQuery(".berocket_replace_deprecated_with_new").click(function() {
                        var $this = jQuery(this);
                        if( ! $this.is(".berocket_ajax_sending") ) {
                            $this.data("text", $this.text());
                            $this.attr("disabled", "disabled");
                            $this.text($this.data("loading"));
                            $this.addClass("berocket_ajax_sending");
                            jQuery.post(ajaxurl, {action:"replace_deprecated_with_new"}, function() {
                                $this.text($this.data("ready"));
                            });
                        }
                    });
                </script>
            </td>
        </tr>';
        return $html;
    }
    public function section_custom_css ( $item, $options ) {
        $html = '</table>
            <table class="form-table">
                <tr>
                    <th colspan="2">' . __('User custom CSS style:', 'BeRocket_AJAX_domain') . '</th>
                </tr>
                <tr>
                    <td style="width:600px;">
                        <textarea style="width: 100%; min-height: 400px; height:900px" name="br_filters_options[user_custom_css]">' . br_get_value_from_array($options, 'user_custom_css') . '</textarea>
                    </td>
                    <td><div class="berocket_css_examples"style="max-width:300px;">
                        <h4>Add border to widget</h4>
<div style="background-color:white;"><pre>#widget#{
    border:2px solid #FF8800;
}</pre></div>
                        <h4>Set font size and font color for title</h4>
<div style="background-color:white;"><pre>#widget-title#{
    font-size:36px!important;
    color:orange!important;
}</pre></div>
                        <h4>Display all inline</h4>
<div style="background-color:white;"><pre>#widget# li{
    display: inline-block;
}</pre></div>
                        <h4>Use WooCommerce font for checkbox</h4>
<div style="background-color:white;">
<pre>#widget# li:not(.berocket_checkbox_color) input[type=checkbox] {
    display: none!important;
}
#widget# li:not(.berocket_checkbox_color) input[type=checkbox] + label:before{
    font-family: WooCommerce!important;
    speak: none!important;
    font-weight: 400!important;
    font-variant: normal!important;
    text-transform: none!important;
    content: "\e039"!important;
    text-decoration: none!important;
    background:none!important;
    display: inline-block!important;
    border: 0!important;
    margin-right: 5px!important;
}
#widget# li:not(.berocket_checkbox_color) input[type=checkbox]:checked + label:before {
    content: "\e015"!important;
}</pre></div>
                        <h4>Use block for slider handler instead image</h4>
<div style="background-color:white;"><pre>#widget# .ui-slider-handle {
    background:none!important;
    border-radius:50px!important;
    background-color:white!important;
    border: 2px solid black!important;
    outline:none!important;
}
#widget# .ui-slider-handle.ui-state-active {
    border: 3px solid black!important;
}</pre></div>
<style>
.berocket_css_examples {
    width:300px;
    overflow:visible;
}
.berocket_css_examples div{
    background-color:white;
    width:100%;
    min-width:100%;
    overflow:hidden;
    float:right;
    border:1px solid white;
    padding: 2px;
}
.berocket_css_examples div:hover {
    position:relative;
    z-index: 9999;
    width: initial;
    border:1px solid #888;
}
</style>
                    </div></td>
                </tr>
            </table>
            <table>';
        $html .= "
<script>
function out_of_stock_variable_reload_hide() {
    if( jQuery('.reload_amount_of_products').prop('checked') && jQuery('.out_of_stock_variable').prop('checked') ) {
        jQuery('.out_of_stock_variable_reload').parent().show();
    } else {
        jQuery('.out_of_stock_variable_reload').parent().hide();
    }
}
out_of_stock_variable_reload_hide();
jQuery('.reload_amount_of_products, .out_of_stock_variable').on('change', out_of_stock_variable_reload_hide);
function load_fix_ajax_request_load() {
    if( jQuery('.load_fix_ajax_request_load').prop('checked') ) {
        jQuery('.load_fix_use_get_query').parent().show();
        jQuery('.ajax_request_load_style').parent().show();
    } else {
        jQuery('.load_fix_use_get_query').parent().hide();
        jQuery('.ajax_request_load_style').parent().hide();
    }
}
load_fix_ajax_request_load();
jQuery(document).on('change', '.load_fix_ajax_request_load', load_fix_ajax_request_load);
function br_scroll_shop_top() {
    if( jQuery('.br_scroll_shop_top').prop('checked') ) {
        jQuery('.br_scroll_shop_top_px').parent().show();
    } else {
        jQuery('.br_scroll_shop_top_px').parent().hide();
    }
}
br_scroll_shop_top();
jQuery(document).on('change', '.br_scroll_shop_top', br_scroll_shop_top);

function br_use_select2() {
    if( jQuery('.br_use_select2').prop('checked') ) {
        jQuery('.br_fixed_select2').parent().show();
    } else {
        jQuery('.br_fixed_select2').parent().hide();
    }
}
br_use_select2();
jQuery(document).on('change', '.br_use_select2', br_use_select2);

function br_selected_area_show() {
    if( jQuery('.br_selected_area_show').prop('checked') ) {
        jQuery('.br_selected_area_hide_empty').parent().show();
    } else {
        jQuery('.br_selected_area_hide_empty').parent().hide();
    }
}
br_selected_area_show();
jQuery(document).on('change', '.br_selected_area_show', br_selected_area_show);
</script>";
        return $html;
    }
    public function section_design($item, $options) {
        $designables = br_aapf_get_styled();
        ob_start();
        include AAPF_TEMPLATE_PATH.'settings/design.php';
        $html = ob_get_clean();
        return $html;
    }
    public function admin_init () {
        parent::admin_init();
        if ( is_admin() ) {
            /* BeRocket WordPress Admin menu styles */
            wp_register_style(
                'berocket_framework_global_admin_style',
                plugins_url( 'css/global-admin.css', __FILE__ ),
                "",
                $this->info[ 'version' ]
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
        register_setting( 'br_filters_plugin_options', 'br_filters_options', array( $this, 'sanitize_aapf_option' ) );
    }
    public function is_active_sidebar($is_active_sidebar, $index) {
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
    public function products_per_page_set() {
        $option = $this->get_option();
        return $option['products_per_page'];
    }
    public function wpml_attribute_slug_translate($terms) {
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
    function ajax_functions() {
        add_action( "wp_ajax_replace_deprecated_with_new", array ( $this, 'replace_deprecated_with_new' ) );
        add_action( 'setup_theme', array( $this, 'WPML_fix' ) );
        add_action('plugins_loaded', array($this, 'wp_hook'));
        add_action( "wp_ajax_br_aapf_get_child", array ( $this, 'br_aapf_get_child' ) );
        add_action( "wp_ajax_nopriv_br_aapf_get_child", array ( $this, 'br_aapf_get_child' ) );
        add_action( "wp_ajax_aapf_color_set", array ( 'BeRocket_AAPF_Widget', 'color_listener' ) );
        BeRocket_AAPF_Widget::br_widget_ajax_set();
    }
    function not_ajax_functions() {
        add_filter( 'pre_get_posts', array( $this, 'apply_user_price' ) );
        add_filter( 'pre_get_posts', array( $this, 'apply_user_filters' ), 900000 );
        add_filter( 'woocommerce_shortcode_products_query', array( $this, 'woocommerce_shortcode_products_query' ), 10, 3 );
    }
    function wp_hook() {
        add_filter('loop_shop_columns', array( $this, 'loop_columns' ), 999 );
    }
    public function replace_deprecated_with_new() {
        require_once dirname( __FILE__ ) . '/fixes/replace_widgets.php';
        wp_die();
    }
    public function widgets_init() {
        register_widget("BeRocket_AAPF_widget");
        register_widget("BeRocket_new_AAPF_Widget");
        register_widget("BeRocket_new_AAPF_Widget_single");
    }
    public function woocommerce_is_filtered($filtered) {
        if ( apply_filters( 'berocket_aapf_is_filtered_page_check', ! empty($_GET['filters']), 'woocommerce_is_filtered' ) ) {
            $filtered = true;
        }
        return $filtered;
    }
    public function include_all_scripts() {
        /* theme scripts */
        if( defined('THE7_VERSION') && THE7_VERSION ) {
            wp_enqueue_script( 'berocket_ajax_fix-the7', plugins_url( 'js/themes/the7.js', __FILE__ ), array( 'jquery' ), BeRocket_AJAX_filters_version );
            add_filter('berocket_aapf_user_func', array($this, 'the7_fix'));
        }
        global $wp_query, $wp, $sitepress, $wp_rewrite;
        $this->wp_print_special_scripts();
        $br_options = apply_filters( 'berocket_aapf_listener_br_options', $this->get_option() );
        if( ! empty($br_options['styles_in_footer']) ) {
            add_action( 'wp_footer', array( $this, 'br_custom_user_css' ) );
        }
        if( ! empty($br_options['user_func']) && is_array( $br_options['user_func'] ) ) {
            $user_func = array_merge( $this->defaults['user_func'], $br_options['user_func'] );
        } else {
            $user_func = $this->defaults['user_func'];
        }

        $this->wp_print_footer_scripts();

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

        $current_page_url = preg_replace( "~paged?/[0-9]+/?~", "", home_url( $wp->request ) );
        $current_page_url = apply_filters('berocket_aapf_current_page_url', $current_page_url, $br_options);
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
                'nice_url_variable'                    => '',
                'nice_url_value_1'                     => '',
                'nice_url_value_2'                     => '',
                'nice_url_split'                       => '',
                'version'                              => BeRocket_AJAX_filters_version,
                'number_style'                         => array('', '.', '2'),
                'current_language'                     => $current_language,
                'current_page_url'                     => $current_page_url,
                'ajaxurl'                              => admin_url( 'admin-ajax.php' ),
                'product_cat'                          => $wp_query_product_cat,
                'product_taxonomy'                     => $product_taxonomy,
                's'                                    => ( ! empty( $_GET['s'] ) ? $_GET['s'] : '' ),
                'products_holder_id'                   => ( empty($br_options['products_holder_id']) ? '' : $br_options['products_holder_id'] ),
                'result_count_class'                   => ( ! empty($br_options['woocommerce_result_count_class']) ? $br_options['woocommerce_result_count_class'] : $this->defaults['woocommerce_result_count_class'] ),
                'ordering_class'                       => ( ! empty($br_options['woocommerce_ordering_class']) ? $br_options['woocommerce_ordering_class'] : $this->defaults['woocommerce_ordering_class'] ),
                'pagination_class'                     => ( ! empty($br_options['woocommerce_pagination_class']) ? $br_options['woocommerce_pagination_class'] : $this->defaults['woocommerce_pagination_class'] ),
                'control_sorting'                      => ( empty($br_options['control_sorting']) ? '' : $br_options['control_sorting'] ),
                'seo_friendly_urls'                    => ( empty($br_options['seo_friendly_urls']) ? '' : $br_options['seo_friendly_urls'] ),
                'seo_uri_decode'                       => ( empty($br_options['seo_uri_decode']) ? '' : $br_options['seo_uri_decode'] ),
                'slug_urls'                            => ( empty($br_options['slug_urls']) ? '' : $br_options['slug_urls'] ),
                'nice_urls'                            => '',
                'ub_product_count'                     => '',
                'ub_product_text'                      => '',
                'ub_product_button_text'               => '',
                'berocket_aapf_widget_product_filters' => $post_temrs,
                'user_func'                            => apply_filters( 'berocket_aapf_user_func', $user_func ),
                'default_sorting'                      => get_option('woocommerce_default_catalog_orderby'),
                'first_page'                           => ( empty($br_options['first_page_jump']) ? '' : $br_options['first_page_jump'] ),
                'scroll_shop_top'                      => ( empty($br_options['scroll_shop_top']) ? '' : $br_options['scroll_shop_top'] ),
                'ajax_request_load'                    => ( ! empty($br_options['alternative_load']) && $br_options['alternative_load_type'] == 'wpajax' ? '' : '1' ),
                'ajax_request_load_style'              => ( empty($br_options['alternative_load']) ? 'jquery' : ( empty($br_options['alternative_load_type']) ? $this->defaults['alternative_load_type'] : $br_options['alternative_load_type'] ) ),
                'use_request_method'                   => ( ! empty($br_options['use_get_query']) && empty($br_options['alternative_load']) ? 'get' : 'post' ),
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
                'hide_button_value'                    => '',
                'scroll_shop_top_px'                   => ( ! empty( $br_options['scroll_shop_top_px'] ) ? $br_options['scroll_shop_top_px'] : $this->defaults['scroll_shop_top_px'] ),
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
                'pagination_base'                      => $wp_rewrite->pagination_base,
            ) )
        );
    }
    public function the7_fix($scripts) {
        $scripts['after_update'] = 'fixWooIsotope();fixWooOrdering(); '.$scripts['after_update'];
        return $scripts;
    }
    public function add_error_log( $error_log ) {
        $error_log[plugin_basename( __FILE__ )] =  self::$error_log;
        return $error_log;
    }
    public function update_from_older( $version ) {
        $option = $this->get_option();
        $version_index = 6;
        if( version_compare($version, '2.0', '>') ) {
            if ( version_compare($version, '2.0.4', '<') ) {
                $version_index = 1;
            } elseif ( version_compare($version, '2.0.5', '<') ) {
                $version_index = 2;
            } elseif ( version_compare($version, '2.0.9.7', '<') ) {
                $version_index = 3;
            } elseif ( ! empty($version) && version_compare($version, '2.1', '<') ) {
                $version_index = 4;
            } elseif ( ! empty($version) && version_compare($version, '2.2', '<') ) {
                $version_index = 5;
            }
        }

        switch ( $version_index ) {
            case 1:
                update_option('berocket_filter_open_wizard_on_settings', true);
            case 2:
                update_option( 'berocket_permalink_option', $this->default_permalink );
            case 3:
                $new_filter_widgets = get_option('widget_berocket_aapf_group');
                if( is_array($new_filter_widgets) ) {
                    foreach($new_filter_widgets as &$new_filter_widget) {
                        if( is_array($new_filter_widget) && isset($new_filter_widget['title']) ) {
                            $new_filter_widget['title'] = '';
                        }
                    }
                    update_option('widget_berocket_aapf_group', $new_filter_widgets);
                }
            case 4:
            case 5:
                if( ! empty($version) ) {
                    $BeRocket_AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
                    $filters = $BeRocket_AAPF_single_filter->get_custom_posts();
                    foreach($filters as $filter) {
                        $filter_option = $BeRocket_AAPF_single_filter->get_option($filter);
                        if( empty($filter_option['widget_collapse_disable']) ) {
                            $filter_option['widget_collapse_enable'] = '1';
                        } else {
                            $filter_option['widget_collapse_enable'] = '';
                        }
                        $filter_post = get_post($filter);
                        $_POST[$BeRocket_AAPF_single_filter->post_name] = $filter_option;
                        $BeRocket_AAPF_single_filter->wc_save_product_without_check($filter, $filter_post);
                    }
                }
        }

        update_option( 'br_filters_options', $option );
        update_option( 'br_filters_version', BeRocket_AJAX_filters_version );
    }
    public function no_woocommerce() {
        echo '
        <div class="error">
            <p>' . __( 'Activate WooCommerce plugin before', 'BeRocket_AJAX_domain' ) . '</p>
        </div>';
    }
    public function update_woocommerce() {
        echo '
        <div class="error">
            <p>' . __( 'Update WooCommerce plugin', 'BeRocket_AJAX_domain' ) . '</p>
        </div>';
    }
    public function shortcode( $atts = array() ) {
        if( self::$debug_mode ) {
            if( ! isset( self::$error_log['2_shortcodes'] ) )
            {
                self::$error_log['2_shortcodes'] = array();
            } 
            self::$error_log['2_shortcodes'][] = $atts;
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
    public function woocommerce_shortcode_products_query( $query_vars, $atts = array(), $name = 'products' ) {
        if( ! is_shop() && ! is_product_taxonomy() && ! is_product_category() && ! is_product_tag() ) {
            $query_vars = $this->woocommerce_filter_query_vars($query_vars, $atts, $name);
        }
        return $query_vars;
    }
    public function woocommerce_filter_query_vars( $query_vars, $atts = array(), $name = 'products' ) {
        $new_query_vars = $query_vars;
        $new_query_vars['nopaging'] = true;
        $new_query_vars['fields'] = 'ids';
        $query = new WP_Query( $new_query_vars );
        global $br_shortcode_query;
        $br_shortcode_query = $query;
        global $wp_query;
        $args = $this->get_filter_args($wp_query, true);
        $args_fields = array( 'meta_key', 'tax_query', 'fields', 'where', 'join', 'meta_query', 'date_query' );
        foreach ( $args_fields as $args_field ) {
            if ( ! empty($args[ $args_field ]) ) {
                if( ! empty($query_vars[ $args_field ]) && is_array($query_vars[ $args_field ]) ) {
                    $query_vars[ $args_field ] = array_merge($query_vars[ $args_field ], $args[ $args_field ]);
                } else {
                    $query_vars[ $args_field ] = $args[ $args_field ];
                }
            }
        }
        if( empty($query_vars['post__in']) ) {
            $query_vars['post__in'] = array();
        }
        if( empty($query_vars['post__not_in']) ) {
            $query_vars['post__not_in'] = array();
        }
        $custom_terms = berocket_isset($_POST['terms']);
        if( ! empty($atts['attribute']) ) {
            if( ! empty($atts['terms']) ) {
                $terms = explode(',',$atts['terms']);
                foreach($terms as &$term) {
                    $term = get_term_by( 'slug', $term, 'pa_'.$atts['attribute']);
                }
            } else {
                $terms = get_terms( array(
                    'taxonomy' => 'pa_'.$atts['attribute'],
                    'hide_empty' => true,
                ) );
            }
            if( ! is_array($custom_terms) ) {
                $custom_terms = array();
            }
            foreach($terms as $term) {
                $custom_terms[] = array(
                    $term->taxonomy,
                    $term->term_id,
                    'OR',
                    $term->slug,
                    'attribute'
                );
            }
        }
        $query_vars['post__not_in'] = array_merge($query_vars['post__not_in'], apply_filters('berocket_add_out_of_stock_variable', array(), $custom_terms, berocket_isset($_POST['limits_arr'])));
        $query_vars['post__in'] = apply_filters( 'loop_shop_post_in', $query_vars['post__in']);
        global $br_wc_query;
        $br_wc_query = new WP_Query( $query_vars );
        return $query_vars;
    }
    public function display_products() {
        return '';
    }
    public function apply_user_price( $query, $is_shortcode = FALSE ) {
        $options = $this->get_option();
        if( class_exists('WC_Query') && method_exists('WC_Query', 'get_main_query') ) {
            $wc_query = WC_Query::get_main_query();
            $is_wc_main_query = $wc_query === $query;
        } else {
            $is_wc_main_query = $query->is_main_query();
        }
        if ( ( ( ! is_admin() && $is_wc_main_query ) || $is_shortcode ) && ( apply_filters( 'berocket_aapf_is_filtered_page_check', ! empty($_GET['filters']), 'apply_user_price', $query ) ) ) {
            br_aapf_args_converter( $query );
            if ( ! empty($_POST['price']) ) {
                add_filter( 'loop_shop_post_in', array( $this, 'price_filter' ) );
            }
            if( ! empty($options['products_only']) ) {
                add_filter('pre_option_woocommerce_shop_page_display', array( $this, 'display_products' ), 99999);
                add_filter('pre_option_woocommerce_category_archive_display', array( $this, 'display_products' ), 99999);
            }
        }
        return $query;
    }
    public function apply_user_filters( $query, $is_shortcode = FALSE ) {
        $options = $this->get_option();
        if( self::$debug_mode ) {
            if ( empty( self::$error_log['8_1_query_in'] ) || ! is_array( self::$error_log['8_1_query_in'] ) ) {
                self::$error_log['8_1_query_in'] = array();
            }
            self::$error_log['8_1_query_in'][] = $query;
            self::$error_log['PERMALINK'] = get_option('permalink_structure');
        }
        if( class_exists('WC_Query') && method_exists('WC_Query', 'get_main_query') ) {
            $wc_query = WC_Query::get_main_query();
            $is_wc_main_query = $wc_query === $query || $query->is_main_query();
            if( $is_wc_main_query && ! $query->is_main_query() ) {
                $is_shortcode = true;
            }
        } else {
            $is_wc_main_query = $query->is_main_query();
        }
        if( apply_filters( 'berocket_aapf_is_filtered_page_check', ! empty($_GET['filters']), 'apply_user_filters', $query ) ) {
            br_aapf_args_converter( $query );
        }
        if ( ( ( ! is_admin() && $is_wc_main_query ) || $is_shortcode ) && ( apply_filters( 'berocket_aapf_is_filtered_page_check', ! empty($_GET['filters']), 'apply_user_filters', $query ) ) 
        && ( ( isset($query->query_vars['wc_query']) && $query->query_vars['wc_query'] == 'product_query' ) || ( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'product' ) ) ) {

            $args = $this->get_filter_args($query);
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
            if( self::$debug_mode ) {
                self::$error_log['8_query_out'] = $query;
            }
            $query = apply_filters('berocket_filters_query_already_filtered', $query, berocket_isset($_POST['terms']), berocket_isset($_POST['limits_arr']));
        }

        if ( ( ! is_admin() && $query->is_main_query() ) || $is_shortcode ) {
            global $br_wc_query;
            $br_wc_query = $query;
        }
        if ( $is_shortcode ) {
            add_action( 'wp_footer', array( $this, 'wp_footer_widget'), 99999 );
        }

        if( self::$debug_mode ) {
            self::$error_log['8_2_query_out'] = $query;
        }

        return apply_filters('berocket_aapf_return_query_filtered', $query, $is_shortcode);
    }
    public function get_filter_args($query, $is_shortcode = false) {
        $options = $this->get_option();
        $args = array();
        if ( apply_filters( 'berocket_aapf_is_filtered_page_check', ! empty($_GET['filters']), 'get_filter_args', $query ) ) {
            br_aapf_args_converter( $query );

            if( self::$debug_mode ) {
                self::$error_log['8_query_in'] = $query;
            }
            if( ! empty($options['products_only']) ) {
                add_filter('pre_option_woocommerce_shop_page_display', array( $this, 'display_products' ), 99999);
                add_filter('pre_option_woocommerce_category_archive_display', array( $this, 'display_products' ), 99999);
            }

            $old_post_terms                      = ( empty($_POST['terms']) ? '' : $_POST['terms'] );
            $woocommerce_hide_out_of_stock_items = BeRocket_AAPF_Widget::woocommerce_hide_out_of_stock_items();
            $meta_query                          = $this->remove_out_of_stock( array(), true, $woocommerce_hide_out_of_stock_items != 'yes' );

            $args = br_aapf_args_parser();
            if ( isset( $args['meta_query'] ) ) {
                $args['meta_query'] += $meta_query;
            } else {
                $args['meta_query'] = $meta_query;
            }
            $_POST['terms'] = $old_post_terms;
            $this->wcml_currency_price_fix();
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
                        add_filter( 'loop_shop_post_in', array( $this, 'price_filter' ) );
                    }
                }
            }

            $args = apply_filters( 'berocket_aapf_filters_on_page_load', $args );
            if( self::$debug_mode ) {
                self::$error_log['3_user_filters'] = $args;
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
        }
        return $args;
    }
    public function remove_out_of_stock( $filtered_posts, $use_post_terms = false, $show_out_of_stock = false ) {
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

        if( self::$debug_mode ) {
            self::$error_log['104_remove_out_of_stock_SELECT'] = $query_string;
            $wpdb->show_errors();
        }

        // TODO: split this into 2 queries(product and product_variation) this way we will not be using all data at the same time
        $matched_products_query = $wpdb->get_results( $query_string, OBJECT_K );
        unset( $query_string );
        $matched_products = array( 0 );

        if( self::$debug_mode ) {
            self::$error_log['000_select_status'][] = @ $wpdb->last_error;
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
    public function remove_hidden( $filtered_posts ){
        global $wpdb;

        $query_string = "
            SELECT DISTINCT ID, post_parent, post_type FROM $wpdb->posts
            INNER JOIN $wpdb->postmeta as meta ON ID = meta.post_id
            WHERE post_type IN ( 'product', 'product_variation' ) AND post_status = 'publish'
            AND meta_key = '_visibility' AND meta_value NOT IN ('hidden', 'search')";

        if( self::$debug_mode ) {
            self::$error_log['105_remove_hidden_SELECT'] = $query_string;
            $wpdb->show_errors();
        }

        $matched_products_query = $wpdb->get_results( $query_string, OBJECT_K );
        unset( $query_string );
        $matched_products = array( 0 );

        if( self::$debug_mode ) {
            self::$error_log['000_select_status'][] = @ $wpdb->last_error;
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
    public function delete_products_not_on_sale($transient) {
        delete_transient( 'wc_products_notonsale' );
    }
    public function price_filter( $filtered_posts ) {
        $option = $this->get_aapf_option();
        if( empty($option['slider_compatibility']) ) {
            return $filtered_posts;
        }
        global $wpdb;

        if ( ! empty($_POST['price']) || ! empty($_POST['price_ranges']) ) {
            $matched_products = array( 0 );
            if ( ! empty($_POST['price']) ) {
                $min              = floatval( $_POST['price'][0] );
                $max              = floatval( $_POST['price'][1] );

                if( self::$debug_mode ) {
                    if( ! isset( self::$error_log['5_price'] ) )
                    {
                        self::$error_log['5_price'] = array();
                    } 
                    self::$error_log['5_price']['select'] = 'from '.$min.' to '.$max;
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

                if( self::$debug_mode ) {
                    self::$error_log['000_select_status'][] = @ $wpdb->last_error;
                    self::$error_log['0099_price'][] = $wpdb->prepare( "
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
    public function register_permalink_option() {
        $screen = get_current_screen();
        $default_values = $this->default_permalink;
        if($screen->id == 'options-permalink') {
            $this->save_permalink_option($default_values);
            $this->_register_permalink_option($default_values);
        }
        if(strpos($screen->id, 'widgets') !== FALSE || strpos($screen->id, 'br-product-filters') !== FALSE) {
            $this->register_admin_scripts();
        }
        if( self::$debug_mode ) {
            self::$error_log['21_current_screen'] = $screen;
        }
    }
    public function register_admin_scripts(){
        wp_enqueue_script( 'brjsf-ui');
        wp_enqueue_style( 'brjsf-ui' );
        wp_enqueue_style( 'font-awesome' );
    }
    public function _register_permalink_option($default_values) {
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
    public function save_permalink_option( $default_values ) {
        if ( isset( $_POST['berocket_permalink_option'] ) ) {
            $option_values    = $_POST['berocket_permalink_option'];
            $data             = shortcode_atts( $default_values, $option_values );
            $data['variable'] = $data['variable'];

            update_option( 'berocket_permalink_option', $data );
        }
    }
    public function new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
        global $wpdb;
        if ( is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
            $old_blog = $wpdb->blogid;
            switch_to_blog($blog_id);
            $this->_br_add_defaults();
            switch_to_blog($old_blog);
        }
    }
    public function br_add_defaults( $networkwide ) {
        global $wpdb;
        if ( function_exists('is_multisite') && is_multisite() ) {
            if ( $networkwide) {
                $old_blog = $wpdb->blogid;
                $blogids  = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

                foreach ( $blogids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    $this->_br_add_defaults();
                }

                switch_to_blog( $old_blog );
                return;
            }
        } 
        $this->_br_add_defaults();
    }
    public function _br_add_defaults() {
        $tmp = $this->get_option();
        $tmp2 = get_option( 'berocket_permalink_option' );
        $version = get_option( 'br_filters_version' );
        if ( isset($tmp['chk_default_options_db']) and ($tmp['chk_default_options_db'] == '1' or ! is_array( $tmp )) ) {
            delete_option( 'br_filters_options' );
            update_option( 'br_filters_options', self::$defaults );
        }
        if ( ( isset($tmp['chk_default_options_db']) and $tmp['chk_default_options_db'] == '1' ) or !is_array( $tmp2 ) ) {
            delete_option( 'berocket_permalink_option' );
            update_option( 'berocket_permalink_option', $this->default_permalink );
        }
    }
    public function br_delete_plugin_options($networkwide) {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) {
            if ($networkwide) {
                $old_blog = $wpdb->blogid;
                $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    $this->_br_delete_plugin_options();
                }
                switch_to_blog($old_blog);
                return;
            }
        }
        $this->_br_delete_plugin_options();
    }
    public function _br_delete_plugin_options() {
        delete_option( 'br_filters_options' );
        delete_option( 'berocket_permalink_option' );
    }
    public function convert_styles_to_string(&$style) {
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
    public function br_custom_user_css() {
        $options     = $this->get_option();
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
        echo $this->convert_styles_to_string($options['styles_input']['checkbox']);
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
        echo $this->convert_styles_to_string($options['styles_input']['radio']);
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
        echo $this->convert_styles_to_string($options['styles_input']['onlyTitle_title']);
        echo '}';
        echo 'div.berocket_single_filter_widget.berocket_hidden_clickable.berocket_single_filter_visible .berocket_aapf_widget-title_div {';
        echo $this->convert_styles_to_string($options['styles_input']['onlyTitle_titleopened']);
        echo '}';
        echo 'div.berocket_single_filter_widget.berocket_hidden_clickable .berocket_aapf_widget {';
        echo $this->convert_styles_to_string($options['styles_input']['onlyTitle_filter']);
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
    public function create_metadata_table() {
        $options     = $this->get_option();
        if( ! empty($options['use_select2']) ) {
            wp_register_style( 'select2', plugins_url( 'css/select2.min.css', __FILE__ ) );
            wp_register_style( 'br_select2', plugins_url( 'css/select2.fixed.css', __FILE__ ) );
            wp_register_script( 'select2', plugins_url( 'js/select2.min.js', __FILE__ ), array( 'jquery' ) );
        }
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
    public function wp_print_footer_scripts() {
        $br_options = $this->get_option();
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
    public function wp_print_special_scripts() {
        wp_enqueue_style( 'jquery-ui-datepick' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
    }
    public function selected_area() {
        $br_options = apply_filters( 'berocket_aapf_listener_br_options', $this->get_option() );
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
    public function br_aapf_get_child() {
        $br_options = apply_filters( 'berocket_aapf_listener_br_options', $this->get_option() );
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
    public function WPML_fix() {
        global $sitepress;
        if ( method_exists( $sitepress, 'switch_lang' )
             && isset( $_POST['current_language'] )
             && $_POST['current_language'] !== $sitepress->get_default_language()
        ) {
            $sitepress->switch_lang( $_POST['current_language'], true );
        }
    }
    public function loop_columns($per_row) {
        $options = $this->get_option();
        $per_row = ( ( empty($options['product_per_row']) || ! (int) $options['product_per_row'] || (int) $options['product_per_row'] < 1 ) ? $per_row : (int) $options['product_per_row'] );
        return $per_row;
    }
    public function order_by_popularity_post_clauses( $args ) {
        global $wpdb;
        $args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";
        return $args;
    }
    public function order_by_rating_post_clauses( $args ) {
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
    public function wp_footer_widget() {
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
    public function get_attribute_for_variation_link($product, $filters) {
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
    public function wcml_currency_price_fix() {
        if ( ! empty($_POST['price']) ) {
            global $woocommerce_wpml;
            if( ! empty($woocommerce_wpml) && is_object($woocommerce_wpml)
            && property_exists($woocommerce_wpml, 'multi_currency') && is_object($woocommerce_wpml->multi_currency)
            && property_exists($woocommerce_wpml->multi_currency, 'prices') && is_object($woocommerce_wpml->multi_currency->prices)
            && method_exists($woocommerce_wpml->multi_currency->prices, 'unconvert_price_amount') ) {
                $min = isset( $_POST['price'][0] ) ? floatval( $_POST['price'][0] ) : 0;
                $max = isset( $_POST['price'][1] ) ? floatval( $_POST['price'][1] ) : 9999999999;
                
                $min = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount($min);
                $max = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount($max);
                $_POST['price'][0] = $min;
                $_POST['price'][1] = $max;
            }
        }
    }
    public static function get_aapf_option() {
        $BeRocket_AAPF = self::getInstance();
        return $BeRocket_AAPF->get_option();
    }
    public function menu_order_custom_post($compatibility) {
        $compatibility['br_product_filter'] = 'br-product-filters';
        $compatibility['br_filters_group'] = 'br-product-filters';
        return $compatibility;
    }
    public function limits_filter($post_in) {
        $post_in = apply_filters('berocket_aapf_limits_filter_function', $post_in);
        return $post_in;
    }
}

new BeRocket_AAPF;
