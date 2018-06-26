<?php

class BeRocket_AAPF_new_filters {
    function __construct () {
        add_filter( 'init', array( $this, 'init' ) );
        add_filter( 'admin_init', array( $this, 'admin_init' ) );
        //CONDITIONS HTML
        add_filter('berocket_filters_condition_type_page', array( __CLASS__, 'condition_page'), 10, 3);
        add_filter('berocket_filters_condition_type_attribute', array( __CLASS__, 'condition_attribute'), 10, 3);
        add_filter('berocket_filters_condition_type_search', array( __CLASS__, 'condition_search'), 10, 3);
        add_filter('berocket_filters_condition_type_prod_count', array( __CLASS__, 'condition_prod_count'), 10, 3);
        add_filter('berocket_filters_condition_type_category', array( __CLASS__, 'condition_category'), 10, 3);
        //CONDITIONS CHECK
        add_filter('berocket_filters_condition_check_type_page', array( __CLASS__, 'check_condition_page'), 10, 4);
        add_filter('berocket_filters_condition_check_type_attribute', array( __CLASS__, 'check_condition_attribute'), 10, 4);
        add_filter('berocket_filters_condition_check_type_search', array( __CLASS__, 'check_condition_search'), 10, 4);
        add_filter('berocket_filters_condition_check_type_prod_count', array( __CLASS__, 'check_condition_prod_count'), 10, 4);
        add_filter('berocket_filters_condition_check_type_category', array( __CLASS__, 'check_condition_category'), 10, 4);
        //SHORTCODES
        add_shortcode( 'br_filters_group', array( $this, 'shortcode_group' ) );
        add_shortcode( 'br_filter_single', array( $this, 'shortcode' ) );
    }
    function init() {
        register_post_type( "br_product_filter",
            array(
                'labels' => array(
                    'name'               => __( 'Product Filter', 'BeRocket_AJAX_domain' ),
                    'singular_name'      => __( 'Product Filter', 'BeRocket_AJAX_domain' ),
                    'menu_name'          => _x( 'Product Filters', 'Admin menu name', 'BeRocket_AJAX_domain' ),
                    'add_new'            => __( 'Add Filter', 'BeRocket_AJAX_domain' ),
                    'add_new_item'       => __( 'Add New Filter', 'BeRocket_AJAX_domain' ),
                    'edit'               => __( 'Edit', 'BeRocket_AJAX_domain' ),
                    'edit_item'          => __( 'Edit Filter', 'BeRocket_AJAX_domain' ),
                    'new_item'           => __( 'New Filter', 'BeRocket_AJAX_domain' ),
                    'view'               => __( 'View Filters', 'BeRocket_AJAX_domain' ),
                    'view_item'          => __( 'View Filter', 'BeRocket_AJAX_domain' ),
                    'search_items'       => __( 'Search Product Filters', 'BeRocket_AJAX_domain' ),
                    'not_found'          => __( 'No Product Filters found', 'BeRocket_AJAX_domain' ),
                    'not_found_in_trash' => __( 'No Product Filters found in trash', 'BeRocket_AJAX_domain' ),
                ),
                'description'     => __( 'This is where you can add Product Filters.', 'BeRocket_AJAX_domain' ),
                'public'          => true,
                'show_ui'         => true,
                'capability_type' => 'post',
                'publicly_queryable'  => false,
                'exclude_from_search' => true,
                'show_in_menu'        => 'edit.php?post_type=product',
                'hierarchical'        => false,
                'rewrite'             => false,
                'query_var'           => false,
                'supports'            => array( 'title' ),
                'show_in_nav_menus'   => false,
            )
        );
        register_post_type( "br_filters_group",
            array(
                'labels' => array(
                    'name'               => __( 'Product Filter Group', 'BeRocket_AJAX_domain' ),
                    'singular_name'      => __( 'Product Filter Group', 'BeRocket_AJAX_domain' ),
                    'menu_name'          => _x( 'Product Filter Groups', 'Admin menu name', 'BeRocket_AJAX_domain' ),
                    'add_new'            => __( 'Add Filter Group', 'BeRocket_AJAX_domain' ),
                    'add_new_item'       => __( 'Add New Filter Group', 'BeRocket_AJAX_domain' ),
                    'edit'               => __( 'Edit', 'BeRocket_AJAX_domain' ),
                    'edit_item'          => __( 'Edit Filter Group', 'BeRocket_AJAX_domain' ),
                    'new_item'           => __( 'New Filter Group', 'BeRocket_AJAX_domain' ),
                    'view'               => __( 'View Filter Groups', 'BeRocket_AJAX_domain' ),
                    'view_item'          => __( 'View Filter Group', 'BeRocket_AJAX_domain' ),
                    'search_items'       => __( 'Search Product Filter Groups', 'BeRocket_AJAX_domain' ),
                    'not_found'          => __( 'No Product Filter Groups found', 'BeRocket_AJAX_domain' ),
                    'not_found_in_trash' => __( 'No Product Filter Groups found in trash', 'BeRocket_AJAX_domain' ),
                ),
                'description'     => __( 'This is where you can add Product Filter Groups.', 'BeRocket_AJAX_domain' ),
                'public'          => true,
                'show_ui'         => true,
                'capability_type' => 'post',
                'publicly_queryable'  => false,
                'exclude_from_search' => true,
                'show_in_menu'        => 'edit.php?post_type=product',
                'hierarchical'        => false,
                'rewrite'             => false,
                'query_var'           => false,
                'supports'            => array( 'title' ),
                'show_in_nav_menus'   => false,
            )
        );
    }
    public function admin_init() {
        add_filter( 'bulk_actions-edit-br_product_filter', array( $this, 'bulk_actions_edit' ) );
        add_filter( 'views_edit-br_product_filter', array( $this, 'views_edit' ) );
        add_filter( 'manage_edit-br_product_filter_columns', array( $this, 'manage_edit_columns' ) );
        add_action( 'manage_br_product_filter_posts_custom_column', array( $this, 'columns_replace' ), 2 );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'wc_save_product' ) );
        add_filter( 'bulk_actions-edit-br_filters_group', array( $this, 'bulk_actions_edit_group' ) );
        add_filter( 'views_edit-br_filters_group', array( $this, 'views_edit_group' ) );
        add_filter( 'manage_edit-br_filters_group_columns', array( $this, 'manage_edit_columns_group' ) );
        add_action( 'manage_br_filters_group_posts_custom_column', array( $this, 'columns_replace_group' ), 2 );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes_group' ) );
        add_action( 'save_post', array( $this, 'wc_save_product_group' ) );
        add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
        add_filter( 'list_table_primary_column', array( $this, 'list_table_primary_column' ), 10, 2 );

        add_action( "wp_ajax_aapf_generate_new_filter", array ( $this, 'generate_filter' ) );
    }

    public function post_row_actions($actions, $post) {
        if( $post->post_type == 'br_product_filter' || $post->post_type == 'br_filters_group' ) {
            if( isset($actions['inline hide-if-no-js']) ) {
                unset($actions['inline hide-if-no-js']);
            }
        }
        return $actions;
    }

    public function list_table_primary_column($default, $screen_id) {
        if( $screen_id == 'edit-br_product_filter' || $screen_id == 'edit-br_filters_group' ) {
            $default = 'name';
        }
        return $default;
    }

    public function generate_filter() {
        if ( current_user_can( 'manage_options' ) ) {
            $field_name = 'widget-'.$_POST['id_base'];
            $widget_number = ( ! empty($_POST['multi_number']) && ! empty($_POST[$field_name][$_POST['multi_number']]) ? $_POST['multi_number'] : $_POST['widget_number']);
            if( ! empty($_POST[$field_name][$widget_number]) ) {
                $filter = $_POST[$field_name][$widget_number];
                if( ! empty($filter['show_page']) && is_array($filter['show_page']) ) {
                    $filter['data'] = array();
                    $i = 1;
                    foreach($filter['show_page'] as $show_page) {
                        $filter['data'][$i] = array(
                            '1' => array(
                                'equal' => 'equal',
                                'type' => 'page',
                                'page' => $show_page,
                            ),
                        );
                        if( 'product_cat' == $show_page && ! empty($filter['product_cat']) && is_array($filter['product_cat']) ) {
                            $cat_propagation = array();
                            foreach($filter['product_cat'] as $product_cat) {
                                $product_cat = get_term_by('slug', $product_cat, 'product_cat');
                                if( is_object($product_cat) && property_exists($product_cat, 'term_id') ) {
                                    $cat_propagation[] = $product_cat->term_id;
                                }
                            }
                            $filter['data'][$i]['2'] = array(
                                'equal' => 'equal',
                                'type' => 'category',
                                'subcats' => ! empty($filter['cat_propagation']),
                                'category' => $cat_propagation,
                            );
                        }
                        $i++;
                    }
                }
                $post_id = wp_insert_post(array(
                    'post_title' => ( empty($filter['title']) ? '' : $filter['title']),
                    'post_type'  => 'br_product_filter'
                ));
                if( ! empty($post_id) ) {
                    update_post_meta( $post_id, 'BeRocket_product_new_filter', $filter );
                    echo admin_url('post.php?post='.$post_id.'&action=edit');
                } else {
                    echo 'error';
                }
            } else {
                echo 'error';
            }
            wp_die();
        }
    }
    public function shortcode($atts = array()) {
        ob_start();
        the_widget( 'BeRocket_new_AAPF_Widget_single', $atts);
        return ob_get_clean();
    }
    public function bulk_actions_edit ( $actions ) {
        unset( $actions['edit'] );
        return $actions;
    }
    public function views_edit ( $view ) {
        unset( $view['publish'], $view['private'], $view['future'] );
        return $view;
    }
    public function manage_edit_columns ( $columns ) {
        $columns = array();
        $columns["cb"]   = '<input type="checkbox" />';
        $columns["name"] = __( "Name", 'BeRocket_AJAX_domain' );
        $columns["data"] = __( "Data", 'BeRocket_AJAX_domain' );
        $columns["shortcode"] = __( "Shortcode", 'BeRocket_AJAX_domain' );
        return $columns;
    }
    public function columns_replace ( $column ) {
        global $post;
        $filter = get_post_meta( $post->ID, 'BeRocket_product_new_filter', true );
        switch ( $column ) {
            case "name":

                $edit_link = get_edit_post_link( $post->ID );
                $title = '<a class="row-title" href="' . $edit_link . '">' . _draft_or_post_title() . '</a>';

                echo 'ID:' . $post->ID . ' <strong>' . $title . '</strong>';
                
                break;
            case "data":
                $widget_types = array(
                    'filter'        => __('Filter', 'BeRocket_AJAX_domain'),
                    'update_button' => __('Update Products button', 'BeRocket_AJAX_domain'),
                    'reset_button'  => __('Reset Products button', 'BeRocket_AJAX_domain'),
                    'selected_area' => __('Selected Filters area', 'BeRocket_AJAX_domain'),
                    'search_box'    => __('Search Box', 'BeRocket_AJAX_domain')
                );
                echo __('Widget type: ', 'BeRocket_AJAX_domain') . '<strong>' . ( isset($widget_types[$filter['widget_type']]) ? $widget_types[$filter['widget_type']] : $filter['widget_type'] ) . '</strong>';
                echo '<br>';
                if( $filter['widget_type'] == 'search_box' ) {
                    $search_type = array(
                        'attribute' => __('Attribute', 'BeRocket_AJAX_domain'),
                        'tag' => __('Tag', 'BeRocket_AJAX_domain'),
                        'custom_taxonomy' => __('Custom Taxonomy', 'BeRocket_AJAX_domain'),
                    );
                    $i = 1;
                    foreach($filter['search_box_attributes'] as $search_box) {
                        echo $i . ') ';
                        if( $search_box['type'] == 'attribute' ) {
                            echo __('Attribute: ', 'BeRocket_AJAX_domain') . '<strong>' . $search_box['attribute'] . '</strong>';
                        } elseif( $search_box['type'] == 'custom_taxonomy' ) {
                            echo __('Custom Taxonomy: ', 'BeRocket_AJAX_domain') . '<strong>' . $search_box['custom_taxonomy'] . '</strong>';
                        } elseif( $search_box['type'] == 'tag' ) {
                            echo __('Tag', 'BeRocket_AJAX_domain');
                        }
                        echo '<br>';
                        $i++;
                    }
                } elseif( $filter['widget_type'] == 'filter' ) {
                    if( $filter['filter_type'] == 'attribute' ) {
                        if( $filter['attribute'] == 'price' ) {
                            $taxonomy_details_label = __('Price', 'BeRocket_AJAX_domain');
                        } else {
                            $taxonomy_details = get_taxonomy( $filter['attribute'] );
                            $taxonomy_details_label = $taxonomy_details->label;
                        }
                        echo __('Attribute: ', 'BeRocket_AJAX_domain') . '<strong>' . $taxonomy_details_label . '</strong>';
                    } elseif( $filter['filter_type'] == '_stock_status' ) {
                        echo __('Stock status', 'BeRocket_AJAX_domain');
                    } elseif( $filter['filter_type'] == 'product_cat' ) {
                        echo __('Product sub-categories', 'BeRocket_AJAX_domain');
                    } elseif( $filter['filter_type'] == 'tag' ) {
                        echo __('Tag', 'BeRocket_AJAX_domain');
                    } elseif( $filter['filter_type'] == 'custom_taxonomy' ) {
                        $taxonomy_details = get_taxonomy( $filter['custom_taxonomy'] );
                        if( ! empty($taxonomy_details) ) {
                            echo __('Custom Taxonomy: ', 'BeRocket_AJAX_domain') . '<strong>' . $taxonomy_details->label . '</strong>';
                        }
                    } elseif( $filter['filter_type'] == 'date' ) {
                        echo __('Date', 'BeRocket_AJAX_domain');
                    } elseif( $filter['filter_type'] == '_sale' ) {
                        echo __('Sale', 'BeRocket_AJAX_domain');
                    } elseif( $filter['filter_type'] == '_rating' ) {
                        echo __('Rating', 'BeRocket_AJAX_domain');
                    }
                }
                break;
            case "shortcode":
                echo "[br_filter_single filter_id={$post->ID}]";
                break;
        }
    }
    public  function add_meta_boxes () {
        add_meta_box( 'submitdiv', __( 'Save content', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box' ), 'br_product_filter', 'side', 'high' );
        add_meta_box( 'product_filter_conditions', __( 'Conditions', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_filter_conditions' ), 'br_product_filter', 'normal', 'high' );
        add_meta_box( 'product_filter_shortcode', __( 'Shortcode', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_shortcode' ), 'br_product_filter', 'side', 'high' );
        add_meta_box( 'product_filter_information', __( 'FAQ', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_information' ), 'br_product_filter', 'side', 'high' );
        add_meta_box( 'product_filters_setup', __( 'Product Filter settings', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_settings' ), 'br_product_filter', 'normal', 'high' );
    }
    public function meta_box_filter_conditions($post) {
        $filters_default = array(
            'data'                  => array()
        );
        $filters = get_post_meta( $post->ID, 'BeRocket_product_new_filter', true );
        if( ! is_array($filters) ) {
            $filters = array();
        }
        $filters = array_merge($filters_default, $filters);
        include AAPF_TEMPLATE_PATH . "filter_condition.php";
    }
    public function meta_box_shortcode($post) {
        global $pagenow;
        if( in_array( $pagenow, array( 'post-new.php' ) ) ) {
            _e( 'You need save it to get shortcode', 'BeRocket_AJAX_domain' );
        } else {
            echo "[br_filter_single filter_id={$post->ID}]";
        }
    }
    public function meta_box_information($post) {
        include AAPF_TEMPLATE_PATH . "filters_information.php";
    }
    public  function meta_box($post) {
        wp_enqueue_script( 'berocket_aapf_widget-colorpicker' );
        wp_enqueue_script( 'berocket_aapf_widget-admin' );
        wp_enqueue_style( 'brjsf-ui' );
        wp_enqueue_script( 'brjsf-ui' );
        wp_enqueue_script( 'berocket_framework_admin' );
        wp_enqueue_style( 'berocket_framework_admin_style' );
        wp_enqueue_script( 'berocket_widget-colorpicker' );
        wp_enqueue_style( 'berocket_widget-colorpicker-style' );
        wp_enqueue_style( 'font-awesome' );
        ?>
        <div class="submitbox" id="submitpost">

            <div id="minor-publishing">
                <div id="major-publishing-actions">
                    <div id="delete-action">
                        <?php
                        global $pagenow;
                        if( in_array( $pagenow, array( 'post-new.php' ) ) ) {
                        } else {
                            if ( current_user_can( "delete_post", $post->ID ) ) {
                                if ( ! EMPTY_TRASH_DAYS )
                                    $delete_text = __( 'Delete Permanently', 'BeRocket_AJAX_domain' );
                                else
                                    $delete_text = __( 'Move to Trash', 'BeRocket_AJAX_domain' );
                                ?>
                                <a class="submitdelete deletion" href="<?php echo esc_url( get_delete_post_link( $post->ID ) ); ?>"><?php echo esc_attr( $delete_text ); ?></a>
                            <?php 
                            }
                        } ?>
                    </div>

                    <div id="publishing-action">
                        <span class="spinner"></span>
                        <input type="submit" class="button button-primary tips" name="publish" value="<?php _e( 'Save', 'BeRocket_AJAX_domain' ); ?>" data-tip="<?php _e( 'Save/update notice', 'BeRocket_AJAX_domain' ); ?>" />
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <?php
    }
    public function meta_box_settings($post) {
        $instance = get_post_meta( $post->ID, 'BeRocket_product_new_filter', true );
        if( ! is_array($instance) ) {
            $instance = array();
        }
        $instance = array_merge(BeRocket_AAPF_Widget::$defaults, $instance);
        include AAPF_TEMPLATE_PATH . "filter_post.php";
    }
    public function wc_save_product( $product_id ) {
        $current_settings = get_post_meta( $product_id, 'BeRocket_product_new_filter', true );
        if( empty($current_settings) ) {
            update_post_meta( $product_id, 'BeRocket_product_new_filter', BeRocket_AAPF_Widget::$defaults );
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( isset( $_POST['BeRocket_product_new_filter'] ) ) {
            $instance = $_POST['BeRocket_product_new_filter'];
            update_post_meta( $product_id, 'BeRocket_product_new_filter', $instance );
            if( ( $instance['filter_type'] == 'attribute' or $instance['filter_type'] == 'custom_taxonomy' or $instance['filter_type'] == 'tag' or $instance['filter_type'] == 'product_cat' ) and ( $instance['type'] == 'color' or $instance['type'] == 'image' ) ) {
                $instance['use_value_with_color'] = ! empty($instance['use_value_with_color']);
                if( $instance['filter_type'] == 'tag' ) {
                    $_POST['tax_color_name']          = 'product_tag';
                } elseif( $instance['filter_type'] == 'product_cat' ) {
                    $_POST['tax_color_name']          = 'product_cat';
                } else {
                    $_POST['tax_color_name']          = $instance['attribute'];
                }
                $_POST['type']                    = $instance['type'];
                $_POST['tax_color_set']           = $_POST['br_widget_color'];
                BeRocket_AAPF_Widget::color_listener();
            }
        }
    }
    public function shortcode_group($atts = array()) {
        ob_start();
        the_widget( 'BeRocket_new_AAPF_Widget', $atts);
        return ob_get_clean();
    }
    public function bulk_actions_edit_group ( $actions ) {
        unset( $actions['edit'] );
        return $actions;
    }
    public function views_edit_group ( $view ) {
        unset( $view['publish'], $view['private'], $view['future'] );
        return $view;
    }
    public function manage_edit_columns_group ( $columns ) {
        $columns = array();
        $columns["cb"]   = '<input type="checkbox" />';
        $columns["name"] = __( "Group Name", 'BeRocket_AJAX_domain' );
        $columns["filters"] = __( "Filters", 'BeRocket_AJAX_domain' );
        $columns["shortcode"] = __( "Shortcode", 'BeRocket_AJAX_domain' );
        return $columns;
    }
    public function columns_replace_group ( $column ) {
        global $post;
        $filters_default = array(
            'filters' => array()
        );
        $filters = get_post_meta( $post->ID, 'br_filter_group', true );
        if( ! is_array($filters) ) {
            $filters = array();
        }
        $filters = array_merge($filters_default, $filters);
        switch ( $column ) {
            case "name":

                $edit_link = get_edit_post_link( $post->ID );
                $title = '<a class="row-title" href="' . $edit_link . '">' . _draft_or_post_title() . '</a>';

                echo 'ID:' . $post->ID . ' <strong>' . $title . '</strong>';

                break;
            case "filters":
                $filter_links = '';
                if( isset($filters['filters']) && is_array($filters['filters']) ) {
                    foreach($filters['filters'] as $filter) {
                        $filter_id = $filter;
                        $filter_post = get_post($filter_id);
                        if( ! empty($filter_post) ) {
                            if( ! empty($filter_links) ) {
                                $filter_links .= ', ';
                            }
                            $filter_links .= '<a class="berocket_edit_filter" target="_blank" href="' . admin_url('post.php?post='.$filter_id.'&action=edit') . '">' . $filter_post->post_title . '</a> ';
                        }
                    }
                }
                echo $filter_links;
                break;
            case "shortcode":
                echo "[br_filters_group group_id={$post->ID}]";
                break;
        }
    }
    public  function add_meta_boxes_group () {
        add_meta_box( 'submitdiv', __( 'Save content', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box' ), 'br_filters_group', 'side', 'high' );
        add_meta_box( 'product_filter_shortcode', __( 'Shortcode', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_shortcode_group' ), 'br_filters_group', 'side', 'high' );
        add_meta_box( 'product_filter_information', __( 'Information', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_information_group' ), 'br_filters_group', 'side', 'low' );
        add_meta_box( 'product_filters_consitions', __( 'Conditions', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_conditions' ), 'br_filters_group', 'normal', 'high' );
        add_meta_box( 'product_filters_setup', __( 'Group settings', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_settings_group' ), 'br_filters_group', 'normal', 'high' );
        add_meta_box( 'product_filters_search_box', __( 'Group Search Box', 'BeRocket_AJAX_domain' ), array( $this, 'meta_box_settings_search_box' ), 'br_filters_group', 'normal', 'low' );
    }
    public function meta_box_shortcode_group($post) {
        global $pagenow;
        if( in_array( $pagenow, array( 'post-new.php' ) ) ) {
            _e( 'You need save it to get shortcode', 'BeRocket_AJAX_domain' );
        } else {
            echo "[br_filters_group group_id={$post->ID}]";
        }
    }
    public function meta_box_information_group($post) {
        include AAPF_TEMPLATE_PATH . "groups_information.php";
    }
    public function meta_box_conditions($post) {
        $filters_default = array(
            'data'                  => array()
        );
        $filters = get_post_meta( $post->ID, 'br_filter_group', true );
        if( ! is_array($filters) ) {
            $filters = array();
        }
        $filters = array_merge($filters_default, $filters);
        include AAPF_TEMPLATE_PATH . "filters_condition.php";
    }
    public function meta_box_settings_group($post) {
        wp_enqueue_script('jquery-ui-sortable');
        $filters_default = array(
            'filters' => array(),
        );
        $filters = get_post_meta( $post->ID, 'br_filter_group', true );
        if( ! is_array($filters) ) {
            $filters = array();
        }
        $filters = array_merge($filters_default, $filters);
        include AAPF_TEMPLATE_PATH . "filters_group.php";
    }
    public function meta_box_settings_search_box($post) {
        $filters_default = array(
            'search_box_link_type'          => 'shop_page',
            'search_box_url'                => '',
            'search_box_category'           => '',
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
        );
        $filters = get_post_meta( $post->ID, 'br_filter_group', true );
        if( ! is_array($filters) ) {
            $filters = array();
        }
        $categories        = BeRocket_AAPF_Widget::get_product_categories( @ json_decode( $instance['product_cat'] ) );
        $categories        = BeRocket_AAPF_Widget::set_terms_on_same_level( $categories );
        $filters = array_merge($filters_default, $filters);
        include AAPF_TEMPLATE_PATH . "filters_search_box.php";
    }
    public function wc_save_product_group( $product_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( isset( $_POST['br_filter_group'] ) ) {
            update_post_meta( $product_id, 'br_filter_group', $_POST['br_filter_group'] );
            $above_products = ! empty($_POST['br_filter_group_show_above']);
            $options = BeRocket_AAPF::get_aapf_option();
            $elements_above_products = br_get_value_from_array($options, 'elements_above_products');
            if( ! is_array($elements_above_products) ) {
                $elements_above_products = array();
            }
            $search_i = array_search($product_id, $elements_above_products);
            if( $search_i !== FALSE && ! $above_products ) {
                unset($elements_above_products[$search_i]);
            } elseif( $search_i === FALSE && $above_products ) {
                $elements_above_products[] = $product_id;
            }
            $options['elements_above_products'] = $elements_above_products;
            update_option('br_filters_options', $options);
        }
    }
    public static function supcondition_equal($name, $options, $extension = array()) {
        $equal = 'equal';
        if( is_array($options) && isset($options['equal'] ) ) {
            $equal = $options['equal'];
        }
        $equal_list = array(
            'equal' => __('Equal', 'BeRocket_AJAX_domain'),
            'not_equal' => __('Not equal', 'BeRocket_AJAX_domain'),
        );
        if( ! empty($extension['equal_less']) ) {
            $equal_list['equal_less'] = __('Equal or less', 'BeRocket_AJAX_domain');
        }
        if( ! empty($extension['equal_more']) ) {
            $equal_list['equal_more'] = __('Equal or more', 'BeRocket_AJAX_domain');
        }
        $html = '<select name="' . $name . '[equal]">';
        foreach($equal_list as $equal_slug => $equal_name) {
            $html .= '<option value="' . $equal_slug . '"' . ($equal == $equal_slug ? ' selected' : '') . '>' . $equal_name . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public static function condition_page($html, $name, $options) {
        $def_options = array('page' => array());
        $options = array_merge($def_options, $options);
        $pages = get_pages();
        $html .= self::supcondition_equal($name, $options);
        $html .= '<select name="' . $name . '[page]">';
        $html .= '<option value="shop"' . ($options['page'] == 'shop' ? ' selected' : '') . '>' . __('shop', 'BeRocket_AJAX_domain') . '</option>';
        $html .= '<option value="product_cat"' . ($options['page'] == 'product_cat' ? ' selected' : '') . '>' . __('product category', 'BeRocket_AJAX_domain') . '</option>';
        $html .= '<option value="product_taxonomy"' . ($options['page'] == 'product_taxonomy' ? ' selected' : '') . '>' . __('product attributes', 'BeRocket_AJAX_domain') . '</option>';
        $html .= '<option value="product_tag"' . ($options['page'] == 'product_tag' ? ' selected' : '') . '>' . __('product tags', 'BeRocket_AJAX_domain') . '</option>';
        $html .= '<option value="single_product"' . ($options['page'] == 'single_product' ? ' selected' : '') . '>' . __('single product', 'BeRocket_AJAX_domain') . '</option>';
        foreach ( $pages as $page ) {
            $html .= '<option value="' . $page->ID . '"' . ($options['page'] == $page->ID ? ' selected' : '') . '>' . $page->post_title . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public static function check_condition_page($show_filters, $condition, $wp_query, $page) {
        $pageid = get_the_ID($page);
        $show_filters = ( 
            ($pageid == $condition['page']) ||
            ($condition['page'] == 'shop' && is_shop()) ||
            ($condition['page'] == 'product_cat' && is_product_category()) ||
            ($condition['page'] == 'product_taxonomy' && is_product_taxonomy() && ! is_product_category() && ! is_product_tag()) ||
            ($condition['page'] == 'product_tag' && is_product_tag()) ||
            ($condition['page'] == 'single_product' && is_product())
        );
        if( $condition['equal'] == 'not_equal' ) {
            $show_filters = ! $show_filters;
        }
        return $show_filters;
    }

    public static function condition_attribute($html, $name, $options) {
        $def_options = array('attribute' => '');
        $options = array_merge($def_options, $options);
        $attributes = get_object_taxonomies( 'product', 'objects');
        $product_attributes = array();
        foreach( $attributes as $attribute ) {
            $attribute_i = array();
            $attribute_i['name'] = $attribute->name;
            $attribute_i['label'] = $attribute->label;
            $attribute_i['value'] = array();
            $terms = get_terms(array(
                'taxonomy' => $attribute->name,
                'hide_empty' => false,
            ));
            foreach($terms as $term) {
                $attribute_i['value'][$term->term_id] = $term->name;
            }
            $product_attributes[] = $attribute_i;
        }
        $html .= self::supcondition_equal($name, $options);
        $html .= '<label>' . __('Select attribute', 'BeRocket_AJAX_domain') . '</label>';
        $html .= '<select name="' . $name . '[attribute]" class="br_cond_attr_select">';
        $has_selected_attr = false;
        foreach($product_attributes as $attribute) {
            $html .= '<option value="' . $attribute['name'] . '"' . ( isset($options['attribute']) && $attribute['name'] == $options['attribute'] ? ' selected' : '' ) . '>' . $attribute['label'] . '</option>';
            if( $attribute['name'] == $options['attribute'] ) {
                $has_selected_attr = true;
            }
        }
        $html .= '</select>';
        $is_first_attr = ! $has_selected_attr;
        foreach($product_attributes as $attribute) {
            $html .= '<select class="br_attr_values br_attr_value_' . $attribute['name'] . '" name="' . $name . '[values][' . $attribute['name'] . ']"' . ($is_first_attr || $attribute['name'] == $options['attribute'] ? '' : ' style="display:none;"') . '>';
            foreach($attribute['value'] as $term_id => $term_name) {
                $html .= '<option value="' . $term_id . '"' . (! empty($options['values'][$attribute['name']]) && $options['values'][$attribute['name']] == $term_id ? ' selected' : '') . '>' . $term_name . '</option>';
            }
            $html .= '</select>';
            $is_first_attr = false;
        }
        return $html;
    }

    public static function check_condition_attribute($show_filters, $condition, $wp_query, $page) {
        $show_filters = ( is_tax($condition['attribute'], $condition['values'][$condition['attribute']]) );
        if( $condition['equal'] == 'not_equal' ) {
            $show_filters = ! $show_filters;
        }
        return $show_filters;
    }

    public static function condition_search($html, $name, $options) {
        $def_options = array('search' => array());
        $options = array_merge($def_options, $options);
        $html .= self::supcondition_equal($name, $options);
        return $html;
    }

    public static function check_condition_search($show_filters, $condition, $wp_query, $page) {
        $show_filters = ( is_search() );
        if( $condition['equal'] == 'not_equal' ) {
            $show_filters = ! $show_filters;
        }
        return $show_filters;
    }

    public static function condition_category($html, $name, $options) {
        $product_categories = get_terms( 'product_cat' );
        if( is_array($product_categories) && count($product_categories) > 0 ) {
            $def_options = array('category' => '');
            $options = array_merge($def_options, $options);
            $html .= self::supcondition_equal($name, $options);
            $html .= '<label><input type="checkbox" name="' . $name . '[subcats]" value="1"' . (empty($options['subcats']) ? '' : ' checked') . '>' . __('Include subcategories', 'BeRocket_AJAX_domain') . '</label>';
            $html .= '<div style="max-height:70px;overflow:auto;border:1px solid #ccc;padding: 5px;">';
            foreach($product_categories as $category) {
                $html .= '<div><label>
                <input type="checkbox" name="' . $name . '[category][]" value="' . $category->term_id . '"' . ( (! empty($options['category']) && is_array($options['category']) && in_array($category->term_id, $options['category']) ) ? ' checked' : '' ) . '>
                ' . $category->name . '
                </label></div>';
            }
            $html .= '</div>';
        }
        return $html;
    }

    public static function check_condition_category($show_filters, $condition, $wp_query, $page) {
        global $wp_query;
        $show_filters = false;
        if( ! empty($condition['category']) && ! is_array($condition['category']) ) {
            $condition['category'] = array($condition['category']);
        }
        if( $wp_query->is_tax ) {
            $queried_object = $wp_query->get_queried_object();
            if(! empty($condition['category'])
            && is_array($condition['category'])
            && is_object($queried_object)
            && property_exists($queried_object, 'term_id')
            && property_exists($queried_object, 'taxonomy')
            && $queried_object->taxonomy == 'product_cat' ) {
                $show_filters = in_array($queried_object->term_id, $condition['category']);
                if( empty($show_filters) && ! empty($condition['subcats']) ) {
                    foreach($condition['category'] as $category) {
                        $show_filters = term_is_ancestor_of($category, $queried_object, 'product_cat');
                        if( $show_filters ) {
                            break;
                        }
                    }
                }
            }
        }
        if( $condition['equal'] == 'not_equal' ) {
            $show_filters = ! $show_filters;
        }
        return $show_filters;
    }
}
new BeRocket_AAPF_new_filters();
