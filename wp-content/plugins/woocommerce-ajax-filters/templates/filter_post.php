<?php
$attributes        = br_aapf_get_attributes();
$categories        = BeRocket_AAPF_Widget::get_product_categories( '' );
$categories        = BeRocket_AAPF_Widget::set_terms_on_same_level( $categories );
$tags              = get_terms( 'product_tag' );
$custom_taxonomies = get_taxonomies( array( "_builtin" => false, "public" => true ) );
?>
<div class="widget-liquid-right tab-item  current">
<div>
    <label class="br_admin_center"><?php _e('Widget Type', 'BeRocket_AJAX_domain') ?></label>
    <select id="<?php echo 'widget_type'; ?>" name="<?php echo 'BeRocket_product_new_filter[widget_type]'; ?>" class="berocket_aapf_widget_admin_widget_type_select br_select_menu_left">
        <option <?php if ( $instance['widget_type'] == 'filter' or ! $instance['widget_type'] ) echo 'selected'; ?> value="filter"><?php _e('Filter', 'BeRocket_AJAX_domain') ?></option>
        <option <?php if ( $instance['widget_type'] == 'update_button' ) echo 'selected'; ?> value="update_button"><?php _e('Update Products button', 'BeRocket_AJAX_domain') ?></option>
        <option <?php if ( $instance['widget_type'] == 'reset_button' ) echo 'selected'; ?> value="reset_button"><?php _e('Reset Products button', 'BeRocket_AJAX_domain') ?></option>
        <option <?php if ( $instance['widget_type'] == 'selected_area' ) echo 'selected'; ?> value="selected_area"><?php _e('Selected Filters area', 'BeRocket_AJAX_domain') ?></option>
        <option <?php if ( $instance['widget_type'] == 'search_box' ) echo 'selected'; ?> value="search_box"><?php _e('Search Box', 'BeRocket_AJAX_domain') ?></option>
    </select>
