<div class="berocket_filter_groups">
    <table>
        <tr>
            <th><?php _e('Custom CSS class', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <input type="text" name="br_filter_group[custom_class]" value="<?php echo br_get_value_from_array($filters, 'custom_class'); ?>">
                <small><?php _e('use white space for multiple classes', 'BeRocket_AJAX_domain');?></small>
            </td>
        </tr>
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
                <input type="checkbox" name="br_filter_group[display_inline]" value="1"<?php if(! empty($filters['display_inline']) ) echo ' checked'; ?>>
            </td>
        </tr>
        <tr>
            <th><?php _e('Show title only', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <input type="checkbox" class="berocket_hidden_clickable_option" name="br_filter_group[hidden_clickable]" value="1"<?php if(! empty($filters['hidden_clickable']) ) echo ' checked'; ?>>
                <span><?php _e('Only title will be visible. Filter will be displayed after click on title and hide after click everywhere else', 'BeRocket_AJAX_domain'); ?></span>
            </td>
        </tr>
        <tr class="berocket_hidden_clickable_option_data">
            <th><?php _e('Display filters on mouse over', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <input type="checkbox" name="br_filter_group[hidden_clickable_hover]" value="1"<?php if(! empty($filters['hidden_clickable_hover']) ) echo ' checked'; ?>>
                <span><?php _e('Display on mouse over and hide on mouse leave', 'BeRocket_AJAX_domain'); ?></span>
            </td>
        </tr>
    </table>
    <h3><?php _e('Filters In Group', 'BeRocket_AJAX_domain'); ?></h3>
    <?php
    $query = new WP_Query(array('post_type' => 'br_product_filter', 'nopaging' => true));
    if ( $query->have_posts() ) {
        echo '<select class="berocket_filter_list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<option data-name="' . get_the_title() . '" value="' . get_the_id() . '">' . get_the_title() . ' (ID:' . get_the_id() . ')</option>';
        }
        echo '</select>';
        echo ' <a class="button berocket_add_filter_to_group" href="#add_filter">' . __('Add filter', 'BeRocket_AJAX_domain') . '</a>';
        echo ' <a href="' . admin_url('edit.php?post_type=br_product_filter') . '">' . __('Manage filters', 'BeRocket_AJAX_domain') . '</a>';
        wp_reset_postdata();
    }
    ?>
    <ul class="berocket_filter_added_list" data-name="br_filter_group[filters][]" data-url="<?php echo admin_url('post.php');?>">
    <?php 
    if( isset($filters['filters']) && is_array($filters['filters']) ) {
        foreach($filters['filters'] as $filter) {
            $filter_id = $filter;
            $filter_post = get_post($filter_id);
            if( ! empty($filter_post) ) {
                echo '<li class="berocket_filter_added_' . $filter_id . '"><fa class="fa fa-bars"></fa>
                    <input type="hidden" name="br_filter_group[filters][]" value="' . $filter_id . '">
                    ' . $filter_post->post_title . ' <small>ID:' . $filter_id . '</small>
                    <i class="fa fa-times"></i>
                    <a class="berocket_edit_filter" target="_blank" href="' . admin_url('post.php?post='.$filter_id.'&action=edit') . '">' . __('Edit', 'BeRocket_AJAX_domain') . '</a>
                    <div class="berocket_hidden_clickable_options">
                        ' . __('Width', 'BeRocket_AJAX_domain') . '<input type="text" name="br_filter_group[filters_data][' . $filter_id . '][width]" value="' . br_get_value_from_array($filters, array('filters_data', $filter_id, 'width')) . '" placeholder="100%">
                    </div>
                </li>';
            }
        }
    }
    ?>
    </ul>
</div>
<script>
    jQuery(document).on('click', '.berocket_add_filter_to_group', function(event) {
        event.preventDefault();
        if( ! jQuery('.berocket_filter_added_'+jQuery('.berocket_filter_list').val()).length ) {
            var html = '<li class="berocket_filter_added_'+jQuery('.berocket_filter_list').val()+'"><i class="fa fa-bars"></i> ';
            html += '<input type="hidden" name="'+jQuery('.berocket_filter_added_list').data('name')+'" value="'+jQuery('.berocket_filter_list').val()+'">';
            html += jQuery('.berocket_filter_list').find(':selected').data('name');
            html += ' <small>ID:'+jQuery('.berocket_filter_list').val()+'</small>';
            html += '<i class="fa fa-times"></i>';
            html += ' <a class="berocket_edit_filter" target="_blank" href="'+jQuery('.berocket_filter_added_list').data('url')+'?post='+jQuery('.berocket_filter_list').val()+'&action=edit"><?php _e('Edit', 'BeRocket_AJAX_domain'); ?></a>';
            html += '<div class="berocket_hidden_clickable_options">';
            html += '<?php _e('Width', 'BeRocket_AJAX_domain'); ?><input type="text" name="br_filter_group[filters_data]['+jQuery('.berocket_filter_list').val()+'][width]" placeholder="100%" value="">';
            html += '</div>';
            html += '</li>';
            jQuery('.berocket_filter_added_list').append(jQuery(html));
        } else {
            jQuery('.berocket_filter_added_'+jQuery('.berocket_filter_list').val()).css('background-color', '#ee3333').clearQueue().animate({backgroundColor:'#eeeeee'}, 1000);
        }
    });
    jQuery(document).on('click', '.berocket_filter_added_list .fa-times', function(event) {
        jQuery(this).parents('li').first().remove();
    });
    jQuery(document).ready(function() {
        if(typeof(jQuery( ".berocket_filter_added_list" ).sortable) == 'function') {
            jQuery( ".berocket_filter_added_list" ).sortable({axis:"y", handle:".fa-bars", placeholder: "berocket_sortable_space"});
        }
    });
    jQuery(document).on('change', '.berocket_hidden_clickable_option', berocket_hidden_clickable_option);
    function berocket_hidden_clickable_option() {
        if( jQuery('.berocket_hidden_clickable_option').prop('checked') ) {
            jQuery('.berocket_hidden_clickable_option_data').show();
            jQuery('.berocket_filter_added_list').addClass('berocket_hidden_clickable_enabled');
        } else {
            jQuery('.berocket_hidden_clickable_option_data').hide();
            jQuery('.berocket_filter_added_list').removeClass('berocket_hidden_clickable_enabled');
        }
    }
    berocket_hidden_clickable_option();
</script>
<style>
.berocket_filter_added_list li {
    font-size: 2em;
    border: 2px solid rgb(153, 153, 153);
    background-color: rgb(238, 238, 238);
    padding: 5px;
    line-height: 1.1em;
}
.berocket_filter_added_list li .fa-bars {
    margin-right: 0.5em;
    cursor: move;
}
.berocket_filter_added_list small {
    font-size: 0.5em;
    line-height: 2em;
    vertical-align: middle;
}
.berocket_filter_added_list li .fa-times {
    margin-left: 0.5em;
    cursor: pointer;
    float: right;
}
.berocket_filter_added_list li .fa-times:hover {
    color: black;
}
.berocket_filter_added_list .berocket_edit_filter {
    vertical-align: middle;
    font-size: 0.5em;
    float: right;
    line-height: 2em;
    height: 2em;
    display: inline-block;
}
.berocket_filter_added_list .berocket_sortable_space {
    border: 2px dashed #aaa;
    background: white;
    font-size: 2em;
    height: 1.1em;
    box-sizing: content-box;
    padding: 5px;
}
.berocket_filter_groups {
    margin-top: 20px;
}
.berocket_filter_added_list .berocket_hidden_clickable_options {
    font-size: 12px;
    float: right;
    margin-right: 10px;
    display: none;
}
.berocket_hidden_clickable_options input{
    width: 100px;
}
.berocket_filter_added_list.berocket_hidden_clickable_enabled .berocket_hidden_clickable_options {
    display: inline-block;
}
</style>
