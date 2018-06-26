<div class="br_accordion">
    <h3><?php if( $type == 'color' ) { _e('Color pick', 'BeRocket_AJAX_domain'); } elseif( $type == 'image' ) { _e('Image pick', 'BeRocket_AJAX_domain'); } ?></h3>
    <div>
<?php if ( is_array(berocket_isset($terms)) ) { 
    if( $type == 'color' ) {?>
<table>
    <?php foreach( $terms as $term ) { 
        ?>
        <tr>
            <td><?php echo berocket_isset($term, 'name') ?></td>
            <?php
            $color_list = array('color', 'color_2', 'color_3', 'color_4');
            foreach($color_list as $color_name) {
                $color_meta = get_metadata('berocket_term', $term->term_id, $color_name); 
                ?>
                <td class="colorpicker_field<?php if( empty($color_meta) && $color_name != 'color' ) echo ' colorpicker_removed' ?>" data-color="<?php echo br_get_value_from_array($color_meta, 0, 'ffffff') ?>">
                    <?php if( $color_name != 'color') { ?><i class="fa fa-times"></i><?php } ?>
                </td>
                <input class="colorpicker_field_input" type="hidden" value="<?php echo br_get_value_from_array($color_meta, 0); ?>"
                       name="br_widget_color[<?php echo $color_name; ?>][<?php echo $term->term_id ?>]" />
                <?php 
            } ?>
        </tr>
    <?php } ?>
    </table>
<?php
    if ( ! empty($load_script) ) {
        ?>
        <script>
            (function ($) {
                var colPick_timer = setInterval(function() {
                    if (typeof $('.colorpicker_field').colpick == 'function') {
                        clearInterval(colPick_timer);
                        $('.colorpicker_field').each(function (i,o) {
                            var color = $(o).data('color');
                            color = color+'';
                            color = color.replace('#', '');
                            $(o).data('color', color);
                            $(o).css('backgroundColor', '#'+$(o).data('color'));
                            if( ! $(o).is('.colorpicker_removed') ) {
                                $(o).next().val($(o).data('color'));
                            }
                            $(o).colpick({
                                layout: 'hex',
                                submit: 0,
                                color: '#'+$(o).data('color'),
                                onChange: function(hsb,hex,rgb,el,bySetColor) {
                                    $(el).removeClass('colorpicker_removed');
                                    $(el).css('backgroundColor', '#'+hex).next().val(hex).trigger('change');
                                }
                            });
                        });
                        jQuery('.colorpicker_field .fa-times').on('click', function(event) {
                            event.preventDefault();
                            event.stopPropagation();
                            jQuery(this).parent().css('backgroundColor', '#000000').colpickSetColor('#000000').addClass('colorpicker_removed');
                            jQuery(this).parent().next().val('');
                        });
                    }
                }, 500);
            })(jQuery);
        </script>
    <?php }
    } elseif( $type == 'image' ) {
        ?>
        <table>
    <?php foreach( $terms as $term ) { $color_meta = get_metadata('berocket_term', $term->term_id, $type); ?>
        <tr>
            <td class="br_aapf_settings_fa"><?php echo '<strong>' . $term->name . '</strong> ' . br_fontawesome_image("br_widget_color[".$term->term_id."]", br_get_value_from_array($color_meta, 0)); ?></td>
        </tr>
    <?php } ?>
    </table>
    <?php
    }
}
?>
</div>
</div>
<script>
        brjsf_accordion(jQuery( ".br_accordion" ));
</script>
