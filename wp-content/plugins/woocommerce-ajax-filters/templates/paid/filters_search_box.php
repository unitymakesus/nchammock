<div>
    <label>
        <input class="berocket_admin_search_box_enable" type="checkbox" name="<?php echo $post_name; ?>[search_box]" value="1"<?php echo (empty($filters['search_box']) ? '' : ' checked') ?>>
        <?php _e('Use Group as Search Box', 'BeRocket_AJAX_domain'); ?>
    </label>
</div>
<div class="berocket_aapf_admin_search_box"<?php if( empty($filters['search_box']) ) echo ' style="display: none;"'; ?>>
    <div>
        <label><?php _e('URL to search', 'BeRocket_AJAX_domain') ?></label>
        <select name="<?php echo $post_name; ?>[search_box_link_type]" class="berocket_search_link_select br_select_menu_left">
            <option value="shop_page"<?php if ($filters['search_box_link_type'] == 'shop_page' ) echo ' selected'; ?>><?php _e('Shop page', 'BeRocket_AJAX_domain') ?></option>
            <option value="category"<?php if ($filters['search_box_link_type'] == 'category' ) echo ' selected'; ?>><?php _e('Category page', 'BeRocket_AJAX_domain') ?></option>
            <option value="url"<?php if ($filters['search_box_link_type'] == 'url' ) echo ' selected'; ?>><?php _e('URL', 'BeRocket_AJAX_domain') ?></option>
        </select>
    </div>
    <div class="berocket_search_link berocket_search_link_category"<?php if( $filters['search_box_link_type'] != 'category' ) echo ' style="display:none;"'; ?>>
        <label><?php _e('Category', 'BeRocket_AJAX_domain') ?></label>
        <select class="br_select_menu_left" name="<?php echo $post_name; ?>[search_box_category]">
        <?php 
        $filters['search_box_category'] = ( empty($filters['search_box_category']) ? '' : urldecode($filters['search_box_category']) );
        foreach( $categories as $category ){
            echo '<option value="'.$category->slug.'"'.($filters['search_box_category'] == $category->slug ? ' selected' : '').'>'.$category->name.'</option>';
        } ?>
        </select>
    </div>
    <div class="berocket_search_link berocket_search_link_url"<?php if( $filters['search_box_link_type'] != 'url' ) echo ' style="display:none;"'; ?>>
        <label><?php _e('URL for search', 'BeRocket_AJAX_domain') ?></label>
        <input class="br_admin_full_size" id="search_box_url" name="<?php echo $post_name; ?>[search_box_url]" type="text" value="<?php echo $filters['search_box_url']; ?>">
    </div>
    <div class="br_accordion">
        <h3><?php _e('Styles', 'BeRocket_AJAX_domain') ?></h3>
        <div>
            <div>
                <label><?php _e('Elements position', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo $post_name; ?>[search_box_style][position]">
                    <option value="vertical"<?php if( br_get_value_from_array($filters, array('search_box_style', 'position')) == 'vertical' ) echo ' selected'; ?>><?php _e('Vertical', 'BeRocket_AJAX_domain') ?></option>
                    <option value="horizontal"<?php if( br_get_value_from_array($filters, array('search_box_style', 'position')) == 'horizontal' ) echo ' selected'; ?>><?php _e('Horizontal', 'BeRocket_AJAX_domain') ?></option>
                </select>
            </div>
            <div>
                <label><?php _e('Search button position', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo $post_name; ?>[search_box_style][search_position]">
                    <option value="before"<?php if( br_get_value_from_array($filters, array('search_box_style', 'search_position')) == 'before' ) echo ' selected'; ?>><?php _e('Before', 'BeRocket_AJAX_domain') ?></option>
                    <option value="after"<?php if( br_get_value_from_array($filters, array('search_box_style', 'search_position')) == 'after' ) echo ' selected'; ?>><?php _e('After', 'BeRocket_AJAX_domain') ?></option>
                    <option value="before_after"<?php if( br_get_value_from_array($filters, array('search_box_style', 'search_position')) == 'before_after' ) echo ' selected'; ?>><?php _e('Before and after', 'BeRocket_AJAX_domain') ?></option>
                </select>
            </div>
            <div>
                <label><?php _e('Search button text', 'BeRocket_AJAX_domain') ?></label>
                <input type="text" class="br_admin_full_size" value="<?php echo br_get_value_from_array($filters, array('search_box_style', 'search_text')); ?>" name="<?php echo $post_name; ?>[search_box_style][search_text]">
            </div>
            <div>
                <label><?php _e('Background color', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($filters, array('search_box_style', 'background'), '000000'); ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($filters, array('search_box_style', 'background')) ?>" name="<?php echo $post_name; ?>[search_box_style][background]">
            </div>
            <div>
                <label><?php _e('Background transparency', 'BeRocket_AJAX_domain') ?></label>
                <select class="br_select_menu_left" name="<?php echo $post_name; ?>[search_box_style][back_opacity]">
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
                        ( (br_get_value_from_array($filters, array('search_box_style', 'back_opacity')) == $key) ? ' selected' : '' ),
                        '>', $value, '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label><?php _e('Button background color', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($filters, array('search_box_style', 'button_background'), '000000'); ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($filters, array('search_box_style', 'button_background')) ?>" name="<?php echo $post_name; ?>[search_box_style][button_background]">
            </div>
            <div>
                <label><?php _e('Button background color on mouse over', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($filters, array('search_box_style', 'button_background_over'), '000000'); ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($filters, array('search_box_style', 'button_background_over')) ?>" name="<?php echo $post_name; ?>[search_box_style][button_background_over]">
            </div>
            <div>
                <label><?php _e('Button text color', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($filters, array('search_box_style', 'text_color'), '000000') ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($filters, array('search_box_style', 'text_color')) ?>" name="<?php echo $post_name; ?>[search_box_style][text_color]">
            </div>
            <div>
                <label><?php _e('Button text color on mouse over', 'BeRocket_AJAX_domain') ?></label>
                <div class="colorpicker_field" data-color="<?php echo br_get_value_from_array($filters, array('search_box_style', 'text_color_over'), '000000') ?>"></div>
                <input type="hidden" value="<?php echo br_get_value_from_array($filters, array('search_box_style', 'text_color_over')) ?>" name="<?php echo $post_name; ?>[search_box_style][text_color_over]">
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).on('change', '.berocket_admin_search_box_enable', function(event) {
    if( jQuery(this).prop('checked') ) {
        jQuery('.berocket_aapf_admin_search_box').show();
    } else {
        jQuery('.berocket_aapf_admin_search_box').hide();
    }
})
</script>
