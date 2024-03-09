<div <?php echo get_block_wrapper_attributes(); ?>>
    <?php
    // https://developer.wordpress.org/block-editor/getting-started/tutorial/#updating-render-php-2
    if (!empty($attributes['formName'])) {
        echo do_shortcode('[bbdev_inscrieri_entry_summary form="' . $attributes['formName'] . '"]');
    }
    ?>
</div>