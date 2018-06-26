<?php
class BeRocket_new_AAPF_Widget extends WP_Widget 
{
    public function __construct() {
        parent::__construct("berocket_aapf_group", "AAPF Filters Group",
            array("description" => "AJAX Product Filters Group"));
    }
    public function widget($args, $instance) {
        if( ! self::check_widget_by_instance($instance) ) {
            return false;
        }
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['group_id'] = apply_filters( 'wpml_object_id', $instance['group_id'], 'page', true, $current_language );
        $filters = get_post_meta( $instance['group_id'], 'br_filter_group', true );
        global $wp_registered_sidebars;
        $is_shortcode = empty($args['id']) || ! isset($wp_registered_sidebars[$args['id']]);
        $new_args = $args;
        if( ! $is_shortcode ) {
            $sidebar = $wp_registered_sidebars[$args['id']];
            $new_args = array_merge($new_args, $sidebar);
            $before_widget = $new_args['before_widget'];
        }
        $custom_class = trim(br_get_value_from_array($filters, 'custom_class'));
        $custom_class_instance = trim(br_get_value_from_array($instance, 'custom_class'));
        $custom_class = $custom_class . ' ' . $custom_class_instance;
        $new_args['custom_class'] = $custom_class;
        $i = 1;
        ob_start();
        if( ! empty($filters['search_box']) ) {
            $search_box_link_type = @ $filters['search_box_link_type'];
            $search_box_url = @ $filters['search_box_url'];
            $search_box_style = $filters['search_box_style'];
            $search_box_category = $filters['search_box_category'];
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
        }
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
            $style .= 'opacity:0!important;';
        }
        $new_args['inline_style'] = $style;
        $new_args['additional_class'] = $additional_class;
        foreach($filters['filters'] as $filter) {
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
            if( $is_shortcode ) {
                if( isset($new_args['before_widget']) ) {
                    unset($new_args['before_widget']);
                }
                if( isset($new_args['after_widget']) ) {
                    unset($new_args['after_widget']);
                }
            } else {
                $new_args['widget_id'] = $args['widget_id'].'-'.$i;
                $new_args['before_widget'] = sprintf($before_widget, $new_args['widget_id'], '%s');
            }
            if( ! empty($filters['search_box']) ) {
                echo '<div style="'.$sb_style.'">';
            }
            the_widget( 'BeRocket_new_AAPF_Widget_single', array('filter_id' => $filter), $new_args);
            if( ! empty($filters['search_box']) ) {
                echo '</div>';
            }
            $i++;
        }
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
        $widget_html = ob_get_clean();
        if( ! empty($widget_html) ) {
            echo $widget_html;
        } else {
            return false;
        }
    }
    public static function check_widget_by_instance($instance) {
        if( empty($instance['group_id']) ) {
            return false;
        }
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['group_id'] = apply_filters( 'wpml_object_id', $instance['group_id'], 'page', true, $current_language );
        $filters = get_post_meta( $instance['group_id'], 'br_filter_group', true );
        if( empty($filters) ) {
            return false;
        }
        if( ! empty($filters['data']) && ! self::check_group_on_page($filters['data']) ) {
            return false;
        }
        if( empty($filters['filters']) || ! is_array($filters['filters']) || ! count($filters['filters']) ) {
            return false;
        }
        return true;
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['group_id'] = strip_tags( @ $new_instance['group_id'] );
        $instance['title'] = strip_tags( @ $new_instance['title'] );
        $instance['custom_class'] = strip_tags( @ $new_instance['custom_class'] );
        if( ! empty($instance['group_id']) && empty($instance['title']) ) {
            $instance['title'] = get_the_title($instance['group_id']);
        }
        return $instance;
    }
    public function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'group_id' => '', 'title' => '', 'custom_class' => '') );
        echo '<a href="' . admin_url('edit.php?post_type=br_filters_group') . '">' . __('Manage groups', 'BeRocket_AJAX_domain') . '</a>';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('custom_class'); ?>"><?php _e('Custom CSS class', 'BeRocket_AJAX_domain'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('custom_class'); ?>" name="<?php echo $this->get_field_name('custom_class'); ?>" value="<?php echo $instance['custom_class']; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('group_id'); ?>"><?php _e('Group', 'BeRocket_AJAX_domain'); ?></label>
            <?php
            $query = new WP_Query(array('post_type' => 'br_filters_group', 'nopaging' => true));
            if ( $query->have_posts() ) {
                echo '<select id="'.$this->get_field_id('group_id').'" name="'.$this->get_field_name('group_id').'">';
                echo '<option>'.__('--Please select group--', 'BeRocket_AJAX_domain').'</option>';
                while ( $query->have_posts() ) {
                    if( empty($instance['group_id']) ) {
                        $instance['group_id'] = get_the_id();
                    }
                    $query->the_post();
                    echo '<option value="' . get_the_id() . '"'.(get_the_id() == $instance['group_id'] ? ' selected' : '').'>' . get_the_title() . ' (ID:' . get_the_id() . ')</option>';
                }
                echo '</select>';
                wp_reset_postdata();
            }
            ?>
        </p>
        <?php
    }
    public static function check_group_on_page($filters_data, $wp_query_in = false, $page_in = false) {
        if( $wp_query_in === false ) {
            global $wp_the_query;
            $wp_query_in = $wp_the_query;
        }
        if( $page_in === false ) {
            global $page;
            $page_in = $page;
        }
        $show_filters = false;
        foreach($filters_data as $filters) {
            $show_filters = false;
            foreach($filters as $condition) {
                $show_filters = apply_filters('berocket_filters_condition_check_type_' . $condition['type'], false, $condition, $wp_query_in, $page_in);
                if( !$show_filters ) {
                    break;
                }
            }
            if( $show_filters ) {
                break;
            }
        }
        return $show_filters;
    }
}
class BeRocket_new_AAPF_Widget_single extends WP_Widget 
{
    public function __construct() {
        parent::__construct("berocket_aapf_single", "AAPF Filter Single",
            array("description" => "AJAX Product Filters Single"));
    }
    public function widget($args, $instance) {
        if( ! self::check_widget_by_instance($instance) ) {
            return true;
        }
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['filter_id'] = apply_filters( 'wpml_object_id', $instance['filter_id'], 'page', true, $current_language );
        $filter_id = $instance['filter_id'];
        $filter_post = get_post($filter_id);
        $filter_data = get_post_meta( $filter_id, 'BeRocket_product_new_filter', true );
        if( empty($filter_data) || ! is_array($filter_data) ) {
            $filter_data = array();
        }
        if( ! empty($args['filter_data']) && is_array($args['filter_data']) ) {
            $filter_data = array_merge($filter_data, $args['filter_data']);
        }
        $custom_class = trim(br_get_value_from_array($filter_data, 'custom_class'));
        $custom_class_args = trim(br_get_value_from_array($args, 'custom_class'));
        $custom_class = $custom_class . ' ' . $custom_class_args;
        if ( empty($instance['br_wp_footer']) ) {
            global $br_widget_ids;
            if ( ! isset( $br_widget_ids ) ) {
                $br_widget_ids = array();
            }
            $instance['is_new_widget'] = true;
            $br_widget_ids[] = array('instance' => $instance, 'args' => $args);
        }
        $filter_data['br_wp_footer'] = true;
        $filter_data['show_page'] = array();
        $filter_data['title'] = $filter_post->post_title;
        $additional_class = br_get_value_from_array($args, 'additional_class');
        if( ! is_array($additional_class) ) {
            $additional_class = array();
        }
        if( ! empty($filter_data['is_hide_mobile']) ) {
            $additional_class[] = 'berocket_hide_single_widget_on_mobile';
        }
        if( ! empty($filter_data['hide_group']['tablet']) ) {
            $additional_class[] = 'berocket_hide_single_widget_on_tablet';
        }
        if( ! empty($filter_data['hide_group']['desktop']) ) {
            $additional_class[] = 'berocket_hide_single_widget_on_desktop';
        }
        $additional_class = array_unique($additional_class);
        if( ! empty($filter_data['widget_type']) && ($filter_data['widget_type'] == 'update_button' || $filter_data['widget_type'] == 'reset_button' ) ) {
            $search_berocket_hidden_clickable = array_search('berocket_hidden_clickable', $additional_class);
            if( $search_berocket_hidden_clickable !== FALSE ) {
                unset($additional_class[$search_berocket_hidden_clickable]);
            }
            echo '<div class="berocket_single_filter_widget berocket_single_filter_widget_' . $instance['filter_id'] . ' ' . $custom_class . implode(' ', $additional_class) . '" data-id="' . $instance['filter_id'] . '">';
        } else {
            if( ! empty($args['widget_inline_style']) ) {
                $classes_arr = 'berocket_single_filter_widget berocket_single_filter_widget_' . $instance['filter_id'] . ' ' . $custom_class . ' '.implode(' ', $additional_class);
                $classes_arr = explode(' ', preg_replace('!\s+!', ' ', $classes_arr));
                $classes_arr = '.' . implode('.', $classes_arr);
                echo '<style>'.$classes_arr.' .berocket_aapf_widget {' . $args['widget_inline_style'] . '}</style>';
            }
            echo '<div class="berocket_single_filter_widget berocket_single_filter_widget_' . $instance['filter_id'] . ' ' . $custom_class . ' '.implode(' ', $additional_class).'" data-id="' . $instance['filter_id'] . '" style="'.br_get_value_from_array($args, 'inline_style').'">';
        }
        the_widget( 'BeRocket_AAPF_widget', $filter_data, $args);
        echo '</div>';
    }
    public static function check_widget_by_instance($instance) {
        if( empty($instance['filter_id']) ) {
            return false;
        }
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['filter_id'] = apply_filters( 'wpml_object_id', $instance['filter_id'], 'page', true, $current_language );
        $filter_id = $instance['filter_id'];
        $filter_post = get_post($filter_id);
        $filter_data = get_post_meta( $filter_id, 'BeRocket_product_new_filter', true );
        if( ! empty($filter_data['data']) && ! self::check_group_on_page($filter_data['data']) ) {
            return false;
        }
        if( empty($filter_data) || empty($filter_post) ) {
            return false;
        }
        return true;
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['filter_id'] = strip_tags( @ $new_instance['filter_id'] );
        $instance['custom_class'] = strip_tags( @ $new_instance['custom_class'] );
        return $instance;
    }
    public function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'filter_id' => '', 'custom_class' => '') );
        echo '<a href="' . admin_url('edit.php?post_type=br_product_filter') . '">' . __('Manage filters', 'BeRocket_AJAX_domain') . '</a>';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('custom_class'); ?>"><?php _e('Custom CSS class', 'BeRocket_AJAX_domain'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('custom_class'); ?>" name="<?php echo $this->get_field_name('custom_class'); ?>" value="<?php echo $instance['custom_class']; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('filter_id'); ?>"><?php _e('Filter', 'BeRocket_AJAX_domain'); ?></label>
            <?php
            $query = new WP_Query(array('post_type' => 'br_product_filter', 'nopaging' => true));
            if ( $query->have_posts() ) {
                echo '<select id="'.$this->get_field_id('filter_id').'" name="'.$this->get_field_name('filter_id').'">';
                echo '<option>'.__('--Please select filter--', 'BeRocket_AJAX_domain').'</option>';
                while ( $query->have_posts() ) {
                    if( empty($instance['filter_id']) ) {
                        $instance['filter_id'] = get_the_id();
                    }
                    $query->the_post();
                    echo '<option value="' . get_the_id() . '"'.(get_the_id() == $instance['filter_id'] ? ' selected' : '').'>' . get_the_title() . ' (ID:' . get_the_id() . ')</option>';
                }
                echo '</select>';
                wp_reset_postdata();
            }
            ?>
        </p>
        <?php
    }
    public static function check_group_on_page($filters_data, $wp_query_in = false, $page_in = false) {
        if( $wp_query_in === false ) {
            global $wp_the_query;
            $wp_query_in = $wp_the_query;
        }
        if( $page_in === false ) {
            global $page;
            $page_in = $page;
        }
        $show_filters = false;
        foreach($filters_data as $filters) {
            $show_filters = false;
            foreach($filters as $condition) {
                $show_filters = apply_filters('berocket_filters_condition_check_type_' . $condition['type'], false, $condition, $wp_query_in, $page_in);
                if( !$show_filters ) {
                    break;
                }
            }
            if( $show_filters ) {
                break;
            }
        }
        return $show_filters;
    }
}
?>