</div>
<?php if( empty($instance['filter_type']) ) $instance['filter_type'] = ''; ?>
<div class="berocket_aapf_admin_filter_widget_content" <?php if ( $instance['widget_type'] == 'update_button' or $instance['widget_type'] == 'reset_button' or $instance['widget_type'] == 'selected_area' or $instance['widget_type'] == 'search_box'  ) echo 'style="display: none;"'; ?>>
    <div class="br_admin_half_size_left">
        <label class="br_admin_center"><?php _e('Filter By', 'BeRocket_AJAX_domain') ?></label>
        <select id="<?php echo 'filter_type'; ?>" name="<?php echo 'BeRocket_product_new_filter[filter_type]'; ?>" class="berocket_aapf_widget_admin_filter_type_select br_select_menu_left">
            <?php
            $filter_type_array = array(
                'attribute' => array(
                    'name' => __('Attribute', 'BeRocket_AJAX_domain'),
                    'sameas' => 'attribute',
                ),
                '_stock_status' => array(
                    'name' => __('Stock status', 'BeRocket_AJAX_domain'),
                    'sameas' => '_stock_status',
                ),
                'product_cat' => array(
                    'name' => __('Product sub-categories', 'BeRocket_AJAX_domain'),
                    'sameas' => 'product_cat',
                ),
                'tag' => array(
                    'name' => __('Tag', 'BeRocket_AJAX_domain'),
                    'sameas' => 'tag',
                ),
                'custom_taxonomy' => array(
                    'name' => __('Custom Taxonomy', 'BeRocket_AJAX_domain'),
                    'sameas' => 'custom_taxonomy',
                ),
                'date' => array(
                    'name' => __('Date', 'BeRocket_AJAX_domain'),
                    'sameas' => 'date',
                ),
                '_sale' => array(
                    'name' => __('Sale', 'BeRocket_AJAX_domain'),
                    'sameas' => '_sale',
                ),
            );
            if ( function_exists('wc_get_product_visibility_term_ids') ) {
                $filter_type_array['_rating'] = array(
                    'name' => __('Rating', 'BeRocket_AJAX_domain'),
                    'sameas' => '_rating',
                );
            }
            $filter_type_array = apply_filters('berocket_filter_filter_type_array', $filter_type_array, $instance);
            foreach($filter_type_array as $filter_type_key => $filter_type_val) {
                echo '<option';
                foreach($filter_type_val as $data_key => $data_val) {
                    if( ! empty($data_val) ) {
                        echo ' data-'.$data_key.'="'.$data_val.'"';
                    }
                }
                echo ' value="'.$filter_type_key.'"'.($instance['filter_type'] == $filter_type_key ? ' selected' : '').'>'.$filter_type_val['name'].'</option>';
                if( $instance['filter_type'] == $filter_type_key ) {
                    $sameas = $filter_type_val;
                }
            }
            ?>
        </select>
    </div>
    <div class="br_admin_half_size_right berocket_aapf_widget_admin_filter_type_ berocket_aapf_widget_admin_filter_type_attribute" <?php if ( $instance['filter_type'] and $instance['filter_type'] != 'attribute') echo 'style="display: none;"'; ?>>
        <label class="br_admin_center"><?php _e('Attribute', 'BeRocket_AJAX_domain') ?></label>
        <select id="<?php echo 'attribute'; ?>" name="<?php echo 'BeRocket_product_new_filter[attribute]'; ?>" class="berocket_aapf_widget_admin_filter_type_attribute_select br_select_menu_right">
            <option <?php if ( $instance['attribute'] == 'price' ) echo 'selected'; ?> value="price"><?php _e('Price', 'BeRocket_AJAX_domain') ?></option>
            <?php foreach ( $attributes as $k => $v ) { ?>
                <option <?php if ( $instance['attribute'] == $k ) echo 'selected'; ?> value="<?php echo $k ?>"><?php echo $v ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="br_admin_half_size_right berocket_aapf_widget_admin_filter_type_ berocket_aapf_widget_admin_filter_type_custom_taxonomy" <?php if ( $instance['filter_type'] != 'custom_taxonomy') echo 'style="display: none;"'; ?>>
        <label class="br_admin_center"><?php _e('Custom Taxonomies', 'BeRocket_AJAX_domain') ?></label>
        <select id="<?php echo 'custom_taxonomy'; ?>" name="<?php echo 'BeRocket_product_new_filter[custom_taxonomy]'; ?>" class="berocket_aapf_widget_admin_filter_type_custom_taxonomy_select br_select_menu_right">
            <?php foreach( $custom_taxonomies as $k => $v ){ ?>
                <option <?php if ( $instance['custom_taxonomy'] == $k ) echo 'selected'; ?> value="<?php echo $k ?>"><?php echo $v ?></option>
            <?php } ?>
        </select>
    </div>
    <?php
    if( ! empty($sameas) ) {
        $instance['filter_type'] = $sameas['sameas'];
        if( ! empty($sameas['attribute']) ) {
            if( $sameas['sameas'] == 'custom_taxonomy' ) {
                $instance['custom_taxonomy'] = $sameas['attribute'];
            } elseif( $sameas['sameas'] == 'attribute' ) {
                $instance['attribute'] = $sameas['attribute'];
            }
        }
    }
    ?>
    <div class="br_clearfix"></div>
    <div class="br_admin_three_size_left br_type_select_block"<?php if( $instance['filter_type'] == 'date' ) echo 'style="display: none;"'; ?>>
        <label class="br_admin_center"><?php _e('Type', 'BeRocket_AJAX_domain') ?></label>
        <select id="<?php echo 'type'; ?>" name="<?php echo 'BeRocket_product_new_filter[type]'; ?>" class="berocket_aapf_widget_admin_type_select br_select_menu_left">
            <?php if ( $instance['filter_type'] and $instance['filter_type'] != 'attribute' or $instance['attribute'] != 'price' ) { ?>
                <option <?php if ( $instance['type'] == 'checkbox' ) echo 'selected'; ?> value="checkbox">Checkbox</option>
                <option <?php if ( $instance['type'] == 'radio' ) echo 'selected'; ?> value="radio">Radio</option>
                <option <?php if ( $instance['type'] == 'select' ) echo 'selected'; ?> value="select">Select</option>
                <?php if ( $instance['filter_type'] != '_stock_status' && $instance['filter_type'] != '_sale' && $instance['filter_type'] != '_rating' ) { ?>
                    <option <?php if ( $instance['type'] == 'color' ) echo 'selected'; ?> value="color">Color</option>
                    <option <?php if ( $instance['type'] == 'image' ) echo 'selected'; ?> value="image">Image</option>
                <?php } ?>
            <?php } ?>
            <?php if ( $instance['filter_type'] and $instance['filter_type'] != 'tag' and $instance['filter_type'] != '_stock_status' and $instance['filter_type'] != '_sale' and $instance['filter_type'] != '_rating' and $instance['filter_type'] != 'product_cat' and ( $instance['filter_type'] != 'custom_taxonomy' or ( $instance['custom_taxonomy'] != 'product_tag' and $instance['custom_taxonomy'] != 'product_cat' ) ) ) {?>
                <option <?php if ( $instance['type'] == 'slider') echo 'selected'; ?> value="slider">Slider</option>
            <?php }
            if ( $instance['filter_type'] and $instance['filter_type'] == 'attribute' and $instance['attribute'] == 'price' ) {?>
                <option <?php if ( $instance['type'] == 'ranges') echo 'selected'; ?> value="ranges">Ranges</option>
            <?php }
            if ( $instance['filter_type'] and $instance['filter_type'] == 'tag' ) { ?>
                <option <?php if ( $instance['type'] == 'tag_cloud' ) echo 'selected'; ?> value="tag_cloud">Tag cloud</option>
            <?php } ?>
        </select>
    </div>
    <div class="br_admin_three_size_left" <?php if ( ( ! $instance['filter_type'] or $instance['filter_type'] == 'attribute' ) and  $instance['attribute'] == 'price' or $instance['type'] == 'slider' or $instance['filter_type'] == 'date' or $instance['filter_type'] == '_sale' or $instance['filter_type'] == '_rating' ) echo " style='display: none;'"; ?> >
        <label class="br_admin_center"><?php _e('Operator', 'BeRocket_AJAX_domain') ?></label>
        <select id="<?php echo 'operator'; ?>" name="<?php echo 'BeRocket_product_new_filter[operator]'; ?>" class="berocket_aapf_widget_admin_operator_select br_select_menu_left">
            <option <?php if ( $instance['operator'] == 'AND' ) echo 'selected'; ?> value="AND">AND</option>
            <option <?php if ( $instance['operator'] == 'OR' ) echo 'selected'; ?> value="OR">OR</option>
        </select>
    </div>
    <div class="berocket_aapf_order_values_by br_admin_three_size_left" <?php if ( ! $instance['filter_type'] or $instance['filter_type'] == 'date' or $instance['filter_type'] == '_sale' or $instance['filter_type'] == '_rating' or $instance['filter_type'] == '_stock_status' or ( $instance['filter_type'] == 'attribute' and $instance['type'] == 'slider' )) echo 'style="display: none;"'; ?>>
        <label class="br_admin_center"><?php _e('Values Order', 'BeRocket_AJAX_domain') ?></label>
        <select id="<?php echo 'order_values_by'; ?>" name="<?php echo 'BeRocket_product_new_filter[order_values_by]'; ?>" class="berocket_aapf_order_values_by_select br_select_menu_left">
            <option value=""><?php _e('Default', 'BeRocket_AJAX_domain') ?></option>
            <?php foreach ( array( 'Alpha' => __('Alpha', 'BeRocket_AJAX_domain'), 'Numeric' => __('Numeric', 'BeRocket_AJAX_domain') ) as $v_i => $v ) { ?>
                <option <?php if ( $instance['order_values_by'] == $v_i ) echo 'selected'; ?> value="<?php echo $v_i ?>"><?php echo $v; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="berocket_aapf_order_values_type br_admin_three_size_left" <?php if ( (( $instance['filter_type'] != 'attribute' && $instance['filter_type'] != 'custom_taxonomy') || $instance['type'] == 'slider') && $instance['filter_type'] != '_rating' && $instance['filter_type'] != 'tag' ) echo 'style="display: none;"'; ?>>
        <label class="br_admin_center"><?php _e('Order Type', 'BeRocket_AJAX_domain') ?></label>
        <select id="<?php echo 'order_values_type'; ?>" name="<?php echo 'BeRocket_product_new_filter[order_values_type]'; ?>" class="berocket_aapf_order_values_type_select br_select_menu_left">
            <?php foreach ( array( 'asc' => __( 'Ascending', 'BeRocket_AJAX_domain' ), 'desc' => __( 'Descending', 'BeRocket_AJAX_domain' ) ) as $v_i => $v ) { ?>
                <option <?php if ( $instance['order_values_type'] == $v_i ) echo 'selected'; ?> value="<?php echo $v_i; ?>"><?php echo $v; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="br_clearfix"></div>
    <div class="berocket_widget_color_pick">
        <?php if ( $instance['type'] == 'color' || $instance['type'] == 'image' ) {
            if ( $instance['filter_type'] == 'attribute' ) {
                $attribute_color_view = $instance['attribute'];
            } elseif ( $instance['filter_type'] == 'product_cat' ) {
                $attribute_color_view = 'product_cat';
            } elseif ( $instance['filter_type'] == 'tag' ) {
                $attribute_color_view = 'product_tag';
            } elseif ( $instance['filter_type'] == 'custom_taxonomy' ) {
                $attribute_color_view = $instance['custom_taxonomy'];
            }
            BeRocket_AAPF_Widget::color_list_view( $instance['type'], $attribute_color_view, true );
        } ?>
    </div>
    <div class="berocket_ranges_block"<?php if ( ! $instance['filter_type'] or $instance['filter_type'] != 'attribute' or $instance['attribute'] != 'price' or $instance['type'] != 'ranges' ) echo ' style="display: none;"'; ?>>
    <?php 
        if ( isset( $instance['ranges'] ) && is_array( $instance['ranges'] ) && count( $instance['ranges'] ) > 0 ) {
            foreach ( $instance['ranges'] as $range ) {
                ?><div class="berocket_ranges">
                    <input type="number" min="1" id="<?php echo 'ranges'; ?>" name="<?php echo 'BeRocket_product_new_filter[ranges]'; ?>[]" value="<?php echo $range; ?>">
                    <a href="#remove" class="berocket_remove_ranges"><i class="fa fa-times"></i></a>
                </div><?php
            }
        } else {
            ?><div class="berocket_ranges">
                <input type="number" min="1" id="<?php echo 'ranges'; ?>" name="<?php echo 'BeRocket_product_new_filter[ranges]'; ?>[]" value="1">
                <a href="#remove" class="berocket_remove_ranges"><i class="fa fa-times"></i></a>
            </div>
            <div class="berocket_ranges">
                <input type="number" min="1" id="<?php echo 'ranges'; ?>" name="<?php echo 'BeRocket_product_new_filter[ranges]'; ?>[]" value="50">
                <a href="#remove" class="berocket_remove_ranges"><i class="fa fa-times"></i></a>
            </div> <?php
        }
        ?><div><a href="#add" class="berocket_add_ranges" data-html='<div class="berocket_ranges"><input type="number" min="1" id="<?php echo 'ranges'; ?>" name="<?php echo 'BeRocket_product_new_filter[ranges]'; ?>[]" value="1"><a href="#remove" class="berocket_remove_ranges"><i class="fa fa-times"></i></a></div>'><i class="fa fa-plus"></i></a></div>
        <label>
            <input type="checkbox" name="<?php echo 'BeRocket_product_new_filter[hide_first_last_ranges]'; ?>" <?php if ( $instance['hide_first_last_ranges'] ) echo 'checked'; ?> value="1" />
            <?php _e('Hide first and last ranges without products', 'BeRocket_AJAX_domain') ?>
        </label>
    </div>
    <div <?php if ( $instance['filter_type'] != 'attribute' || $instance['attribute'] != 'price' ) echo " style='display: none;'"; ?> class="berocket_aapf_widget_admin_price_attribute" >
        <label class="br_admin_center" for="<?php echo 'text_before_price'; ?>"><?php _e('Text before price:', 'BeRocket_AJAX_domain') ?> </label>
        <input class="br_admin_full_size"  id="<?php echo 'text_before_price'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[text_before_price]'; ?>" value="<?php echo $instance['text_before_price']; ?>"/>
        <label class="br_admin_center" for="<?php echo 'text_after_price'; ?>"><?php _e('after:', 'BeRocket_AJAX_domain') ?> </label>
        <input class="br_admin_full_size"  id="<?php echo 'text_after_price'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[text_after_price]'; ?>" value="<?php echo $instance['text_after_price']; ?>" /><br>
        <span>%cur_symbol% will be replaced with currency symbol($), %cur_slug% will be replaced with currency code(USD)</span><br>
        <input  id="<?php echo 'enable_slider_inputs'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[enable_slider_inputs]'; ?>" value="1"<?php if( ! empty($instance['enable_slider_inputs']) ) echo ' checked'; ?>/>
        <label for="<?php echo 'enable_slider_inputs'; ?>"><?php _e('Enable Slider Inputs', 'BeRocket_AJAX_domain') ?> </label>
    </div>
    <div <?php if ( $instance['filter_type'] != 'attribute' || $instance['attribute'] != 'price' ) echo " style='display: none;'"; ?> class="berocket_aapf_widget_admin_price_attribute" >
        <label for="<?php echo 'price_values'; ?>"><?php _e('Use custom values(comma separated):', 'BeRocket_AJAX_domain') ?> </label>
        <input class="br_admin_full_size" id="<?php echo 'price_values'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[price_values]'; ?>" value="<?php echo br_get_value_from_array($instance, 'price_values'); ?>"/>
        <small><?php _e('* use numeric values only, strings will not work as expected', 'BeRocket_AJAX_domain') ?></small>
    </div>
    <div class="br_clearfix"></div>
    <div class="berocket_aapf_product_sub_cat_current" <?php if( $instance['filter_type'] != 'product_cat' ) echo 'style="display:none;"'; ?>>
        <div>
            <label>
                <input class="berocket_aapf_product_sub_cat_current_input" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[parent_product_cat_current]'; ?>" <?php if ( $instance['parent_product_cat_current'] ) echo 'checked'; ?> value="1" />
                <?php _e('Use current product category to get child', 'BeRocket_AJAX_domain') ?>
            </label>
        </div>
        <div>
            <label for="<?php echo 'depth_count'; ?>"><?php _e('Deep level:', 'BeRocket_AJAX_domain') ?></label>
            <input id="<?php echo 'depth_count'; ?>" type="number" min=0 name="<?php echo 'BeRocket_product_new_filter[depth_count]'; ?>" value="<?php echo $instance['depth_count']; ?>" />
        </div>
    </div>
    <div class="berocket_aapf_product_sub_cat_div" <?php if( $instance['filter_type'] != 'product_cat' || $instance['parent_product_cat_current'] ) echo 'style="display:none;"'; ?>>
            <label><?php _e('Product Category:', 'BeRocket_AJAX_domain') ?></label>
            <ul class="berocket_aapf_advanced_settings_categories_list">
                    <li>
                        <?php 
                        echo '<input type="radio" name="' . ( 'BeRocket_product_new_filter[parent_product_cat]' ) . '" ' .
                             ( empty($instance['parent_product_cat']) ? 'checked' : '' ) . ' value="" ' .
                             'class="berocket_aapf_widget_admin_height_input" />';
                        ?>
                        <?php _e('None', 'BeRocket_AJAX_domain') ?>
                    </li>
            <?php
            $selected_category = false;
            foreach ( $categories as $category ) {
                if ( (int) $instance['parent_product_cat'] == (int) $category->term_id ) {
                    $selected_category = true;
                }
                echo '<li>';
                if ( (int) $category->depth ) {
                    for ( $depth_i = 0; $depth_i < $category->depth; $depth_i ++ ) {
                        echo "&nbsp;&nbsp;&nbsp;";
                    }
                }
                echo '<input type="radio" name="' . ( 'BeRocket_product_new_filter[parent_product_cat]' ) . '" ' .
                     ( ( $selected_category ) ? 'checked' : '' ) . ' value="' . ( $category->term_id ).'" ' .
                     'class="berocket_aapf_widget_admin_height_input" />' . ( $category->name );
                echo '</li>';
                $selected_category = false;
            }
            ?>
            </ul>
    </div>
    <div class="berocket_options_for_select"<?php if( ( $instance['filter_type'] != 'tag' and $instance['filter_type'] != 'custom_taxonomy' and $instance['filter_type'] != 'attribute' ) or $instance['type'] != 'select' ) echo ' style="display:none;"'; ?>>
        <div>
            <label for="<?php echo 'select_first_element_text'; ?>"><?php _e('First Element Text', 'BeRocket_AJAX_domain') ?> </label>
            <input placeholder="<?php _e('Any', 'BeRocket_AJAX_domain'); ?>" id="<?php echo 'select_first_element_text'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[select_first_element_text]'; ?>" value="<?php echo $instance['select_first_element_text']; ?>" />
        </div>
        <div>
            <label>
                <input type="checkbox" name="BeRocket_product_new_filter[select_multiple]" <?php if ( ! empty($instance['select_multiple']) ) echo 'checked'; ?> value="1" />
                <?php _e('Multiple select', 'BeRocket_AJAX_domain') ?>
            </label>
        </div>
    </div>
    <br />
    <div class="br_clearfix"></div>
        <h3><?php _e('Advanced Settings', 'BeRocket_AJAX_domain') ?></h3>
        <div>
            <div class="berocket_attributes_checkbox_radio_data"<?php if( ( $instance['filter_type'] != 'custom_taxonomy' and $instance['filter_type'] != 'attribute' ) or ( $instance['type'] != 'checkbox' and $instance['type'] != 'radio' and $instance['type'] != 'color' and $instance['type'] != 'image' )) echo ' style="display:none;"'; ?>>
                <label for="<?php echo 'attribute_count'; ?>"><?php _e('Attribute Values count', 'BeRocket_AJAX_domain') ?></label>
                <input id="<?php echo 'attribute_count'; ?>" type="number" name="<?php echo 'BeRocket_product_new_filter[attribute_count]'; ?>" placeholder="<?php _e('From settings', 'BeRocket_AJAX_domain') ?>" value="<?php echo $instance['attribute_count']; ?>" />
                <div>
                    <?php _e('Show/Hide button', 'BeRocket_AJAX_domain') ?>
                    <select name="BeRocket_product_new_filter[attribute_count_show_hide]">
                        <option value=""><?php _e('Default', 'BeRocket_AJAX_domain') ?></option>
                        <option value="visible"<?php if(br_get_value_from_array($instance, 'attribute_count_show_hide') == 'visible') echo ' selected'; ?>><?php _e('Always visible', 'BeRocket_AJAX_domain') ?></option>
                        <option value="hidden"<?php if(br_get_value_from_array($instance, 'attribute_count_show_hide') == 'hidden') echo ' selected'; ?>><?php _e('Always hidden', 'BeRocket_AJAX_domain') ?></option>
                    </select>
                </div>
            </div>
            <div class="berocket_attributes_slider_data"<?php if( ( $instance['filter_type'] != 'custom_taxonomy' and $instance['filter_type'] != 'attribute' ) or $instance['type'] != 'slider' or ( $instance['filter_type'] == 'attribute' && $instance['attribute'] == 'price' )) echo ' style="display:none;"'; ?>>
                <input id="<?php echo 'slider_default'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[slider_default]'; ?>" <?php if ( $instance['slider_default'] ) echo 'checked'; ?> value="1" />
                <label for="<?php echo 'slider_default'; ?>"><?php _e('Use default values for slider', 'BeRocket_AJAX_domain') ?></label>
            </div>
            <div class="berocket_attributes_number_style_data"<?php if( ( $instance['filter_type'] != 'custom_taxonomy' and $instance['filter_type'] != 'attribute' ) or $instance['type'] != 'slider') echo ' style="display:none;"'; ?>>
                <div>
                    <input class="berocket_attributes_number_style" id="<?php echo 'number_style'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[number_style]'; ?>" <?php if ( $instance['number_style'] ) echo 'checked'; ?> value="1" />
                    <label for="<?php echo 'number_style'; ?>"><?php _e('Use specific number style', 'BeRocket_AJAX_domain') ?></label>
                </div>
                <div class="berocket_attributes_number_styles"<?php if( empty($instance['number_style']) ) echo ' style="display:none;"'; ?>>
                    <div>
                        <label for="<?php echo 'number_style_thousand_separate'; ?>"><?php _e('Thousand Separator', 'BeRocket_AJAX_domain') ?></label>
                        <input id="<?php echo 'number_style_thousand_separate'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[number_style_thousand_separate]'; ?>" value="<?php echo $instance['number_style_thousand_separate']; ?>" />
                    </div>
                    <div>
                        <label for="<?php echo 'number_style_decimal_separate'; ?>"><?php _e('Decimal Separator', 'BeRocket_AJAX_domain') ?></label>
                        <input id="<?php echo 'number_style_decimal_separate'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[number_style_decimal_separate]'; ?>" value="<?php echo $instance['number_style_decimal_separate']; ?>" />
                    </div>
                    <div>
                        <label for="<?php echo 'number_style_decimal_number'; ?>"><?php _e('Number Of Decimal', 'BeRocket_AJAX_domain') ?></label>
                        <input id="<?php echo 'number_style_decimal_number'; ?>" type="number" name="<?php echo 'BeRocket_product_new_filter[number_style_decimal_number]'; ?>" value="<?php echo $instance['number_style_decimal_number']; ?>" />
                    </div>
                </div>
            </div>
            <div>
                <input id="<?php echo 'widget_collapse_disable'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[widget_collapse_disable]'; ?>" <?php if ( ! empty($instance['widget_collapse_disable']) ) echo 'checked'; ?> value="1" />
                <label for="<?php echo 'widget_collapse_disable'; ?>"><?php _e('Disable collapse option', 'BeRocket_AJAX_domain') ?></label>
            </div>
            <div>
                <input id="<?php echo 'widget_is_hide'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[widget_is_hide]'; ?>" <?php if ( $instance['widget_is_hide'] ) echo 'checked'; ?> value="1" />
                <label for="<?php echo 'widget_is_hide'; ?>"><?php _e('Hide this widget on load?', 'BeRocket_AJAX_domain') ?></label>
            </div>
            <div class="berocket_aapf_widget_admin_non_price_tag_cloud" <?php if ( $instance['type'] == 'tag_cloud' || $instance['type'] == 'slider' ) echo 'style="display:none;"' ?>>
                <input id="<?php echo 'show_product_count_per_attr'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[show_product_count_per_attr]'; ?>" <?php if ( $instance['show_product_count_per_attr'] ) echo 'checked'; ?> value="1" />
                <label for="<?php echo 'show_product_count_per_attr'; ?>"><?php _e('Show product count per attributes?', 'BeRocket_AJAX_domain') ?></label>
            </div>
            <div>
                <input id="<?php echo 'hide_collapse_arrow'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[hide_collapse_arrow]'; ?>" <?php if ( $instance['hide_collapse_arrow'] ) echo 'checked'; ?> value="1" />
                <label for="<?php echo 'hide_collapse_arrow'; ?>"><?php _e('Hide collapse arrow?', 'BeRocket_AJAX_domain') ?></label>
            </div>
            <div class="berocket_aapf_widget_admin_non_price_tag_cloud_select" <?php if ( $instance['type'] == 'tag_cloud' || $instance['type'] == 'slider' || $instance['type'] == 'select' ) echo 'style="display:none;"' ?>>
                <input id="<?php echo 'hide_child_attributes'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[hide_child_attributes]'; ?>" <?php if ( $instance['hide_child_attributes'] ) echo 'checked'; ?> value="1" />
                <label for="<?php echo 'hide_child_attributes'; ?>"><?php _e('Hide all child values?', 'BeRocket_AJAX_domain') ?></label>
            </div>
            <div class="berocket_aapf_advanced_color_pick_settings"<?php if ( $instance['type'] != 'color' && $instance['type'] != 'image' ) echo " style='display: none;'"; ?>>
                <div>
                    <input id="<?php echo 'use_value_with_color'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[use_value_with_color]'; ?>" <?php if ( $instance['use_value_with_color'] ) echo 'checked'; ?> value="1" />
                    <label for="<?php echo 'use_value_with_color'; ?>"><?php _e('Display value with color/image box?', 'BeRocket_AJAX_domain') ?></label>
                </div>
                <div>
                    <label for="color_image_block_size"><?php _e('Size of blocks(Height x Width)', 'BeRocket_AJAX_domain') ?></label>
                    <select id="color_image_block_size" name="BeRocket_product_new_filter[color_image_block_size]">
                        <?php 
                            $color_image_sizes = array(
                                'h2em w2em' => __('2em x 2em', 'BeRocket_AJAX_domain'),
                                'h1em w1em' => __('1em x 1em', 'BeRocket_AJAX_domain'),
                                'h1em w2em' => __('1em x 2em', 'BeRocket_AJAX_domain'),
                                'h2em w3em' => __('2em x 3em', 'BeRocket_AJAX_domain'),
                                'h2em w4em' => __('2em x 4em', 'BeRocket_AJAX_domain'),
                                'h3em w3em' => __('3em x 3em', 'BeRocket_AJAX_domain'),
                                'h3em w4em' => __('3em x 4em', 'BeRocket_AJAX_domain'),
                                'h3em w5em' => __('3em x 5em', 'BeRocket_AJAX_domain'),
                                'h4em w4em' => __('4em x 4em', 'BeRocket_AJAX_domain'),
                                'h4em w5em' => __('4em x 5em', 'BeRocket_AJAX_domain'),
                                'h5em w5em' => __('5em x 5em', 'BeRocket_AJAX_domain'),
                                'hxpx_wxpx' => __('Custom size', 'BeRocket_AJAX_domain'),
                            );
                            foreach($color_image_sizes as $color_image_size_id => $color_image_size_name) {
                                echo '<option value="'.$color_image_size_id.'"'.(br_get_value_from_array($instance, 'color_image_block_size') == $color_image_size_id ? ' selected' : '').'>'.$color_image_size_name.'</option>';
                            }
                        ?>
                    </select>
                    <div class="color_image_block_size_ color_image_block_size_hxpx_wxpx"<?php if( br_get_value_from_array($instance, 'color_image_block_size') != 'hxpx_wxpx') echo ' style="display: none;"'; ?>>
                        <label><?php _e('Custom size(Height x Width)', 'BeRocket_AJAX_domain') ?></label>
                        <p>
                            <input type="number" placeholder="50" name="BeRocket_product_new_filter[color_image_block_size_height]" value="<?php echo br_get_value_from_array($instance, 'color_image_block_size_height'); ?>">px x 
                            <input type="number" placeholder="50" name="BeRocket_product_new_filter[color_image_block_size_width]" value="<?php echo br_get_value_from_array($instance, 'color_image_block_size_width'); ?>">px</p>
                    </div>
                    <script>
                        jQuery('#color_image_block_size').on('change', function() {
                            jQuery('.color_image_block_size_').hide();
                            jQuery('.color_image_block_size_'+jQuery(this).val()).show();
                        });
                    </script>
                </div>
                <div>
                    <label for="color_image_checked"><?php _e('Checked type', 'BeRocket_AJAX_domain') ?></label>
                    <select id="color_image_checked" name="BeRocket_product_new_filter[color_image_checked]">
                        <?php 
                            $color_image_sizes = array(
                                'brchecked_default' => __('Default', 'BeRocket_AJAX_domain'),
                                'brchecked_rotate' => __('Rotate', 'BeRocket_AJAX_domain'),
                                'brchecked_scale' => __('Scale', 'BeRocket_AJAX_domain'),
                                'brchecked_shadow' => __('Blue Shadow', 'BeRocket_AJAX_domain'),
                                'brchecked_custom' => __('Custom CSS', 'BeRocket_AJAX_domain'),
                            );
                            foreach($color_image_sizes as $color_image_size_id => $color_image_size_name) {
                                echo '<option value="'.$color_image_size_id.'"'.(br_get_value_from_array($instance, 'color_image_checked') == $color_image_size_id ? ' selected' : '').'>'.$color_image_size_name.'</option>';
                            }
                        ?>
                    </select>
                    <div class="color_image_checked_ color_image_checked_brchecked_custom"<?php if( br_get_value_from_array($instance, 'color_image_checked') != 'brchecked_custom') echo ' style="display: none;"'; ?>>
                        <label for="color_image_checked_custom_css"><?php _e('Custom CSS for Checked block', 'BeRocket_AJAX_domain') ?></label>
                        <p><textarea style="width: 100%;" id="color_image_checked_custom_css" name="BeRocket_product_new_filter[color_image_checked_custom_css]"><?php echo br_get_value_from_array($instance, 'color_image_checked_custom_css');?></textarea></p>
                    </div>
                    <script>
                        jQuery('#color_image_checked').on('change', function() {
                            jQuery('.color_image_checked_').hide();
                            jQuery('.color_image_checked_'+jQuery(this).val()).show();
                        });
                    </script>
                </div>
            </div>
            <div class="br_admin_full_size" <?php if ( ( ! $instance['filter_type'] or $instance['filter_type'] == 'attribute' ) and $instance['attribute'] == 'price' or $instance['filter_type'] == 'product_cat' or $instance['type'] == 'slider' or $instance['type'] == 'select' or $instance['type'] == 'tag_cloud' or ( $instance['filter_type'] == 'custom_taxonomy' and $instance['custom_taxonomy'] == 'product_cat' ) ) echo " style='display: none;'"; ?> >
                <label class="br_admin_center"><?php _e('Values per row', 'BeRocket_AJAX_domain') ?></label>
                <select id="<?php echo 'values_per_row'; ?>" name="<?php echo 'BeRocket_product_new_filter[values_per_row]'; ?>" class="berocket_aapf_widget_admin_values_per_row br_select_menu_left">
                    <option <?php if ( $instance['values_per_row'] == '1' || ! $instance['operator'] ) echo 'selected'; ?> value="1">Default</option>
                    <option <?php if ( $instance['values_per_row'] == '2' ) echo 'selected'; ?> value="2">2</option>
                    <option <?php if ( $instance['values_per_row'] == '3' ) echo 'selected'; ?> value="3">3</option>
                    <option <?php if ( $instance['values_per_row'] == '4' ) echo 'selected'; ?> value="4">4</option>
                </select>
            </div>
            <div class="br_accordion br_icons">
                <h3><?php _e('Icons', 'BeRocket_AJAX_domain') ?></h3>
                <div>
                    <label class="br_admin_center"><?php _e('Title Icons', 'BeRocket_AJAX_domain') ?></label>
                    <div class="br_clearfix"></div>
                    <div class="br_admin_half_size_left"><?php echo berocket_font_select_upload(__('Before', 'BeRocket_AJAX_domain'), 'icon_before_title', 'BeRocket_product_new_filter[icon_before_title]', $instance['icon_before_title'] ); ?></div>
                    <div class="br_admin_half_size_right"><?php echo berocket_font_select_upload(__('After', 'BeRocket_AJAX_domain'), 'icon_after_title' , 'BeRocket_product_new_filter[icon_after_title]' , $instance['icon_after_title'] ); ?></div>
                    <div class="br_clearfix"></div>
                    <div class="berocket_aapf_icons_select_block" <?php if ($instance['type'] == 'select') echo 'style="display:none;"' ?>>
                        <label class="br_admin_center"><?php _e('Value Icons', 'BeRocket_AJAX_domain') ?></label>
                        <div class="br_clearfix"></div>
                        <div class="br_admin_half_size_left"><?php echo berocket_font_select_upload(__('Before', 'BeRocket_AJAX_domain'), 'icon_before_value', 'BeRocket_product_new_filter[icon_before_value]', $instance['icon_before_value'] ); ?></div>
                        <div class="br_admin_half_size_right"><?php echo berocket_font_select_upload(__('After', 'BeRocket_AJAX_domain') , 'icon_after_value' , 'BeRocket_product_new_filter[icon_after_value]', $instance['icon_after_value'] ); ?></div>
                        <div class="br_clearfix"></div>
                    </div>
                </div>
            </div>
            <div>
                <label class="br_admin_center" style="text-align: left;" for="<?php echo 'description'; ?>"><?php _e('Description', 'BeRocket_AJAX_domain') ?></label>
                <textarea style="resize: none; width: 100%;" id="<?php echo 'description'; ?>" name="<?php echo 'BeRocket_product_new_filter[description]'; ?>"><?php echo $instance['description']; ?></textarea>
            </div>
            <div>
                <label class="br_admin_center" style="text-align: left;" for="<?php echo 'css_class'; ?>"><?php _e('CSS Class', 'BeRocket_AJAX_domain') ?> </label>
                <input id="<?php echo 'css_class'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[css_class]'; ?>" value="<?php echo $instance['css_class']; ?>" class="berocket_aapf_widget_admin_css_class_input br_admin_full_size" />
                <small class="br_admin_center" style="font-size: 1em;"><?php _e('(use white space for multiple classes)', 'BeRocket_AJAX_domain') ?></small>
            </div>
            <?php echo br_get_value_from_array($instance, 'filter_type_attribute'); ?>
            <div class="berocket_aapf_widget_admin_tag_cloud_block" <?php if ($instance['type'] != 'tag_cloud') echo 'style="display:none;"' ?>>
                <div>
                    <label for="<?php echo 'tag_cloud_height'; ?>"><?php _e('Tags Cloud Height:', 'BeRocket_AJAX_domain') ?> </label>
                    <input id="<?php echo 'tag_cloud_height'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[tag_cloud_height]'; ?>" value="<?php echo $instance['tag_cloud_height']; ?>" class="berocket_aapf_widget_admin_height_input" />px
                </div>
                <div>
                    <label for="<?php echo 'tag_cloud_min_font'; ?>"><?php _e('Min Font Size:', 'BeRocket_AJAX_domain') ?> </label>
                    <input id="<?php echo 'tag_cloud_min_font'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[tag_cloud_min_font]'; ?>" value="<?php echo $instance['tag_cloud_min_font']; ?>" class="berocket_aapf_widget_admin_height_input" />px
                </div>
                <div>
                    <label for="<?php echo 'tag_cloud_max_font'; ?>"><?php _e('Max Font Size:', 'BeRocket_AJAX_domain') ?> </label>
                    <input id="<?php echo 'tag_cloud_max_font'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[tag_cloud_max_font]'; ?>" value="<?php echo $instance['tag_cloud_max_font']; ?>" class="berocket_aapf_widget_admin_height_input" />px
                </div>
                <div>
                    <label for="<?php echo 'tag_cloud_tags_count'; ?>"><?php _e('Max Tags Count:', 'BeRocket_AJAX_domain') ?> </label>
                    <input id="<?php echo 'tag_cloud_tags_count'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[tag_cloud_tags_count]'; ?>" value="<?php echo $instance['tag_cloud_tags_count']; ?>" class="berocket_aapf_widget_admin_height_input" />
                </div>
            </div>
            <div class="berocket_aapf_widget_admin_price_attribute" <?php if ( ! ( $instance['attribute'] == 'price' && $instance['type'] == 'slider' ) ) echo " style='display: none;'"; ?> >
                <div class="br_admin_half_size_left">
                    <div class="berocket_aapf_checked_show_next">
                        <input id="<?php echo 'use_min_price'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[use_min_price]'; ?>" <?php if ( $instance['use_min_price'] ) echo 'checked'; ?> value="1" class="berocket_aapf_widget_admin_input_price_is"/>
                        <label class="br_admin_full_size" for="<?php echo 'use_min_price'; ?>"><?php _e('Use min price', 'BeRocket_AJAX_domain') ?></label>
                    </div>
                    <div <?php if ( !$instance['use_min_price'] ) echo 'style="display:none"'; ?>>
                        <input type=number min=0 id="<?php echo 'min_price'; ?>" name="<?php echo 'BeRocket_product_new_filter[min_price]'; ?>" value="<?php echo ( ( $instance['min_price'] ) ? $instance['min_price'] : '0' ); ?>" class="br_admin_full_size berocket_aapf_widget_admin_input_price">
                    </div>
                </div>
                <div class="br_admin_half_size_right">
                    <div class="berocket_aapf_checked_show_next">
                        <input id="<?php echo 'use_max_price'; ?>" type="checkbox" name="<?php echo 'BeRocket_product_new_filter[use_max_price]'; ?>" <?php if ( $instance['use_max_price'] ) echo 'checked'; ?> value="1" class="berocket_aapf_widget_admin_input_price_is"/>
                        <label class="br_admin_full_size" for="<?php echo 'use_max_price'; ?>"><?php _e('Use max price', 'BeRocket_AJAX_domain') ?></label>
                    </div>
                    <div <?php if ( !$instance['use_max_price'] ) echo 'style="display:none"'; ?>>
                        <input type=number min=1 id="<?php echo 'max_price'; ?>" name="<?php echo 'BeRocket_product_new_filter[max_price]'; ?>" value="<?php echo ( ( $instance['max_price'] ) ? $instance['max_price'] : '0' ); ?>" class="br_admin_full_size berocket_aapf_widget_admin_input_price">
                    </div>
                </div>
                <div class="br_clearfix"></div>
            </div>
            <div>
                <label for="<?php echo 'height'; ?>"><?php _e('Filter Box Height:', 'BeRocket_AJAX_domain') ?> </label>
                <input id="<?php echo 'height'; ?>" type="text" name="<?php echo 'BeRocket_product_new_filter[height]'; ?>" value="<?php echo $instance['height']; ?>" class="berocket_aapf_widget_admin_height_input" />px
            </div>
            <div>
                <label for="<?php echo 'scroll_theme'; ?>"><?php _e('Scroll Theme:', 'BeRocket_AJAX_domain') ?> </label>
                <select id="<?php echo 'scroll_theme'; ?>" name="<?php echo 'BeRocket_product_new_filter[scroll_theme]'; ?>" class="berocket_aapf_widget_admin_scroll_theme_select br_select_menu_left">
                    <?php
                    $scroll_themes = array("light", "dark", "minimal", "minimal-dark", "light-2", "dark-2", "light-3", "dark-3", "light-thick", "dark-thick", "light-thin",
                        "dark-thin", "inset", "inset-dark", "inset-2", "inset-2-dark", "inset-3", "inset-3-dark", "rounded", "rounded-dark", "rounded-dots",
                        "rounded-dots-dark", "3d", "3d-dark", "3d-thick", "3d-thick-dark");
                    foreach( $scroll_themes as $theme ): ?>
                        <option <?php if ( $instance['scroll_theme'] == $theme ) echo 'selected'; ?>><?php echo $theme; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="br_aapf_child_parent_selector" <?php if ( $instance['filter_type'] == 'attribute' and $instance['attribute'] == 'price'  or $instance['filter_type'] == 'product_cat' or $instance['filter_type'] == '_stock_status' or $instance['filter_type'] == 'tag' or $instance['type'] == 'slider' or $instance['filter_type'] == 'date' or $instance['filter_type'] == '_sale' or $instance['filter_type'] == '_rating' ) echo " style='display: none;'"; ?>>
                <div>
                    <label class="br_admin_center"><?php _e('Child/Parent Limitation', 'BeRocket_AJAX_domain') ?></label>
                    <select name="<?php echo 'BeRocket_product_new_filter[child_parent]'; ?>" class="br_select_menu_left berocket_aapf_widget_child_parent_select">
                        <option value="" <?php if ( ! $instance['child_parent'] ) echo 'selected' ?>><?php _e('Default', 'BeRocket_AJAX_domain') ?></option>
                        <option value="depth" <?php if ( $instance['child_parent'] == 'depth' ) echo 'selected' ?>><?php _e('Child Count', 'BeRocket_AJAX_domain') ?></option>
                        <option value="parent" <?php if ( $instance['child_parent'] == 'parent' ) echo 'selected' ?>><?php _e('Parent', 'BeRocket_AJAX_domain') ?></option>
                        <option value="child" <?php if ( $instance['child_parent'] == 'child' ) echo 'selected' ?>><?php _e('Child', 'BeRocket_AJAX_domain') ?></option>
                    </select>
                </div>
                <div class="berocket_aapf_widget_child_parent_depth_block" <?php if( $instance['child_parent'] != 'child' ) echo 'style="display: none;"'; ?>>
                    <label for="<?php echo 'child_parent_depth'; ?>" class="br_admin_full_size"><?php _e('Child depth', 'BeRocket_AJAX_domain') ?></label>
                    <input name="<?php echo 'BeRocket_product_new_filter[child_parent_depth]'; ?>" id="<?php echo 'child_parent_depth'; ?>" type="number" min="1" value="<?php echo $instance['child_parent_depth']; ?>">
                    <div>
                        <label><?php _e('"No values" messages', 'BeRocket_AJAX_domain') ?></label>
                        <input class="br_admin_full_size" name="<?php echo 'BeRocket_product_new_filter[child_parent_no_values]'; ?>" type="text" value="<?php echo $instance['child_parent_no_values']; ?>">
                    </div>
                    <div>
                        <label><?php _e('"Select previous" messages', 'BeRocket_AJAX_domain') ?></label>
                        <input class="br_admin_full_size" name="<?php echo 'BeRocket_product_new_filter[child_parent_previous]'; ?>" type="text" value="<?php echo $instance['child_parent_previous']; ?>">
                    </div>
                    <div>
                        <label><?php _e('"No Products" messages', 'BeRocket_AJAX_domain') ?></label>
                        <input class="br_admin_full_size" name="<?php echo 'BeRocket_product_new_filter[child_parent_no_products]'; ?>" type="text" value="<?php echo $instance['child_parent_no_products']; ?>">
                    </div>
                </div>
                <div class="berocket_aapf_widget_child_parent_one_widget" <?php if( $instance['child_parent'] != 'depth' ) echo 'style="display: none;"'; ?>>
                    <label for="<?php echo 'child_onew_count'; ?>" class="br_admin_full_size"><?php _e('Child count', 'BeRocket_AJAX_domain') ?></label>
                    <select class="br_onew_child_count_select br_select_menu_left" id="<?php echo 'child_onew_count'; ?>" name="<?php echo 'BeRocket_product_new_filter[child_onew_count]'; ?>">
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
                                <input class="br_admin_full_size" name="<?php echo 'BeRocket_product_new_filter[child_onew_childs]'.'['.$i.'][title]'; ?>" type="text" value="<?php echo $instance['child_onew_childs'][$i]['title']; ?>">
                            </div>
                            <div>
                                <label><?php _e('"No products" messages', 'BeRocket_AJAX_domain') ?></label>
                                <input class="br_admin_full_size" name="<?php echo 'BeRocket_product_new_filter[child_onew_childs]'.'['.$i.'][no_product]'; ?>" type="text" value="<?php echo $instance['child_onew_childs'][$i]['no_product']; ?>">
                            </div>
                            <div>
                                <label><?php _e('"No values" messages', 'BeRocket_AJAX_domain') ?></label>
                                <input class="br_admin_full_size" name="<?php echo 'BeRocket_product_new_filter[child_onew_childs]'.'['.$i.'][no_values]'; ?>" type="text" value="<?php echo $instance['child_onew_childs'][$i]['no_values']; ?>">
                            </div>
                            <div>
                                <label><?php _e('"Select previous" messages', 'BeRocket_AJAX_domain') ?></label>
                                <input class="br_admin_full_size" name="<?php echo 'BeRocket_product_new_filter[child_onew_childs]'.'['.$i.'][previous]'; ?>" type="text" value="<?php echo $instance['child_onew_childs'][$i]['previous']; ?>">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
</div>
<div class="berocket_aapf_admin_widget_selected_area" <?php if ( $instance['widget_type'] != 'selected_area' or $instance['widget_type'] == 'search_box' ) echo 'style="display: none;"'; ?>>
    <div>
        <label>
            <input type="checkbox" name="<?php echo 'BeRocket_product_new_filter[selected_area_show]'; ?>" <?php if ( $instance['selected_area_show'] ) echo 'checked'; ?> value="1" />
            <?php _e('Show if nothing is selected', 'BeRocket_AJAX_domain') ?>
        </label>
    </div>
    <div>
        <label>
            <input type="checkbox" name="<?php echo 'BeRocket_product_new_filter[hide_selected_arrow]'; ?>" <?php if ( $instance['hide_selected_arrow'] ) echo 'checked'; ?> value="1" />
            <?php _e('Hide collapse arrow?', 'BeRocket_AJAX_domain') ?>
        </label>
    </div>
    <div>
        <label>
            <input type="checkbox" name="<?php echo 'BeRocket_product_new_filter[selected_is_hide]'; ?>" <?php if ( $instance['selected_is_hide'] ) echo 'checked'; ?> value="1" />
            <?php _e('Hide this widget on load?', 'BeRocket_AJAX_domain') ?>
        </label>
    </div>
</div>
<div class="berocket_aapf_admin_search_box"<?php if( $instance['widget_type'] != 'search_box' ) echo ' style="display:none;"'; ?>>
    <div class="br_accordion">
        <h3><?php _e('Attributes', 'BeRocket_AJAX_domain') ?></h3>
        <div>
            <div>
                <label><?php _e('URL to search', 'BeRocket_AJAX_domain') ?></label>
                <select name="<?php echo 'BeRocket_product_new_filter[search_box_link_type]'; ?>" class="berocket_search_link_select br_select_menu_left">
                    <option value="shop_page"<?php if ($instance['search_box_link_type'] == 'shop_page' ) echo ' selected'; ?>><?php _e('Shop page', 'BeRocket_AJAX_domain') ?></option>
                    <option value="category"<?php if ($instance['search_box_link_type'] == 'category' ) echo ' selected'; ?>><?php _e('Category page', 'BeRocket_AJAX_domain') ?></option>
                    <option value="url"<?php if ($instance['search_box_link_type'] == 'url' ) echo ' selected'; ?>><?php _e('URL', 'BeRocket_AJAX_domain') ?></option>
                </select>
            </div>
            <div class="berocket_search_link berocket_search_link_category"<?php if( $instance['search_box_link_type'] != 'category' ) echo ' style="display:none;"'; ?>>
                <label><?php _e('Category', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo 'BeRocket_product_new_filter[search_box_category]'; ?>">
                <?php 
                $instance['search_box_category'] = ( empty($instance['search_box_category']) ? '' : urldecode($instance['search_box_category']) );
                foreach( $categories as $category ){
                    echo '<option value="'.$category->slug.'"'.($instance['search_box_category'] == $category->slug ? ' selected' : '').'>'.$category->name.'</option>';
                } ?>
                </select>
            </div>
            <div class="berocket_search_link berocket_search_link_url"<?php if( $instance['search_box_link_type'] != 'url' ) echo ' style="display:none;"'; ?>>
                <label><?php _e('URL for search', 'BeRocket_AJAX_domain') ?></label>
                <input class="br_admin_full_size" id="<?php echo 'search_box_url'; ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_url]'; ?>" type="text" value="<?php echo $instance['search_box_url']; ?>">
            </div>
            <div>
                <label><?php _e('Attributes count', 'BeRocket_AJAX_domain') ?></label>
                <select id="<?php echo 'search_box_count'; ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_count]'; ?>" class="br_search_box_count br_select_menu_left">
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
                            <input class="br_admin_full_size" id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_title" type="text" name="<?php echo 'BeRocket_product_new_filter[search_box_attributes]'; ?>[<?php echo $i; ?>][title]" value="<?php echo $instance['search_box_attributes'][$i]['title']; ?>"/>
                        </div>
                        <div class="br_admin_half_size_left">
                            <label class="br_admin_center"><?php _e('Filter By', 'BeRocket_AJAX_domain') ?></label>
                            <select id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_attributes]'; ?>[<?php echo $i; ?>][type]" class="br_search_box_attribute_type br_select_menu_left">
                                <option <?php if ( $instance['search_box_attributes'][$i]['type'] == 'attribute' ) echo 'selected'; ?> value="attribute"><?php _e('Attribute', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( $instance['search_box_attributes'][$i]['type'] == 'tag' ) echo 'selected'; ?> value="tag"><?php _e('Tag', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( $instance['search_box_attributes'][$i]['type'] == 'custom_taxonomy' ) echo 'selected'; ?> value="custom_taxonomy"><?php _e('Custom Taxonomy', 'BeRocket_AJAX_domain') ?></option>
                            </select>
                        </div>
                        <div class="br_admin_half_size_right br_search_box_attribute_attribute_block" <?php if ( $instance['search_box_attributes'][$i]['type'] and $instance['search_box_attributes'][$i]['type'] != 'attribute') echo 'style="display: none;"'; ?>>
                            <label class="br_admin_center"><?php _e('Attribute', 'BeRocket_AJAX_domain') ?></label>
                            <select id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_attribute" name="<?php echo 'BeRocket_product_new_filter[search_box_attributes]'; ?>[<?php echo $i; ?>][attribute]" class="br_search_box_attribute_attribute br_select_menu_right">
                                <?php foreach ( $attributes as $k => $v ) { ?>
                                    <option <?php if ( $instance['search_box_attributes'][$i]['attribute'] == $k ) echo 'selected'; ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="br_admin_half_size_right br_search_box_attribute_custom_taxonomy_block" <?php if ( $instance['search_box_attributes'][$i]['type'] != 'custom_taxonomy') echo 'style="display: none;"'; ?>>
                            <label class="br_admin_center"><?php _e('Custom Taxonomies', 'BeRocket_AJAX_domain') ?></label>
                            <select id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_custom" name="<?php echo 'BeRocket_product_new_filter[search_box_attributes]'; ?>[<?php echo $i; ?>][custom_taxonomy]" class="br_search_box_attribute_custom_taxonomy br_select_menu_right">
                                <?php foreach( $custom_taxonomies as $k => $v ){ ?>
                                    <option <?php if ( $instance['search_box_attributes'][$i]['custom_taxonomy'] == $k ) echo 'selected'; ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="br_clearfix"></div>
                        <div>
                            <label class="br_admin_center"><?php _e('Type', 'BeRocket_AJAX_domain') ?></label>
                            <select id="<?php echo 'search_box_attributes'; ?>_<?php echo $i; ?>_visual_type" name="<?php echo 'BeRocket_product_new_filter[search_box_attributes]'; ?>[<?php echo $i; ?>][visual_type]" class="br_select_menu_left">
                                <option <?php if ( $instance['search_box_attributes'][$i]['visual_type'] == 'select' ) echo 'selected'; ?> value="select"><?php _e('Select', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( $instance['search_box_attributes'][$i]['visual_type'] == 'checkbox' ) echo 'selected'; ?> value="checkbox"><?php _e('Checkbox', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( $instance['search_box_attributes'][$i]['visual_type'] == 'radio' ) echo 'selected'; ?> value="radio"><?php _e('Radio', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( $instance['search_box_attributes'][$i]['visual_type'] == 'color' ) echo 'selected'; ?> value="color"><?php _e('Color', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php if ( $instance['search_box_attributes'][$i]['visual_type'] == 'image' ) echo 'selected'; ?> value="image"><?php _e('Image', 'BeRocket_AJAX_domain') ?></option>
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
                <select class="br_select_menu_left" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[position]">
                    <option value="vertical"<?php if( br_get_value_from_array($instance, array('search_box_style', 'position')) == 'vertical' ) echo ' selected'; ?>><?php _e('Vertical', 'BeRocket_AJAX_domain') ?></option>
                    <option value="horizontal"<?php if( br_get_value_from_array($instance, array('search_box_style', 'position')) == 'horizontal' ) echo ' selected'; ?>><?php _e('Horizontal', 'BeRocket_AJAX_domain') ?></option>
                </select>
            </div>
            <div>
                <label><?php _e('Search button position', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[search_position]">
                    <option value="before"<?php if( br_get_value_from_array($instance, array('search_box_style', 'search_position')) == 'before' ) echo ' selected'; ?>><?php _e('Before', 'BeRocket_AJAX_domain') ?></option>
                    <option value="after"<?php if( br_get_value_from_array($instance, array('search_box_style', 'search_position')) == 'after' ) echo ' selected'; ?>><?php _e('After', 'BeRocket_AJAX_domain') ?></option>
                    <option value="before_after"<?php if( br_get_value_from_array($instance, array('search_box_style', 'search_position')) == 'before_after' ) echo ' selected'; ?>><?php _e('Before and after', 'BeRocket_AJAX_domain') ?></option>
                </select>
            </div>
            <div>
                <label><?php _e('Search button text', 'BeRocket_AJAX_domain') ?></label>
                <input type="text" class="br_admin_full_size" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'search_text')); ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[search_text]">
            </div>
            <div>
                <label><?php _e('Background color', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'background'), '000000'); ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'background')) ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[background]">
            </div>
            <div>
                <label><?php _e('Background transparency', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[back_opacity]">
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
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'button_background')) ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[button_background]">
            </div>
            <div>
                <label><?php _e('Button background color on mouse over', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'button_background_over'), '000000'); ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'button_background_over')) ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[button_background_over]">
            </div>
            <div>
                <label><?php _e('Button text color', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'text_color'), '000000') ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'text_color')) ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[text_color]">
            </div>
            <div>
                <label><?php _e('Button text color on mouse over', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($instance, array('search_box_style', 'text_color_over'), '000000') ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($instance, array('search_box_style', 'text_color_over')) ?>" name="<?php echo 'BeRocket_product_new_filter[search_box_style]'; ?>[text_color_over]">
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function() {
    jQuery('.colorpicker_field').each(function (i,o){
        jQuery(o).css('backgroundColor', '#'+jQuery(o).data('color'));
        jQuery(o).colpick({
            layout: 'hex',
            submit: 0,
            color: '#'+jQuery(o).data('color'),
            onChange: function(hsb,hex,rgb,el,bySetColor) {
                jQuery(el).removeClass('colorpicker_removed');
                jQuery(el).css('backgroundColor', '#'+hex).next().val(hex).trigger('change');
            }
        })
    });
});
</script>
    <h3><?php _e('Widget Output Limitations', 'BeRocket_AJAX_domain') ?></h3>
    <div>
        <div class="br_accordion berocket_product_category_value_limit"<?php if( ! empty($instance['widget_type']) && $instance['widget_type'] != 'filter' ) echo ' style="display: none";'?>>
            <h3><?php _e('Product Category Value Limitation', 'BeRocket_AJAX_domain') ?></h3>
            <div>
                <ul class="br_admin_150_height">
                    <li>
                        <input type="radio" name="<?php echo 'BeRocket_product_new_filter[cat_value_limit]'; ?>" <?php if ( ! $instance['cat_value_limit'] ) echo 'checked'; ?> value="0"/>
                        <?php _e('Disable', 'BeRocket_AJAX_domain') ?>
                    </li>
                <?php
                $instance['cat_value_limit'] = ( empty($instance['cat_value_limit']) ? '' : urldecode($instance['cat_value_limit']) );
                foreach( $categories as $category ){
                    $selected_category = false;
                    if( $instance['cat_value_limit'] == $category->slug )
                        $selected_category = true;
                    ?>
                    <li>
                        <?php
                        if ( (int)$category->depth ) for ( $depth_i = 0; $depth_i < $category->depth*3; $depth_i++ ) echo "&nbsp;";
                        ?>
                        <input type="radio" name="<?php echo 'BeRocket_product_new_filter[cat_value_limit]'; ?>" <?php if ( $selected_category ) echo 'checked'; ?> value="<?php echo $category->slug ?>"/>
                        <?php echo $category->name ?>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>
        <?php
        $taxonomy_name = false;
        if( $instance['filter_type'] == 'custom_taxonomy' ) {
            $taxonomy_name = $instance['custom_taxonomy'];
        } elseif( $instance['filter_type'] == 'attribute' && $instance['attribute'] != 'price' ) {
            $taxonomy_name = $instance['attribute'];
        }
        ?>
        <div class="include_exclude_select"<?php if( $taxonomy_name === false ) echo ' style="display: none;"' ?>>
            <select name="<?php echo 'BeRocket_product_new_filter[include_exclude_select]'; ?>">
                <option value=""><?php _e('Disabled', 'BeRocket_AJAX_domain') ?></option>
                <option value="include"<?php if( $instance['include_exclude_select'] == 'include' ) echo ' selected'; ?>><?php _e('Display only', 'BeRocket_AJAX_domain') ?></option>
                <option value="exclude"<?php if( $instance['include_exclude_select'] == 'exclude' ) echo ' selected'; ?>><?php _e('Remove', 'BeRocket_AJAX_domain') ?></option>
            </select>
            <label><?php _e('values selected in Include / Exclude List', 'BeRocket_AJAX_domain') ?></label>
        </div>
        <div class="include_exclude_list" data-name="<?php echo 'BeRocket_product_new_filter[include_exclude_list]'; ?>"<?php if( empty($instance['include_exclude_select']) ) echo ' style="display: none;"'; ?>>
            <?php
            if( $taxonomy_name !== false ) {
                $list = BeRocket_AAPF_Widget::include_exclude_terms_list($taxonomy_name, $instance['include_exclude_list']);
                $list = str_replace('%field_name%', 'BeRocket_product_new_filter[include_exclude_list]', $list);
                echo $list;
            }
            ?>
        </div>
    </div>
<script>
    if( typeof(br_widget_set) == 'function' )
        br_widget_set();
</script>
</div>
