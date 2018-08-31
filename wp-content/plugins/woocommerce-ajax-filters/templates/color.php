<?php
$random_name = rand();
$hiden_value = false;
$child_parent = berocket_isset($child_parent);
$is_child_parent = $child_parent == 'child';
$is_child_parent_or = ( $child_parent == 'child' || $child_parent == 'parent' );
$child_parent_depth = berocket_isset($child_parent_depth, false, 0);
if ( $child_parent == 'parent' ) {
    $child_parent_depth = 0;
}
$is_first = true;
$item_i = 0;
if ( is_array(berocket_isset($terms)) ) {
    if( berocket_isset($color_image_checked) == 'brchecked_custom' ) {
        echo '<style>
        .berocket_aapf_widget .berocket_checkbox_color.brchecked_custom_'.$random_name.' input[type="checkbox"]:checked + label .berocket_color_span_block,
        .berocket_aapf_widget .berocket_checkbox_color.brchecked_custom_'.$random_name.' .berocket_checked .berocket_color_span_block{
            '.$color_image_checked_custom_css.'
        }
        </style>';
    }
    if( $color_image_block_size == 'hxpx_wxpx' ) {
        if( empty($color_image_block_size_height) ) {
            $color_image_block_size_height = 50;
        }
        if( empty($color_image_block_size_width) ) {
            $color_image_block_size_width = 50;
        }
        echo '<style>
        .berocket_aapf_widget .berocket_checkbox_color.berocket_color_with_value.hxpx_wxpx_'.$random_name.'.brchecked_default input[type="checkbox"]:checked + label .berocket_color_span_block,
        .berocket_aapf_widget .berocket_checkbox_color.berocket_color_with_value.hxpx_wxpx_'.$random_name.'.brchecked_default .berocket_checked .berocket_color_span_block{
            width: '.($color_image_block_size_width + 10).'px;
        }
        .berocket_aapf_widget .berocket_checkbox_color.hxpx_wxpx_'.$random_name.' label span.berocket_color_span_block, span.berocket_color_span_block{
            width: '.$color_image_block_size_width.'px;
        }
        .berocket_aapf_widget .berocket_checkbox_color.hxpx_wxpx_'.$random_name.' label span.berocket_color_span_block, span.berocket_color_span_block{
            height: '.$color_image_block_size_height.'px;
            line-height: '.$color_image_block_size_height.'px;
        }
        .berocket_aapf_widget .berocket_checkbox_color.hxpx_wxpx_'.$random_name.'{
            height: '.($color_image_block_size_height + 10).'px;
        }';
        if( ($color_image_block_size_ratio = $color_image_block_size_width / $color_image_block_size_height) < 1.3 ) {
            
            $color_image_block_size_margin = (15 / $color_image_block_size_ratio) + (1 - $color_image_block_size_ratio) * (30 + 5 / $color_image_block_size_ratio / $color_image_block_size_ratio);
            echo '.berocket_checkbox_color.hxpx_wxpx_'.$random_name.' .berocket_color_span_block .berocket_color_multiple {
                margin-left: -'.$color_image_block_size_margin.'%;
                margin-right: -'.$color_image_block_size_margin.'%;
            }';
        }
        echo '</style>';
    }
    foreach ( $terms as $term ) {
        $term_taxonomy_echo = berocket_isset($term, 'wpml_taxonomy');
        if( empty($term_taxonomy_echo) ) {
            $term_taxonomy_echo = berocket_isset($term, 'taxonomy');
        }
        $item_i++;
        $meta_class = apply_filters('berocket_widget_color_image_temp_meta_class_init', '&nbsp;', $term);
        $meta_after = '';
        if ( !$is_child_parent || !$is_first ) {
            if( $type == 'color' ) {
                $color_list = array('color', 'color_2', 'color_3', 'color_4');
                $meta_color = array();
                foreach($color_list as $color_name) {
                    $berocket_term = get_metadata( 'berocket_term', $term->term_id, $color_name );
                    $berocket_term = br_get_value_from_array($berocket_term, 0, '');
                    if( empty($berocket_term) && $color_name != 'color') continue;
                    $meta_color[] = $berocket_term;
                }
            } else {
                $meta_color = get_metadata( 'berocket_term', $term->term_id, $type );
            }
        } else {
            $meta_color = 'R';
            if( $type == 'color' ) {
                $meta_color = array($meta_color);
            }
            ?>
            <li class="berocket_child_parent_sample"><ul>
            <?php
        }
        $meta_color_init = $meta_color;
        if( $type == 'color' ) {
            if( count($meta_color) == 1 ) {
                $meta_color = $meta_color[0];
                $meta_color = str_replace('#', '', $meta_color);
                $meta_color = 'background-color: #'.$meta_color.';';
                $meta_class = '<span class="berocket_color_span_absolute"><span>'.$meta_class.'</span></span>';
            } else {
                $meta_class = '<span class="berocket_color_multiple berocket_color_multiple_'.count($meta_color).'">';
                foreach($meta_color as $meta_color_key => $meta_color_val) {
                    $meta_color_val = str_replace('#', '', $meta_color_val);
                    $meta_color_val = 'background-color: #'.$meta_color_val.';';
                    $meta_class .= '<span style="'.$meta_color_val.'" class="berocket_color_multiple_single berocket_color_multiple_single_'.$meta_color_key.'"></span>';
                }
                $meta_class .= '</span>';
                $meta_color = '';
            }
        } elseif( $type == 'image' ) {
            if ( ! empty($meta_color[0]) ) {
                if ( substr( $meta_color[0], 0, 3) == 'fa-' ) {
                    $meta_class = '<i class="fa '.$meta_color[0].'"></i>&nbsp;';
                    $meta_color = '';
                } else {
                    $meta_color = 'background: url('.$meta_color[0].') no-repeat scroll 50% 50% rgba(0, 0, 0, 0);';
                    $meta_class = '&nbsp;';
                }
                $meta_after = '';
            } else {
                $meta_color = '';
                $meta_class = '';
            }
        }
        list($meta_class, $meta_after, $meta_color) = apply_filters('berocket_widget_color_image_temp_meta_ready', array($meta_class, $meta_after, $meta_color), $term, $meta_color_init);
        ?>
        <li class="berocket_term_parent_<?php echo berocket_isset($term, 'parent'); 
        if ( $is_child_parent ) echo ' R__class__R';
        if( ! empty($hide_o_value) && berocket_isset($term, 'count') == 0 && ( !$is_child_parent || !$is_first ) ) {
            echo ' berocket_hide_o_value';
            $hiden_value = true;
        }
        if( ! empty($hide_sel_value) && br_is_term_selected( $term, true, $is_child_parent_or, $child_parent_depth ) != '' ) {
            echo ' berocket_hide_sel_value';
            $hiden_value = true;
        }
        if( ! empty($attribute_count) ) {
            if( $item_i > $attribute_count ) {
                echo ' berocket_hide_attribute_count_value';
                $hiden_value = true;
            } elseif( ! empty($hide_o_value) && berocket_isset($term, 'count') == 0 && ( !$is_child_parent || !$is_first ) ) {
                echo ' berocket_hide_attribute_count_value';
                $item_i--;
                $hiden_value = true;
            }
        }
        if( $color_image_block_size == 'hxpx_wxpx' ) {
            echo ' hxpx_wxpx_'.$random_name;
        } else {
            echo ' '.$color_image_block_size;
        }
        if( berocket_isset($color_image_checked) == 'brchecked_custom' ) {
            echo ' brchecked_custom_'.$random_name;
        } else {
            echo ' '.(empty($color_image_checked) ? 'brchecked_default' : $color_image_checked);
        }
        ?> berocket_checkbox_color<?php echo ( ! empty($use_value_with_color) ? ' berocket_color_with_value' : ' berocket_color_without_value' ) ?>">
            <span>
                <input id='checkbox_<?php echo str_replace ( '*' , '-' , berocket_isset($term, 'term_id')), str_replace ( '*' , '-' , $term_taxonomy_echo) ?>_<?php echo berocket_isset($random_name) ?>'
                       class="<?php echo ( empty($uo['class']['checkbox_radio']) ? '' : $uo['class']['checkbox_radio'] ) ?> checkbox_<?php echo str_replace ( '*' , '-' , berocket_isset($term, 'term_id')), str_replace ( '*' , '-' , $term_taxonomy_echo) ?>" 
                       type='checkbox' 
                       autocomplete="off" 
                       style="<?php echo ( empty($uo['style']['checkbox_radio']) ? '' : $uo['style']['checkbox_radio'] ) ?>" data-term_slug='<?php echo urldecode(berocket_isset($term, 'slug')) ?>' 
                       data-term_name='<?php echo ( ! empty($icon_before_value) ? ( ( substr( $icon_before_value, 0, 3) == 'fa-' ) ? '<i class="fa '.$icon_before_value.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon_before_value.'" alt=""></i>' ) : '' ) . berocket_isset($term, 'name') . ( ! empty($icon_after_value) ? ( ( substr( $icon_after_value, 0, 3) == 'fa-' ) ? '<i class="fa '.$icon_after_value.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon_after_value.'" alt=""></i>' ) : '' )?>' 
                       data-filter_type='<?php echo berocket_isset($filter_type) ?>' <?php if( ! empty($term->term_id) ) { ?>data-term_id='<?php echo $term->term_id ?>'<?php } ?> data-operator='<?php echo $operator ?>' 
                       data-term_ranges='<?php echo str_replace ( '*' , '-' , berocket_isset($term, 'term_id')) ?>' 
                       data-taxonomy='<?php echo $term_taxonomy_echo ?>' 
                       data-term_count='<?php echo berocket_isset($term, 'count') ?>' 
                       <?php echo br_is_term_selected( $term, true, $is_child_parent_or, $child_parent_depth ); ?> />
                <label data-for='checkbox_<?php echo str_replace ( '*' , '-' , berocket_isset($term, 'term_id')), str_replace ( '*' , '-' , $term_taxonomy_echo) ?>' 
                    class="berocket_label_widgets<?php if( br_is_term_selected( $term, true, $is_child_parent_or, $child_parent_depth ) != '') echo ' berocket_checked'; ?>">
                    <?php 
                    echo apply_filters( 'berocket_check_radio_color_filter_term_text', ( '<span class="'. apply_filters('berocket_widget_color_image_temp_span_class', 'berocket_color_span_block', array($meta_class, $meta_after, $meta_color), $term) . '"
                    style="' . $meta_color . '">' . $meta_class . '</span>' .
                    ( ! empty($use_value_with_color) ? '<span class="berocket_color_text">' . ( ! empty($icon_before_value) ? ( ( substr( $icon_before_value, 0, 3) == 'fa-' ) ? '<i class="fa '.$icon_before_value.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon_before_value.'" alt=""></i>' ) : '' ) . $term->name . ( ! empty($icon_after_value) ? ( ( substr( $icon_after_value, 0, 3) == 'fa-' ) ? '<i class="fa '.$icon_after_value.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon_after_value.'" alt=""></i>' ) : '' ) . '</span>' : '' ) .
                    berocket_isset($meta_after) ), $term, $operator, FALSE );
                    ?>
                </label>
            </span>
        </li>
        <?php
        if ( $is_child_parent && $is_first ) {
            ?>
            </ul></li>
            <?php
            $is_first = false;
        }
    } ?>
    <?php if( $is_child_parent && is_array(berocket_isset($terms)) && count($terms) == 1 ) {
        if( BeRocket_AAPF_Widget::is_parent_selected($attribute, $child_parent_depth - 1) ) {
            echo '<li>'.$child_parent_no_values.'</li>';
        } else {
            echo '<li>'.$child_parent_previous.'</li>';
        }
    } else {
    if( $child_parent_no_values ) {?>
        <script>
        if ( typeof(child_parent_depth) == 'undefined' || child_parent_depth < <?php echo $child_parent_depth; ?> ) {
            child_parent_depth = <?php echo $child_parent_depth; ?>;
        }
        jQuery(document).ready(function() {
            if( child_parent_depth == <?php echo $child_parent_depth; ?> ) {
                jQuery('.woocommerce-info').text('<?php echo $child_parent_no_values; ?>');
            }
        });
        </script>
    <?php }
    }
    if( ! empty($attribute_count_show_hide) ) {
        if( $attribute_count_show_hide == 'hidden' ) {
            $hide_button_value = true;
        } elseif( $attribute_count_show_hide == 'visible' ) {
            $hide_button_value = false;
        }
    }
    if( empty($hide_button_value) ) { ?>
        <li class="berocket_widget_show_values"<?php if( !$hiden_value ) echo 'style="display: none;"' ?>><?php _e('Show value(s)', 'BeRocket_AJAX_domain') ?><span class="show_button"></span></li>
    <div style="clear: both;"></div>
<?php } }
