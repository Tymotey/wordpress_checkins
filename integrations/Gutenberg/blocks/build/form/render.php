<div <?php echo get_block_wrapper_attributes(); ?>>
    <?php
    // https://developer.wordpress.org/block-editor/getting-started/tutorial/#updating-render-php-2
    if (!empty($attributes['formName'])) {
        if (!empty($attributes['formAction'])) {
            echo do_shortcode('[bbdev_inscrieri_form form="' . $attributes['formName'] . '" action="' . $attributes['formAction'] . '"]');
        } else {
            echo __('No form action has been chosen', 'btdev_inscriere_text');
        }
    } else {
        echo __('No form has been chosen', 'btdev_inscriere_text');
    }
    ?>
</div>