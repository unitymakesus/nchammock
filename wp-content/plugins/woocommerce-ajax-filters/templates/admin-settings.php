<?php 
$dplugin_name = 'WooCommerce AJAX Products Filter';
$dplugin_link = 'http://berocket.com/product/woocommerce-ajax-products-filter';
$dplugin_price = 26;
$dplugin_desc = '';
@ include 'settings_head.php';
?>
<div class="wrap">
    <form class="berocket_aapf_setting_form" method="post" action="options.php">
        <?php
        settings_fields('br_filters_plugin_options');
        $options = BeRocket_AAPF::get_aapf_option();
        $fonts_list = g_fonts_list();

        $designables = br_aapf_get_styled();
        $tabs_array = array('general', 'elements', 'selectors', 'seo', 'advanced', 'design', 'javascript', 'customcss', 'shortcode');
        ?>
        <h2 class="nav-tab-wrapper filter_settings_tabs">
            <a href="#general" class="nav-tab <?php if($options['br_opened_tab'] == 'general' || !in_array( $options['br_opened_tab'], $tabs_array ) ) echo 'nav-tab-active'; ?>"><?php _e('General', 'BeRocket_AJAX_domain') ?></a>
            <a href="#elements" class="nav-tab <?php if($options['br_opened_tab'] == 'elements' ) echo 'nav-tab-active'; ?>"><?php _e('Elements', 'BeRocket_AJAX_domain') ?></a>
            <a href="#selectors" class="nav-tab <?php if($options['br_opened_tab'] == 'selectors' ) echo 'nav-tab-active'; ?>"><?php _e('Selectors', 'BeRocket_AJAX_domain') ?></a>
            <a href="<?php echo admin_url( 'edit.php?post_type=br_filters_group' ); ?>" target="_blank" class="link-tab"><?php _e('Filters Group', 'BeRocket_AJAX_domain') ?></a>
            <a href="#seo" class="nav-tab <?php if($options['br_opened_tab'] == 'seo' ) echo 'nav-tab-active'; ?>"><?php _e('SEO', 'BeRocket_AJAX_domain') ?></a>
            <a href="#advanced" class="nav-tab <?php if($options['br_opened_tab'] == 'advanced' ) echo 'nav-tab-active'; ?>"><?php _e('Advanced', 'BeRocket_AJAX_domain') ?></a>
            <a href="#design" class="nav-tab <?php if($options['br_opened_tab'] == 'design' ) echo 'nav-tab-active'; ?>"><?php _e('Design', 'BeRocket_AJAX_domain') ?></a>
            <a href="#javascript" class="nav-tab <?php if($options['br_opened_tab'] == 'javascript' ) echo 'nav-tab-active'; ?>"><?php _e('JavaScript/CSS', 'BeRocket_AJAX_domain') ?></a>
        </h2>
        <div id="general" class="tab-item <?php if($options['br_opened_tab'] == 'general' || !in_array( $options['br_opened_tab'], $tabs_array ) ) echo 'current'; ?>">
            <table class="form-table">
                <?php if( apply_filters('br_filters_options-setup_wizard-show', true) ) { ?>
                <tr>
                    <th scope="row"><?php _e('SETUP WIZARD', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <a class="button" href="<?php echo admin_url( 'admin.php?page=br-aapf-setup' ); ?>"><?php _e('RUN SETUP WIZARD', 'BeRocket_AJAX_domain') ?></a>
                        <div>
                            <?php _e('Run it to setup plugin options step by step', 'BeRocket_AJAX_domain') ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>
                
                <tr>
                    <th scope="row"><?php _e('"No Products" message', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input size="50" name="br_filters_options[no_products_message]" type='text' value='<?php echo $options['no_products_message']?>'/>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e('Text that will be shown if no products found', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr<?php if ( br_is_plugin_active( 'list-grid' ) || br_is_plugin_active( 'List_Grid' ) || br_is_plugin_active( 'more-products' ) || br_is_plugin_active( 'Load_More_Products' ) ) echo ' style="display: none;"'?>>
                    <th><?php _e( 'Products Per Page', 'BeRocket_AJAX_domain' ) ?></th>
                    <td>
                        <input name="br_filters_options[products_per_page]" value="<?php echo br_get_value_from_array($options,'products_per_page'); ?>" type="number">
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Attribute Values count', 'BeRocket_AJAX_domain' ) ?></th>
                    <td>
                        <input name="br_filters_options[attribute_count]" value="<?php echo br_get_value_from_array($options,'attribute_count'); ?>" type="number">
                        <?php _e( 'Attribute Values count that will be displayed. Other values will be hidden and can be displayed by pressing the button. Option <strong>Hide "Show/Hide value(s)" button</strong> must be disabled', 'BeRocket_AJAX_domain' ) ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Sorting control', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[control_sorting]" type='checkbox' value='1' <?php if( ! empty($options['control_sorting']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e("Take control over WooCommerce's sorting selectbox?", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Hide values', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[hide_value][o]" type='checkbox' value='1' <?php if( ! empty($options['hide_value']['o']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Hide values without products', 'BeRocket_AJAX_domain') ?></span><br>
                        <input name="br_filters_options[hide_value][sel]" type='checkbox' value='1' <?php if( ! empty($options['hide_value']['sel']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Hide selected values', 'BeRocket_AJAX_domain') ?></span><br>
                        <input name="br_filters_options[hide_value][empty]" type='checkbox' value='1' <?php if( ! empty($options['hide_value']['empty']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Hide empty widget', 'BeRocket_AJAX_domain') ?></span><br>
                        <input name="br_filters_options[hide_value][button]" type='checkbox' value='1' <?php if( ! empty($options['hide_value']['button']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Hide "Show/Hide value(s)" button', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Jump to first page', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[first_page_jump]" type='checkbox' value='1' <?php if( ! empty($options['first_page_jump']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Check if you want load first page after filters change', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Scroll page to the top', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_scroll_shop_top" name="br_filters_options[scroll_shop_top]" type='checkbox' value='1' <?php if( ! empty($options['scroll_shop_top']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Check if you want scroll page to the top of shop after filters change', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                    <td <?php if( empty($options['scroll_shop_top']) ) echo ' style="display:none;"';?>>
                        <input name="br_filters_options[scroll_shop_top_px]" type='number' value='<?php echo ( ! empty($options['scroll_shop_top_px']) ? $options['scroll_shop_top_px'] : BeRocket_AAPF::$defaults['scroll_shop_top_px'] ); ?>'/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('px from products top.', 'BeRocket_AJAX_domain') ?></span><br>
                        <span><?php _e('Use this to fix top scroll.', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Select2', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[use_select2]" type='checkbox' value='1' <?php if( ! empty($options['use_select2']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Use Select2 script for dropdown menu', 'BeRocket_AJAX_domain') ?></span>
                        <p>
                            <input name="br_filters_options[fixed_select2]" type='checkbox' value='1' <?php if( ! empty($options['fixed_select2']) ) echo "checked='checked'";?>/>
                            <span style="color:#666666;margin-left:2px;"><?php _e('Fixed CSS styles for Select2 (do not enable if Select2 work correct. Option can break Select2 in other plugins or themes)', 'BeRocket_AJAX_domain') ?></span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Reload amount of products', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="reload_amount_of_products" name="br_filters_options[recount_products]" type='checkbox' value='1' <?php if( ! empty($options['recount_products']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Use filters on products count display', 'BeRocket_AJAX_domain') ?></span>
                        <p class="notice notice-error"><?php _e('Can slow down page load and filtering speed. Also do not use it with more then 5000 products.', 'BeRocket_AJAX_domain') ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Data cache', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <select name="br_filters_options[object_cache]">
                            <option <?php echo ( empty($options['object_cache']) ) ? 'selected' : '' ?> value=""><?php _e('Disable', 'BeRocket_AJAX_domain') ?></option>
                            <option <?php echo ( br_get_value_from_array($options, 'object_cache') == 'wordpress' ) ? 'selected' : '' ?> value="wordpress"><?php _e('WordPress Cache', 'BeRocket_AJAX_domain') ?></option>
                            <option <?php echo ( br_get_value_from_array($options, 'object_cache') == 'persistent' ) ? 'selected' : '' ?> value="persistent"><?php _e('Persistent Cache Plugins', 'BeRocket_AJAX_domain') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Thousand Separator', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[number_style][thousand_separate]" type='text' value='<?php echo br_get_value_from_array($options, array('number_style', 'thousand_separate'))?>'/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Decimal Separator', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[number_style][decimal_separate]" type='text' value='<?php echo br_get_value_from_array($options, array('number_style', 'decimal_separate'))?>'/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Number Of Decimal', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[number_style][decimal_number]" min="0" type='number' value='<?php echo br_get_value_from_array($options, array('number_style', 'decimal_number'))?>'/>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_AJAX_domain') ?>" />
            </p>
        </div>
        <div id="elements" class="tab-item <?php if($options['br_opened_tab'] == 'elements' ) echo 'current'; ?>">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Elements position', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <select name="br_filters_options[elements_position_hook]">
                        <?php
                            $elements_position_hook = array(
                                'woocommerce_archive_description' => __('WooCommerce Description(in header)', 'BeRocket_AJAX_domain'),
                                'woocommerce_before_shop_loop' => __('WooCommerce Before Shop Loop', 'BeRocket_AJAX_domain'),
                                'woocommerce_after_shop_loop' => __('WooCommerce After Shop Loop', 'BeRocket_AJAX_domain')
                            );
                            $current_elements_hook = br_get_value_from_array($options, 'elements_position_hook', 'woocommerce_archive_description');
                            foreach($elements_position_hook as $hook_slug => $hook_name) {
                                echo '<option value="' . $hook_slug . '"' . ($current_elements_hook == $hook_slug ? ' selected' : '') . '>' . $hook_name . '</option>';
                            }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Show selected filters', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[selected_area_show]" type='checkbox' value='1' <?php if( ! empty($options['selected_area_show']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Show selected filters above products', 'BeRocket_AJAX_domain') ?></span>
                        <p>
                            <input name="br_filters_options[selected_area_hide_empty]" type='checkbox' value='1' <?php if( ! empty($options['selected_area_hide_empty']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Hide selected filters area if nothing selected(affect only area above products)', 'BeRocket_AJAX_domain') ?></span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Show products count before filtering', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[ub_product_count]" type='checkbox' value='1' <?php if( ! empty($options['ub_product_count']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Show products count before filtering, when using update button', 'BeRocket_AJAX_domain') ?></span>
                        <p>
                            <label><?php _e('Text that means products', 'BeRocket_AJAX_domain') ?></label>
                            <input name="br_filters_options[ub_product_text]" type='text' value='<?php echo br_get_value_from_array($options, 'ub_product_text');?>'/>
                        </p>
                        <p>
                            <label><?php _e('Text for show button', 'BeRocket_AJAX_domain') ?></label>
                            <input name="br_filters_options[ub_product_button_text]" type='text' value='<?php echo br_get_value_from_array($options, 'ub_product_button_text');?>'/>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Elements above products', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <?php
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
                        echo '<div>' . __('Group', 'BeRocket_AJAX_domain') . '<select>';
                        foreach($br_filters_group as $post_id) {
                            echo '<option data-name="' . get_the_title($post_id) . '" value="' . $post_id . '">' . get_the_title($post_id) . ' (ID:' . $post_id . ')</option>';
                        }
                        echo '</select><button class="button berocket_elements_above_group" type="button">'.__('Add group', 'BeRocket_AJAX_domain').'</button></div>';
                        echo '<ul class="berocket_elements_above_products">';
                        if( is_array(br_get_value_from_array($options, 'elements_above_products')) ) {
                            foreach($options['elements_above_products'] as $post_id) {
                                $post_type = get_post_type($post_id);
                                echo '<li class="berocket_elements_added_' . $post_id . '"><fa class="fa fa-bars"></fa>
                                    <input type="hidden" name="br_filters_options[elements_above_products][]" value="' . $post_id . '">
                                    ' . get_the_title($post_id) . ' (ID:' . $post_id . ')
                                    <i class="fa fa-times"></i>
                                </li>';
                            }
                        }
                        echo '</ul>';
                        wp_enqueue_script('jquery-color');
                        wp_enqueue_script('jquery-ui-sortable');
                        ?>
                        <script>
                            jQuery(document).on('click', '.berocket_elements_above_group', function(event) {
                                event.preventDefault();
                                var selected = jQuery(this).prev().find(':selected');
                                post_id = selected.val();
                                post_title = selected.text();
                                if( ! jQuery('.berocket_elements_added_'+post_id).length ) {
                                    var html = '<li class="berocket_elements_added_'+post_id+'"><fa class="fa fa-bars"></fa>';
                                    html += '<input type="hidden" name="br_filters_options[elements_above_products][]" value="'+post_id+'">';
                                    html += post_title;
                                    html += '<i class="fa fa-times"></i></li>';
                                    jQuery('.berocket_elements_above_products').append(jQuery(html));
                                } else {
                                    jQuery('.berocket_elements_added_'+post_id).css('background-color', '#ee3333').clearQueue().animate({backgroundColor:'#eeeeee'}, 1000);
                                }
                            });
                            jQuery(document).on('click', '.berocket_elements_above_products .fa-times', function(event) {
                                jQuery(this).parents('li').first().remove();
                            });
                            jQuery(document).ready(function() {
                                if(typeof(jQuery( ".berocket_elements_above_products" ).sortable) == 'function') {
                                    jQuery( ".berocket_elements_above_products" ).sortable({axis:"y", handle:".fa-bars", placeholder: "berocket_sortable_space"});
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
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_AJAX_domain') ?>" />
            </p>
        </div>
        <div id="selectors" class="tab-item <?php if($options['br_opened_tab'] == 'selectors' ) echo 'current'; ?>">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Get selectors automatically (BETA)', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <?php echo BeRocket_wizard_generate_autoselectors(array('products' => '.berocket_aapf_products_selector', 'pagination' => '.berocket_aapf_pagination_selector', 'result_count' => '.berocket_aapf_product_count_selector')); ?>
                        <div>
                            <?php _e('Please do not use it on live sites. If something went wrong write us.', 'BeRocket_AJAX_domain') ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Products selector', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_aapf_products_selector" size="30" name="br_filters_options[products_holder_id]" type='text' value='<?php echo ! empty($options['products_holder_id'])?$options['products_holder_id']:BeRocket_AAPF::$defaults['products_holder_id']?>'/>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e("Selector for tag that is holding products. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Product count selector', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_aapf_product_count_selector" size="30" name="br_filters_options[woocommerce_result_count_class]" type='text' value='<?php echo ! empty($options['woocommerce_result_count_class'])?$options['woocommerce_result_count_class']:BeRocket_AAPF::$defaults['woocommerce_result_count_class']?>'/>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e('Selector for tag with product result count("Showing 1–8 of 61 results"). Don\'t change this if you don\'t know what it is', 'BeRocket_AJAX_domain') ?></span>
                        <?php if( apply_filters('br_filters_options-woocommerce_removes_result_count-show', true) ) { ?>
                        <div class="settings-sub-option">
                            <input name="br_filters_options[woocommerce_removes][result_count]" type='checkbox' value='1' <?php if( ! empty($options['woocommerce_removes']['result_count']) ) echo "checked='checked'";?>/>
                            <span style="color:#666666;margin-left:2px;"><?php _e('Removed product count. Enable if page doesn\'t have product count block', 'BeRocket_AJAX_domain') ?></span>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Product order by selector', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input size="30" name="br_filters_options[woocommerce_ordering_class]" type='text' value='<?php echo ! empty($options['woocommerce_ordering_class'])?$options['woocommerce_ordering_class']:BeRocket_AAPF::$defaults['woocommerce_ordering_class']?>'/>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e("Selector for order by form with drop down menu. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain') ?></span>
                        <?php if( apply_filters('br_filters_options-woocommerce_removes_ordering-show', true) ) { ?>
                        <div class="settings-sub-option">
                            <input name="br_filters_options[woocommerce_removes][ordering]" type='checkbox' value='1' <?php if( ! empty($options['woocommerce_removes']['ordering']) ) echo "checked='checked'";?>/>
                            <span style="color:#666666;margin-left:2px;"><?php _e('Removed order by drop down menu. Enable if page doesn\'t have order by drop down menu', 'BeRocket_AJAX_domain') ?></span>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Products pagination selector', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_aapf_pagination_selector" size="30" name="br_filters_options[woocommerce_pagination_class]" type='text' value='<?php echo ! empty($options['woocommerce_pagination_class'])?$options['woocommerce_pagination_class']:BeRocket_AAPF::$defaults['woocommerce_pagination_class']?>'/>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e("Selector for tag that is holding products. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain') ?></span>
                        <?php if( apply_filters('br_filters_options-woocommerce_removes_pagination-show', true) ) { ?>
                        <div class="settings-sub-option">
                            <input name="br_filters_options[woocommerce_removes][pagination]" type='checkbox' value='1' <?php if( ! empty($options['woocommerce_removes']['pagination']) ) echo "checked='checked'";?>/>
                            <span style="color:#666666;margin-left:2px;"><?php _e('Removed pagination. Enable if page doesn\'t have pagination', 'BeRocket_AJAX_domain') ?></span>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_AJAX_domain') ?>" />
            </p>
        </div>
        <div id="seo" class="tab-item <?php if($options['br_opened_tab'] == 'seo' ) echo 'current'; ?>">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('SEO friendly urls', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_seo_friendly_urls" name="br_filters_options[seo_friendly_urls]" type='checkbox' value='1' <?php if( ! empty($options['seo_friendly_urls']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e("If this option is on url will be changed when filter is selected/changed", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Use links in checkbox and radio filters', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_use_links_filters" name="br_filters_options[use_links_filters]" type='checkbox' value='1' <?php if( ! empty($options['use_links_filters']) ) echo "checked='checked'";?>/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Use slug in URL', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_use_slug_in_url" name="br_filters_options[slug_urls]" type='checkbox' value='1' <?php if( ! empty($options['slug_urls']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e("Use attribute slug instead ID", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Nice URL', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_nice_url" name="br_filters_options[nice_urls]" type='checkbox' value='1' <?php if( ! empty($options['nice_urls']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e("Works only with SEO friendly urls. WordPress permalinks must be set to Post name(Custom structure: /%postname%/ )", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Canonicalization', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_canonicalization" name="br_filters_options[canonicalization]" type='checkbox' value='1' <?php if( ! empty($options['canonicalization']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e("Use canonical tag on WooCommerce pages", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('URL decode', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="berocket_uri_decode" name="br_filters_options[seo_uri_decode]" type='checkbox' value='1' <?php if( ! empty($options['seo_uri_decode']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e("Decode all symbols in URL to prevent errors on server side", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_AJAX_domain') ?>" />
            </p>
        </div>
        <div id="advanced" class="tab-item <?php if($options['br_opened_tab'] == 'advanced' ) echo 'current'; ?>">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Add position relative to products holder', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[pos_relative]" type='checkbox' value='1' <?php if( $options['pos_relative'] ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Fix for correct displaying loading block', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('"No Products" class', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input size="30" name="br_filters_options[no_products_class]" type='text' value='<?php echo $options['no_products_class']?>'/>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e('Add class and use it to style "No Products" box', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Turn all filters off', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[filters_turn_off]" type='checkbox' value='1' <?php if( ! empty($options['filters_turn_off']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e("If you want to hide filters without losing current configuration just turn them off", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Show all values', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[show_all_values]" type='checkbox' value='1' <?php if( ! empty($options['show_all_values']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Check if you want to show not used attribute values too', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Display products', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[products_only]" type='checkbox' value='1' <?php if( ! empty($options['products_only']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Display always products when filters selected. Use this when you have categories and subcategories on shop pages, but you want to display products on filtering', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Hide out of stock variable', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="out_of_stock_variable" name="br_filters_options[out_of_stock_variable]" type='checkbox' value='1' <?php if( ! empty($options['out_of_stock_variable']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Hide variable products if variations with selected filters out of stock', 'BeRocket_AJAX_domain') ?></span>
                        <p class="out_of_stock_variable_reload">
                            <input name="br_filters_options[out_of_stock_variable_reload]" type='checkbox' value='1' <?php if( ! empty($options['out_of_stock_variable_reload']) ) echo "checked='checked'";?>/>
                            <span style="color:#666666;margin-left:2px;"><?php _e('Use it for attributes values to display more correct count with option Reload amount of products', 'BeRocket_AJAX_domain') ?></span>
                        </p>
                        <script>
                            function out_of_stock_variable_reload_hide() {
                                if( jQuery('.reload_amount_of_products').prop('checked') && jQuery('.out_of_stock_variable').prop('checked') ) {
                                    jQuery('.out_of_stock_variable_reload').show();
                                } else {
                                    jQuery('.out_of_stock_variable_reload').hide();
                                }
                            }
                            out_of_stock_variable_reload_hide();
                            jQuery('.reload_amount_of_products, .out_of_stock_variable').on('change', out_of_stock_variable_reload_hide);
                        </script>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Template ajax load fix', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input class="load_fix_ajax_request_load" name="br_filters_options[ajax_request_load]" type='checkbox' value='1' <?php if( ! empty($options['ajax_request_load']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Use all plugins on ajax load(can slow down products loading)', 'BeRocket_AJAX_domain') ?></span>
                        <p class="load_fix_use_get_query"<?php if( empty($options['ajax_request_load']) ) echo ' style="display:none;"'; ?>>
                            <input name="br_filters_options[use_get_query]" type='checkbox' value='1' <?php if( ! empty($options['use_get_query']) ) echo "checked='checked'";?>/>
                            <span style="color:#666666;margin-left:2px;"><?php _e('Use GET query instead POST for filtering', 'BeRocket_AJAX_domain') ?></span>
                            <script>
                                jQuery(document).on('change', '.load_fix_ajax_request_load', function() {
                                    if( jQuery(this).prop('checked') ) {
                                        jQuery('.load_fix_use_get_query').show();
                                    } else {
                                        jQuery('.load_fix_use_get_query').hide();
                                    }
                                });
                            </script>
                        </p>
                        <div class="settings-sub-option">
                            <span style="color:#666666;margin-left:2px;"><?php _e('Use', 'BeRocket_AJAX_domain') ?></span>
                            <select name="br_filters_options[ajax_request_load_style]">
                                <option <?php echo ( empty($options['ajax_request_load_style']) ) ? 'selected' : '' ?> value=""><?php _e('PHP', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php echo ( br_get_value_from_array($options, 'ajax_request_load_style') == 'jquery' ) ? 'selected' : '' ?> value="jquery"><?php _e('JavaScript (jQuery)', 'BeRocket_AJAX_domain') ?></option>
                                <option <?php echo ( br_get_value_from_array($options, 'ajax_request_load_style') == 'js' ) ? 'selected' : '' ?> value="js"><?php _e('JavaScript', 'BeRocket_AJAX_domain') ?></option>
                            </select>
                            <span style="color:#666666;margin-left:2px;"><?php _e('for fix', 'BeRocket_AJAX_domain') ?></span>
                            <br>
                            <span style="color:#666666;margin-left:2px;">
                                <?php _e('PHP - loads the full page and cuts products from the page via PHP. Slow down server, but users take only needed information.', 'BeRocket_AJAX_domain') ?>
                            </span>
                            <br>
                            <span style="color:#666666;margin-left:2px;">
                                <?php _e('JavaScript (jQuery) - loads the full page and copy all products from the loaded page to the current page using JQuery. Slow down server and users take the full page. Works good with different themes and plugins.', 'BeRocket_AJAX_domain') ?>
                            </span>
                            <br>
                            <span style="color:#666666;margin-left:2px;">
                                <?php _e('JavaScript - loads the full page and cuts products from the page via JavaScript. Slow down server and users take the full page. Works like PHP method.', 'BeRocket_AJAX_domain') ?>
                            </span>
                        <p class="notice notice-error"><?php _e('Some features work only with JavaScript (jQuery) fix', 'BeRocket_AJAX_domain') ?></p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Slider has a lot of values', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[slider_250_fix]" type='checkbox' value='1' <?php if( ! empty($options['slider_250_fix']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Enable it if slider has more than 250 values. Hierarchical taxonomy can work incorrect with sliders', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Old slider compatibility', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[slider_compatibility]" type='checkbox' value='1' <?php if( ! empty($options['slider_compatibility']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Slow down filtering with sliders. Enable it only if you have some problem with slider filters', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Display styles only for pages with filters', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[styles_in_footer]" type='checkbox' value='1' <?php if( ! empty($options['styles_in_footer']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('On some sites it can cause some visual problem on page loads', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Product per row fix', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input min='1' name="br_filters_options[product_per_row]" type='number' value='<?php echo br_get_value_from_array($options, 'product_per_row')?>'/>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e('Change this only if after filtering count of products per row changes.', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Fix for sites with AJAX', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[ajax_site]" type='checkbox' value='1' <?php if( ! empty($options['ajax_site']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Add JavaScript files to all pages.', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Search page fix', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[search_fix]" type='checkbox' value='1' <?php if( ! empty($options['search_fix']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Disable redirection, when search page return only one product', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Use Tags like custom taxonomy', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[tags_custom]" type='checkbox' value='1' <?php if( ! empty($options['tags_custom']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Try to enable this if widget with tags didn\'t work.', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Disable loading Font Awesome on front end', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[disable_font_awesome]" type='checkbox' value='1' <?php if( ! empty($options['disable_font_awesome']) ) echo "checked='checked'";?>/>
                        <span style="color:#666666;margin-left:2px;"><?php _e('Don\'t loading css file for Font Awesome on site front end. Use this only if you doesn\'t uses Font Awesome icons in widgets or you have Font Awesome in your theme.', 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Use filtered variation link and session', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[use_filtered_variation]" type='checkbox' value='1' <?php if( ! empty($options['use_filtered_variation']) ) echo "checked='checked'";?>/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Use filtered variation only after search', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <input name="br_filters_options[use_filtered_variation_once]" type='checkbox' value='1' <?php if( ! empty($options['use_filtered_variation_once']) ) echo "checked='checked'";?>/>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_AJAX_domain') ?>" />
            </p>
        </div>
        <div id="design" class="tab-item <?php if($options['br_opened_tab'] == 'design' ) echo 'current'; ?>">
            <table class="wp-list-table widefat fixed posts">
                <thead>
                    <tr>
                        <th class="manage-column column-cb check-column" id="cb" scope="col">
                            <label for="cb-select-all-1" class="screen-reader-text"><?php _e('Select All', 'BeRocket_AJAX_domain') ?></label>
                            <input type="checkbox" id="cb-select-all-1" />
                        </th>
                        <th class="manage-column" scope="col"><?php _e('Element', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-family" scope="col"><?php _e('Font Family', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-weight" scope="col"><?php _e('Font-Weight', 'BeRocket_AJAX_domain') ?><br /><small><?php _e('(depends on font)', 'BeRocket_AJAX_domain') ?></small></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Font-Size', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-theme" scope="col"><?php _e('Theme', 'BeRocket_AJAX_domain') ?></th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th class="manage-column column-cb check-column" scope="col">
                            <label for="cb-select-all-2" class="screen-reader-text"><?php _e('Select All', 'BeRocket_AJAX_domain') ?></label>
                            <input type="checkbox" id="cb-select-all-2" />
                        </th>
                        <th class="manage-column" scope="col"><?php _e('Element', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-family" scope="col"><?php _e('Font Family', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-weight" scope="col"><?php _e('Font-Weight', 'BeRocket_AJAX_domain') ?><br /><small><?php _e('(depends on font)', 'BeRocket_AJAX_domain') ?></small></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Font-Size', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-theme" scope="col"><?php _e('Theme', 'BeRocket_AJAX_domain') ?></th>
                    </tr>
                    <tr>
                        <th class="manage-column admin-column-theme" scope="col" colspan="7">
                            <input type="button" value="<?php _e('Set all to theme default', 'BeRocket_AJAX_domain') ?>" class="all_theme_default button">
                            <div style="clear:both;"></div>
                        </th>
                    </tr>
                </tfoot>

                <tbody id="the-list">
                    <?php
                        $i_designable = 1;
                        foreach ( $designables as $key => $designable ) {
                            ?>
                            <tr class="type-page status-publish author-self">
                                <th class="check-column" scope="row">
                                    <label for="cb-select-<?php echo $i_designable ?>" class="screen-reader-text"><?php _e('Select Element', 'BeRocket_AJAX_domain') ?></label>
                                    <input type="checkbox" value="<?php echo $i_designable ?>" name="element[]" id="cb-select-<?php echo $i_designable ?>">
                                    <div class="locked-indicator"></div>
                                </th>
                                <td><?php echo $designable['name'] ?></td>
                                <td class="admin-column-color">
                                    <?php if ( $designable['has']['color'] ) { ?>
                                        <div class="colorpicker_field" data-color="<?php echo ( ! empty($options['styles'][$key]['color']) ) ? $options['styles'][$key]['color'] : '000000' ?>"></div>
                                        <input type="hidden" value="<?php echo ( ! empty($options['styles'][$key]['color']) ) ? $options['styles'][$key]['color'] : '' ?>" name="br_filters_options[styles][<?php echo $key ?>][color]" />
                                        <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                                    <?php } else {
                                        _e('N/A', 'BeRocket_AJAX_domain');
                                    } ?>
                                </td>
                                <td class="admin-column-font-family">
                                    <?php if ( $designable['has']['font_family'] ) { ?>
                                        <select name="br_filters_options[styles][<?php echo $key ?>][font_family]">
                                            <option value=""><?php _e('Theme Default', 'BeRocket_AJAX_domain') ?></option>
                                            <?php foreach( $fonts_list as $font ) { ?>
                                                <option <?php echo ( br_get_value_from_array($options, array('styles', $key, 'font_family')) == $font ) ? 'selected' : '' ?>><?php echo $font?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } else {
                                        _e('N/A', 'BeRocket_AJAX_domain');
                                    } ?>
                                </td>
                                <td class="admin-column-font-weight">
                                    <?php if ( $designable['has']['bold'] ) {
                                        if( empty( $options['styles'][$key]['bold'] ) ) {
                                            $options['styles'][$key]['bold'] = '';
                                        } ?>
                                        <select name="br_filters_options[styles][<?php echo $key ?>][bold]">
                                            <option value=""><?php _e('Theme Default', 'BeRocket_AJAX_domain') ?></option>
                                            <?php
                                            $font_weight = array(
                                                'Textual Values' => array(
                                                    'lighter'   => 'light',
                                                    'normal'    => 'normal',
                                                    'bold'      => 'bold',
                                                    'bolder'    => 'bolder',
                                                ),
                                                'Numeric Values' => array(
                                                    '100' => '100',
                                                    '200' => '200',
                                                    '300' => '300',
                                                    '400' => '400',
                                                    '500' => '500',
                                                    '600' => '600',
                                                    '700' => '700',
                                                    '800' => '800',
                                                    '900' => '900',
                                                ),
                                            );
                                            $fw_current = br_get_value_from_array($options, array('styles', $key, 'bold'));
                                            foreach($font_weight as $fm_optgroup => $fw_options) {
                                                echo '<optgroup label="', $fm_optgroup, '">';
                                                foreach($fw_options as $fw_key => $fw_value) {
                                                    echo '<option', ( $fw_current == $fw_key ? ' selected' : '' ), ' value="', $fw_key, '">', $fw_value, '</option>';
                                                }
                                                echo '</optgroup>';
                                            }
                                            ?>
                                        </select>
                                    <?php } else {
                                        _e('N/A', 'BeRocket_AJAX_domain');
                                    } ?>
                                </td>
                                <td class="admin-column-font-size">
                                    <?php if ( ! empty($designable['has']['font_size']) ) { ?>
                                        <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles][<?php echo $key ?>][font_size]" value="<?php echo br_get_value_from_array($options, array('styles', $key, 'font_size')) ?>" />
                                    <?php } else {
                                        _e('N/A', 'BeRocket_AJAX_domain');
                                    } ?>
                                </td>
                               <td class="admin-column-theme">
                                    <?php if ( $designable['has']['theme'] ) { ?>
                                        <select name="br_filters_options[styles][<?php echo $key ?>][theme]">
                                            <option value=""><?php _e('Without Theme', 'BeRocket_AJAX_domain') ?></option>
                                            <?php if ( $key != 'selectbox' ) { ?>
                                                <option value="aapf_grey1" <?php echo ( empty($options['styles'][$key]['theme']) && $options['styles'][$key]['theme'] == 'aapf_grey1' ) ? 'selected' : '' ?>>Grey</option>
                                            <?php } ?>
                                            <?php if ( $key != 'slider' and $key != 'checkbox_radio' ) { ?>
                                            <option value="aapf_grey2" <?php echo ( ! empty($options['styles'][$key]['theme']) && $options['styles'][$key]['theme'] == 'aapf_grey2' ) ? 'selected' : '' ?>>Grey 2</option>
                                            <?php } ?>
                                        </select>
                                    <?php } else {
                                        _e('N/A', 'BeRocket_AJAX_domain');
                                    } ?>
                                </td>
                            </tr>
                            <?php
                            $i_designable++;
                        }
                    ?>
                </tbody>
            </table>
            <table class="wp-list-table widefat fixed posts">
                <thead>
                    <tr><th colspan="9" style="text-align: center; font-size: 2em;"><?php _e('Checkbox / Radio', 'BeRocket_AJAX_domain') ?></th></tr>
                    <tr>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Element', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Border color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Border width', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Border radius', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Size', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Font color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Background', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Icon', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Theme', 'BeRocket_AJAX_domain') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="br_checkbox_radio_settings">
                        <td><?php _e('Checkbox', 'BeRocket_AJAX_domain') ?></td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'bcolor'), '000000') ?>"></div>
                            <input class="br_border_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'bcolor')) ?>" name="br_filters_options[styles_input][checkbox][bcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_width_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][checkbox][bwidth]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'bwidth')); ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_radius_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][checkbox][bradius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'bradius')); ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_size_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][checkbox][fontsize]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'fontsize')); ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'fcolor'), '000000') ?>"></div>
                            <input class="br_font_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'fcolor')) ?>" name="br_filters_options[styles_input][checkbox][fcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'backcolor'), '000000') ?>"></div>
                            <input class="br_background_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'checkbox', 'backcolor')) ?>" name="br_filters_options[styles_input][checkbox][backcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <select name="br_filters_options[styles_input][checkbox][icon]" class="fontawesome br_icon_set">
                                <option value=""<?php if ( empty($options['styles_input']['checkbox']['icon']) ) echo ' selected' ?>>NONE</option>
                                <?php $radion_icon = array( 'f00c', '2713', 'f00d', 'f067', 'f055', 'f0fe', 'f14a', 'f058' );
                                foreach( $radion_icon as $r_icon ) {
                                    echo '<option value="'.$r_icon.'"'.( br_get_value_from_array($options, array('styles_input', 'checkbox', 'icon')) == $r_icon ? ' selected' : '' ).'>&#x'.$r_icon.';</option>';
                                }?>
                            </select>
                        </td>
                        <td class="admin-column-color">
                            <select name="br_filters_options[styles_input][checkbox][theme]" class="br_theme_set_select">
                                <option value=""<?php if ( empty($options['styles_input']['checkbox']['theme']) ) echo ' selected' ?>>NONE</option>
                                <?php
                                $checkbox_theme_current = br_get_value_from_array($options, array('styles_input', 'checkbox', 'theme'));
                                $checkbox_themes = array(
                                    'black_1' => array(
                                        'name'          => 'Black 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => '333333',
                                        'background'    => 'bbbbbb',
                                        'icon'          => 'f00c',
                                    ),
                                    'black_2' => array(
                                        'name'          => 'Black 2',
                                        'border_color'  => '333333',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '333333',
                                        'background'    => '',
                                        'icon'          => '2713',
                                    ),
                                    'black_3' => array(
                                        'name'          => 'Black 3',
                                        'border_color'  => '333333',
                                        'border_width'  => '2',
                                        'border_radius' => '50',
                                        'size'          => '',
                                        'font_color'    => '333333',
                                        'background'    => '',
                                        'icon'          => 'f058',
                                    ),
                                    'black_4' => array(
                                        'name'          => 'Black 4',
                                        'border_color'  => '333333',
                                        'border_width'  => '2',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '333333',
                                        'background'    => '',
                                        'icon'          => 'f14a',
                                    ),
                                    'white_1' => array(
                                        'name'          => 'White 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => 'dddddd',
                                        'background'    => '333333',
                                        'icon'          => 'f00c',
                                    ),
                                    'white_2' => array(
                                        'name'          => 'White 2',
                                        'border_color'  => 'dddddd',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => 'dddddd',
                                        'background'    => '',
                                        'icon'          => '2713',
                                    ),
                                    'white_3' => array(
                                        'name'          => 'White 3',
                                        'border_color'  => 'dddddd',
                                        'border_width'  => '2',
                                        'border_radius' => '50',
                                        'size'          => '',
                                        'font_color'    => 'dddddd',
                                        'background'    => '',
                                        'icon'          => 'f058',
                                    ),
                                    'white_4' => array(
                                        'name'          => 'White 4',
                                        'border_color'  => 'dddddd',
                                        'border_width'  => '2',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => 'dddddd',
                                        'background'    => '',
                                        'icon'          => 'f14a',
                                    ),
                                    'red_1' => array(
                                        'name'          => 'Red 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => 'dd3333',
                                        'background'    => '333333',
                                        'icon'          => 'f00c',
                                    ),
                                    'red_2' => array(
                                        'name'          => 'Red 2',
                                        'border_color'  => 'dd3333',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => 'dd3333',
                                        'background'    => '',
                                        'icon'          => '2713',
                                    ),
                                    'red_3' => array(
                                        'name'          => 'Red 3',
                                        'border_color'  => 'dd3333',
                                        'border_width'  => '2',
                                        'border_radius' => '50',
                                        'size'          => '',
                                        'font_color'    => 'dd3333',
                                        'background'    => '',
                                        'icon'          => 'f058',
                                    ),
                                    'red_4' => array(
                                        'name'          => 'Red 4',
                                        'border_color'  => 'dd3333',
                                        'border_width'  => '2',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => 'dd3333',
                                        'background'    => '',
                                        'icon'          => 'f14a',
                                    ),
                                    'green_1' => array(
                                        'name'          => 'Green 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => '33dd33',
                                        'background'    => '333333',
                                        'icon'          => 'f00c',
                                    ),
                                    'green_2' => array(
                                        'name'          => 'Green 2',
                                        'border_color'  => '33dd33',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '33dd33',
                                        'background'    => '',
                                        'icon'          => '2713',
                                    ),
                                    'green_3' => array(
                                        'name'          => 'Green 3',
                                        'border_color'  => '33dd33',
                                        'border_width'  => '2',
                                        'border_radius' => '50',
                                        'size'          => '',
                                        'font_color'    => '33dd33',
                                        'background'    => '',
                                        'icon'          => 'f058',
                                    ),
                                    'green_4' => array(
                                        'name'          => 'Green 4',
                                        'border_color'  => '33dd33',
                                        'border_width'  => '2',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '33dd33',
                                        'background'    => '',
                                        'icon'          => 'f14a',
                                    ),
                                    'blue_1' => array(
                                        'name'          => 'Blue 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => '3333dd',
                                        'background'    => '333333',
                                        'icon'          => 'f00c',
                                    ),
                                    'blue_2' => array(
                                        'name'          => 'Blue 2',
                                        'border_color'  => '3333dd',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '3333dd',
                                        'background'    => '',
                                        'icon'          => '2713',
                                    ),
                                    'blue_3' => array(
                                        'name'          => 'Blue 3',
                                        'border_color'  => '3333dd',
                                        'border_width'  => '2',
                                        'border_radius' => '50',
                                        'size'          => '',
                                        'font_color'    => '3333dd',
                                        'background'    => '',
                                        'icon'          => 'f058',
                                    ),
                                    'blue_4' => array(
                                        'name'          => 'Blue 4',
                                        'border_color'  => '3333dd',
                                        'border_width'  => '2',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '3333dd',
                                        'background'    => '',
                                        'icon'          => 'f14a',
                                    ),
                                );
                                foreach($checkbox_themes as $chth_key => $chth_data) {
                                    echo '<option value="', $chth_key, '"';
                                    foreach($chth_data as $chth_data_key => $chth_data_val) {
                                        echo ' data-', $chth_data_key, '="', $chth_data_val, '"';
                                    }
                                    if( $checkbox_theme_current == $chth_key ) {
                                        echo ' selected';
                                    }
                                    echo '>', $chth_data['name'], '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="br_checkbox_radio_settings">
                        <td><?php _e('Radio', 'BeRocket_AJAX_domain') ?></td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'bcolor'), '000000') ?>"></div>
                            <input class="br_border_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'bcolor')) ?>" name="br_filters_options[styles_input][radio][bcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_width_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][radio][bwidth]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'bwidth')) ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_radius_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][radio][bradius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'bradius')) ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_size_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][radio][fontsize]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'fontsize')) ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'fcolor'), '000000') ?>"></div>
                            <input class="br_font_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'fcolor')) ?>" name="br_filters_options[styles_input][radio][fcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'backcolor'), '000000') ?>"></div>
                            <input class="br_background_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'radio', 'backcolor')) ?>" name="br_filters_options[styles_input][radio][backcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <select name="br_filters_options[styles_input][radio][icon]" class="fontawesome br_icon_set">
                                <option value=""<?php if ( empty($options['styles_input']['radio']['icon']) ) echo ' selected' ?>>NONE</option>
                                <?php $radion_icon = array( 'f111', '2022', 'f10c', 'f192', 'f0c8', 'f055', 'f0fe', 'f14a', 'f058' );
                                foreach( $radion_icon as $r_icon ) {
                                    echo '<option value="'.$r_icon.'"'.( br_get_value_from_array($options, array('styles_input', 'radio', 'icon')) == $r_icon ? ' selected' : '' ).'>&#x'.$r_icon.';</option>';
                                }?>
                            </select>
                        </td>
                        <td class="admin-column-color">
                            <select name="br_filters_options[styles_input][radio][theme]" class="br_theme_set_select">
                                <option value=""<?php if ( empty($options['styles_input']['radio']['theme']) ) echo ' selected' ?>>NONE</option>
                                <?php
                                $radio_theme_current = br_get_value_from_array($options, array('styles_input', 'checkbox', 'theme'));
                                $radio_themes = array(
                                    'black_1' => array(
                                        'name'          => 'Black 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => '333333',
                                        'background'    => 'bbbbbb',
                                        'icon'          => 'f111',
                                    ),
                                    'black_2' => array(
                                        'name'          => 'Black 2',
                                        'border_color'  => '333333',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '333333',
                                        'background'    => '',
                                        'icon'          => 'f0c8',
                                    ),
                                    'black_3' => array(
                                        'name'          => 'Black 3',
                                        'border_color'  => '333333',
                                        'border_width'  => '2',
                                        'border_radius' => '',
                                        'size'          => '10',
                                        'font_color'    => '333333',
                                        'background'    => '',
                                        'icon'          => 'f055',
                                    ),
                                    'white_1' => array(
                                        'name'          => 'White 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => 'dddddd',
                                        'background'    => '333333',
                                        'icon'          => 'f111',
                                    ),
                                    'white_2' => array(
                                        'name'          => 'White 2',
                                        'border_color'  => 'dddddd',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => 'dddddd',
                                        'background'    => '',
                                        'icon'          => 'f0c8',
                                    ),
                                    'white_3' => array(
                                        'name'          => 'White 3',
                                        'border_color'  => 'dddddd',
                                        'border_width'  => '2',
                                        'border_radius' => '',
                                        'size'          => '10',
                                        'font_color'    => 'dddddd',
                                        'background'    => '',
                                        'icon'          => 'f055',
                                    ),
                                    'red_1' => array(
                                        'name'          => 'Red 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => 'dd3333',
                                        'background'    => '333333',
                                        'icon'          => 'f111',
                                    ),
                                    'red_2' => array(
                                        'name'          => 'Red 2',
                                        'border_color'  => 'dd3333',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => 'dd3333',
                                        'background'    => '',
                                        'icon'          => 'f0c8',
                                    ),
                                    'red_3' => array(
                                        'name'          => 'Red 3',
                                        'border_color'  => 'dd3333',
                                        'border_width'  => '2',
                                        'border_radius' => '',
                                        'size'          => '10',
                                        'font_color'    => 'dd3333',
                                        'background'    => '',
                                        'icon'          => 'f055',
                                    ),
                                    'green_1' => array(
                                        'name'          => 'Green 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => '33dd33',
                                        'background'    => '333333',
                                        'icon'          => 'f111',
                                    ),
                                    'green_2' => array(
                                        'name'          => 'Green 2',
                                        'border_color'  => '33dd33',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '33dd33',
                                        'background'    => '',
                                        'icon'          => 'f0c8',
                                    ),
                                    'green_3' => array(
                                        'name'          => 'Green 3',
                                        'border_color'  => '33dd33',
                                        'border_width'  => '2',
                                        'border_radius' => '',
                                        'size'          => '10',
                                        'font_color'    => '33dd33',
                                        'background'    => '',
                                        'icon'          => 'f055',
                                    ),
                                    'blue_1' => array(
                                        'name'          => 'Blue 1',
                                        'border_color'  => '',
                                        'border_width'  => '0',
                                        'border_radius' => '5',
                                        'size'          => '',
                                        'font_color'    => '3333dd',
                                        'background'    => '333333',
                                        'icon'          => 'f111',
                                    ),
                                    'blue_2' => array(
                                        'name'          => 'Blue 2',
                                        'border_color'  => '3333dd',
                                        'border_width'  => '1',
                                        'border_radius' => '2',
                                        'size'          => '',
                                        'font_color'    => '3333dd',
                                        'background'    => '',
                                        'icon'          => 'f0c8',
                                    ),
                                    'blue_3' => array(
                                        'name'          => 'Blue 3',
                                        'border_color'  => '3333dd',
                                        'border_width'  => '2',
                                        'border_radius' => '',
                                        'size'          => '10',
                                        'font_color'    => '3333dd',
                                        'background'    => '',
                                        'icon'          => 'f055',
                                    ),
                                );
                                foreach($radio_themes as $rth_key => $rth_data) {
                                    echo '<option value="', $rth_key, '"';
                                    foreach($rth_data as $rth_data_key => $rth_data_val) {
                                        echo ' data-', $rth_data_key, '="', $rth_data_val, '"';
                                    }
                                    if( $checkbox_theme_current == $rth_key ) {
                                        echo ' selected';
                                    }
                                    echo '>', $rth_data['name'], '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="manage-column admin-column-theme" scope="col" colspan="9">
                            <input type="button" value="<?php _e('Set all to theme default', 'BeRocket_AJAX_domain') ?>" class="all_theme_default button">
                            <div style="clear:both;"></div>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <table class="wp-list-table widefat fixed posts">
                <thead>
                    <tr><th colspan="10" style="text-align: center; font-size: 2em;"><?php _e('Slider', 'BeRocket_AJAX_domain') ?></th></tr>
                    <tr>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Line color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Back line color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Line height', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Line border color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Line border width', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Button size', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Button color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Button border color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Button border width', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Button border radius', 'BeRocket_AJAX_domain') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'line_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'line_color')) ?>" name="br_filters_options[styles_input][slider][line_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'back_line_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'back_line_color')) ?>" name="br_filters_options[styles_input][slider][back_line_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][slider][line_height]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'line_height')) ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'line_border_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'line_border_color')) ?>" name="br_filters_options[styles_input][slider][line_border_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][slider][line_border_width]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'line_border_width')) ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][slider][button_size]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'button_size')) ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'button_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'button_color')) ?>" name="br_filters_options[styles_input][slider][button_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'button_border_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'button_border_color')) ?>" name="br_filters_options[styles_input][slider][button_border_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][slider][button_border_width]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'button_border_width')); ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][slider][button_border_radius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'slider', 'button_border_radius')); ?>" />
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="manage-column admin-column-theme" scope="col" colspan="10">
                            <input type="button" value="<?php _e('Set all to theme default', 'BeRocket_AJAX_domain') ?>" class="all_theme_default button">
                            <div style="clear:both;"></div>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <table class="wp-list-table widefat fixed posts">
                <thead>
                    <tr><th colspan="10" style="text-align: center; font-size: 2em;"><?php _e('Product count description before filtering with Update button', 'BeRocket_AJAX_domain') ?></th></tr>
                    <tr>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Background color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Border color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Font size', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Font color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Show button font size', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Show button font color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Show button font color on mouse over', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Close button size', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Close button font color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Close button font color on mouse over', 'BeRocket_AJAX_domain') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'back_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'back_color')) ?>" name="br_filters_options[styles_input][pc_ub][back_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'border_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'border_color')) ?>" name="br_filters_options[styles_input][pc_ub][border_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][pc_ub][font_size]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'font_size')); ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'font_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'font_color')) ?>" name="br_filters_options[styles_input][pc_ub][font_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][pc_ub][show_font_size]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'show_font_size')); ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'show_font_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'show_font_color')) ?>" name="br_filters_options[styles_input][pc_ub][show_font_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'show_font_color_hover'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'show_font_color_hover')) ?>" name="br_filters_options[styles_input][pc_ub][show_font_color_hover]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][pc_ub][close_size]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'close_size')); ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'close_font_color'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'close_font_color')) ?>" name="br_filters_options[styles_input][pc_ub][close_font_color]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'close_font_color_hover'), '000000') ?>"></div>
                            <input type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'pc_ub', 'close_font_color_hover')) ?>" name="br_filters_options[styles_input][pc_ub][close_font_color_hover]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="manage-column admin-column-theme" scope="col" colspan="10">
                            <input type="button" value="<?php _e('Set all to theme default', 'BeRocket_AJAX_domain') ?>" class="all_theme_default button">
                            <div style="clear:both;"></div>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <table class="wp-list-table widefat fixed posts">
                <thead>
                    <tr><th colspan="7" style="text-align: center; font-size: 2em;"><?php _e('Show title only Styles', 'BeRocket_AJAX_domain') ?></th></tr>
                    <tr>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Element', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Border color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Border width', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Border radius', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-font-size" scope="col"><?php _e('Size', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Font color', 'BeRocket_AJAX_domain') ?></th>
                        <th class="manage-column admin-column-color" scope="col"><?php _e('Background', 'BeRocket_AJAX_domain') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="br_onlyTitle_title_radio_settings">
                        <td><?php _e('Title', 'BeRocket_AJAX_domain') ?></td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'bcolor'), '000000') ?>"></div>
                            <input class="br_border_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'bcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_title][bcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_width_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_title][bwidth]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'bwidth')); ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_radius_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_title][bradius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'bradius')); ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_size_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_title][fontsize]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'fontsize')); ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'fcolor'), '000000') ?>"></div>
                            <input class="br_font_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'fcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_title][fcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'backcolor'), '000000') ?>"></div>
                            <input class="br_background_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'backcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_title][backcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                    </tr>
                    <tr class="br_onlyTitle_title_radio_settings">
                        <td><?php _e('Title opened', 'BeRocket_AJAX_domain') ?></td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'bcolor'), '000000') ?>"></div>
                            <input class="br_border_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'bcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][bcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_width_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][bwidth]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'bwidth')); ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_radius_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][bradius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'bradius')); ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_size_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][fontsize]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'fontsize')); ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'fcolor'), '000000') ?>"></div>
                            <input class="br_font_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'fcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][fcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'backcolor'), '000000') ?>"></div>
                            <input class="br_background_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'backcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][backcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                    </tr>
                    <tr class="br_onlyTitle_filter_radio_settings">
                        <td><?php _e('Filter', 'BeRocket_AJAX_domain') ?></td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'bcolor'), '000000') ?>"></div>
                            <input class="br_border_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'bcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_filter][bcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_width_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_filter][bwidth]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'bwidth')) ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_border_radius_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_filter][bradius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'bradius')) ?>" />
                        </td>
                        <td class="admin-column-font-size">
                            <input class="br_size_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_filter][fontsize]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'fontsize')) ?>" />
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'fcolor'), '000000') ?>"></div>
                            <input class="br_font_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'fcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_filter][fcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                        <td class="admin-column-color">
                            <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'backcolor'), '000000') ?>"></div>
                            <input class="br_background_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'backcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_filter][backcolor]" />
                            <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button">
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="manage-column admin-column-theme" scope="col" colspan="7">
                            <input type="button" value="<?php _e('Set all to theme default', 'BeRocket_AJAX_domain') ?>" class="all_theme_default button">
                            <div style="clear:both;"></div>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Loading products icon', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <?php echo berocket_font_select_upload('', 'br_filters_options_ajax_load_icon', 'br_filters_options[ajax_load_icon]', br_get_value_from_array($options, 'ajax_load_icon'), false); ?>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Text at load icon', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <span><?php _e('Above:', 'BeRocket_AJAX_domain') ?> </span><input name="br_filters_options[ajax_load_text][top]" type='text' value='<?php echo br_get_value_from_array($options, array('ajax_load_text', 'top',)); ?>'/>
                    </td>
                    <td>
                        <span><?php _e('Under:', 'BeRocket_AJAX_domain') ?> </span><input name="br_filters_options[ajax_load_text][bottom]" type='text' value='<?php echo br_get_value_from_array($options, array('ajax_load_text', 'bottom')); ?>'/>
                    </td>
                    <td>
                        <span><?php _e('Before:', 'BeRocket_AJAX_domain') ?> </span><input name="br_filters_options[ajax_load_text][left]" type='text' value='<?php echo br_get_value_from_array($options, array('ajax_load_text', 'left')); ?>'/>
                    </td>
                    <td>
                        <span><?php _e('After:', 'BeRocket_AJAX_domain') ?> </span><input name="br_filters_options[ajax_load_text][right]" type='text' value='<?php echo br_get_value_from_array($options, array('ajax_load_text', 'right')); ?>'/>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Description show and hide', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <span><?php _e('Show on:', 'BeRocket_AJAX_domain') ?> </span>
                        <select name="br_filters_options[description][show]">
                            <option <?php echo ( $options['description']['show'] == 'click' ) ? 'selected' : '' ?> value="click"><?php _e('Click', 'BeRocket_AJAX_domain') ?></option>
                            <option <?php echo ( $options['description']['show'] == 'hover' ) ? 'selected' : '' ?> value="hover"><?php _e('Mouse over icon', 'BeRocket_AJAX_domain') ?></option>
                        </select>
                    </td>
                    <td>
                        <span><?php _e('Hide on:', 'BeRocket_AJAX_domain') ?> </span>
                        <select name="br_filters_options[description][hide]">
                            <option <?php echo ( $options['description']['hide'] == 'click' ) ? 'selected' : '' ?> value="click"><?php _e('Click anywhere', 'BeRocket_AJAX_domain') ?></option>
                            <option <?php echo ( $options['description']['hide'] == 'mouseleave' ) ? 'selected' : '' ?> value="mouseleave"><?php _e('Mouse out of icon', 'BeRocket_AJAX_domain') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Product count style', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <select name="br_filters_options[styles_input][product_count]">
                            <option <?php echo ( $options['styles_input']['product_count'] ) ? 'selected' : '' ?> value=""><?php _e('4', 'BeRocket_AJAX_domain') ?></option>
                            <option <?php echo ( $options['styles_input']['product_count'] == 'round' ) ? 'selected' : '' ?> value="round"><?php _e('(4)', 'BeRocket_AJAX_domain') ?></option>
                            <option <?php echo ( $options['styles_input']['product_count'] == 'quad' ) ? 'selected' : '' ?> value="quad"><?php _e('[4]', 'BeRocket_AJAX_domain') ?></option>
                        </select>
                    </td>
                    <td>
                        <span><?php _e('Position:', 'BeRocket_AJAX_domain') ?> </span>
                        <select name="br_filters_options[styles_input][product_count_position]">
                            <option <?php echo ( $options['styles_input']['product_count_position'] ) ? 'selected' : '' ?> value=""><?php _e('Normal', 'BeRocket_AJAX_domain') ?></option>
                            <option <?php echo ( $options['styles_input']['product_count_position'] == 'right' ) ? 'selected' : '' ?> value="right"><?php _e('Right', 'BeRocket_AJAX_domain') ?></option>
                            <option <?php echo ( $options['styles_input']['product_count_position'] == 'right2em' ) ? 'selected' : '' ?> value="right2em"><?php _e('Right from name', 'BeRocket_AJAX_domain') ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_AJAX_domain') ?>" />
            </p>
        </div>
        <div id="javascript" class="tab-item <?php if($options['br_opened_tab'] == 'javascript' ) echo 'current'; ?>">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Before Update:', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <textarea style="min-width: 500px; height: 100px;" name="br_filters_options[user_func][before_update]"><?php echo br_get_value_from_array($options, array('user_func', 'before_update')) ?></textarea>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e("If you want to add own actions on filter activation, eg: alert('1');", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('On Update:', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <textarea style="min-width: 500px; height: 100px;" name="br_filters_options[user_func][on_update]"><?php echo br_get_value_from_array($options, array('user_func', 'on_update')) ?></textarea>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e("If you want to add own actions right on products update. You can manipulate data here, try: data.products = 'Ha!';", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('After Update:', 'BeRocket_AJAX_domain') ?></th>
                    <td>
                        <textarea style="min-width: 500px; height: 100px;" name="br_filters_options[user_func][after_update]"><?php echo br_get_value_from_array($options, array('user_func', 'after_update')) ?></textarea>
                        <br />
                        <span style="color:#666666;margin-left:2px;"><?php _e("If you want to add own actions after products updated, eg: alert('1');", 'BeRocket_AJAX_domain') ?></span>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr>
                    <th colspan="2"><?php _e('User custom CSS style:', 'BeRocket_AJAX_domain') ?></th>
                </tr>
                <tr>
                    <td style="width:600px;">
                        <textarea style="width: 100%; min-height: 400px; height:900px" name="br_filters_options[user_custom_css]"><?php echo br_get_value_from_array($options, 'user_custom_css') ?></textarea>
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
            <input type="hidden" id="br_opened_tab" name="br_filters_options[br_opened_tab]" value="<?php echo $options['br_opened_tab'] ?>">
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_AJAX_domain') ?>" />
            </p>
        </div>
    </form>
</div>
<?php
$feature_list = array();
@ include 'settings_footer.php';
?>
