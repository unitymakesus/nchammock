
        <tr>
            <th><?php _e('Show filters above products', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <?php $options = BeRocket_AAPF::get_aapf_option();
                $elements_above_products = br_get_value_from_array($options, 'elements_above_products');
                if( ! is_array($elements_above_products) ) {
                    $elements_above_products = array();
                }
                global $pagenow;
                $post_id = 0;
                if( ! in_array( $pagenow, array( 'post-new.php' ) ) ) {
                    $post_id = $post->ID;
                }
                 ?>
                <input type="checkbox" name="br_filter_group_show_above" value="1"<?php if(in_array($post_id, $elements_above_products)) echo ' checked'; ?>>
            </td>
        </tr>
        <tr>
            <th><?php _e('Display filters in line', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <input class="berocket_display_inline_option" type="checkbox" name="<?php echo $post_name; ?>[display_inline]" value="1"<?php if(! empty($filters['display_inline']) ) echo ' checked'; ?>>
            </td>
        </tr>
        <tr class="berocket_display_inline_count">
            <th><?php _e('Display filters in line max count', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <select name="<?php echo $post_name; ?>[display_inline_count]">
                    <option value=""><?php _e('Default', 'BeRocket_AJAX_domain'); ?></option>
                    <option value="1"<?php if( br_get_value_from_array($filters, 'display_inline_count') == 1 ) echo ' selected'; ?>>1</option>
                    <option value="2"<?php if( br_get_value_from_array($filters, 'display_inline_count') == 2 ) echo ' selected'; ?>>2</option>
                    <option value="3"<?php if( br_get_value_from_array($filters, 'display_inline_count') == 3 ) echo ' selected'; ?>>3</option>
                    <option value="4"<?php if( br_get_value_from_array($filters, 'display_inline_count') == 4 ) echo ' selected'; ?>>4</option>
                    <option value="5"<?php if( br_get_value_from_array($filters, 'display_inline_count') == 5 ) echo ' selected'; ?>>5</option>
                    <option value="6"<?php if( br_get_value_from_array($filters, 'display_inline_count') == 6 ) echo ' selected'; ?>>6</option>
                    <option value="7"<?php if( br_get_value_from_array($filters, 'display_inline_count') == 7 ) echo ' selected'; ?>>7</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><?php _e('Show title only', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <input type="checkbox" class="berocket_hidden_clickable_option" name="<?php echo $post_name; ?>[hidden_clickable]" value="1"<?php if(! empty($filters['hidden_clickable']) ) echo ' checked'; ?>>
                <span><?php _e('Only title will be visible. Filter will be displayed after click on title and hide after click everywhere else', 'BeRocket_AJAX_domain'); ?></span>
            </td>
        </tr>
        <tr class="berocket_hidden_clickable_option_data">
            <th><?php _e('Display filters on mouse over', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <input type="checkbox" name="<?php echo $post_name; ?>[hidden_clickable_hover]" value="1"<?php if(! empty($filters['hidden_clickable_hover']) ) echo ' checked'; ?>>
                <span><?php _e('Display on mouse over and hide on mouse leave', 'BeRocket_AJAX_domain'); ?></span>
            </td>
        </tr>
<script>
    function berocket_hidden_clickable_option() {
        if( jQuery('.berocket_hidden_clickable_option').prop('checked') ) {
            jQuery('.berocket_hidden_clickable_option_data').show();
            jQuery('.berocket_filter_added_list').addClass('berocket_hidden_clickable_enabled');
        } else {
            jQuery('.berocket_hidden_clickable_option_data').hide();
            jQuery('.berocket_filter_added_list').removeClass('berocket_hidden_clickable_enabled');
        }
    }
    jQuery(document).on('change', '.berocket_hidden_clickable_option', berocket_hidden_clickable_option);
    berocket_hidden_clickable_option();
    function berocket_display_inline_count() {
        if( jQuery('.berocket_display_inline_option').prop('checked') && ! jQuery('.berocket_hidden_clickable_option').prop('checked') ) {
            jQuery('.berocket_display_inline_count').show();
        } else {
            jQuery('.berocket_display_inline_count').hide();
        }
    }
    jQuery(document).on('change', '.berocket_display_inline_option, .berocket_hidden_clickable_option', berocket_display_inline_count);
    berocket_display_inline_count();
    jQuery(document).ready(function() {
        berocket_hidden_clickable_option();
        berocket_display_inline_count();
    });
</script>
