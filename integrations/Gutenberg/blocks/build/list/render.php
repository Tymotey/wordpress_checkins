<div <?php echo get_block_wrapper_attributes(); ?>>
    <?php
    // https://developer.wordpress.org/block-editor/getting-started/tutorial/#updating-render-php-2
    if (!empty($attributes['formName'])) {
        if (!empty($attributes['tableType'])) {
            echo do_shortcode('[bbdev_inscrieri_list_entries form="' . $attributes['formName'] . '" type="' . $attributes['tableType'] . '"]');
        } else {
            echo __('No table type has been chosen', 'btdev_inscriere_text');
        }
    } else {
        echo __('No form has been chosen', 'btdev_inscriere_text');
    }
    ?>
</div>