<?php
function btdev_inscriere_gutenberg()
{
    wp_enqueue_script(
        'btdev_inscriere_form',
        plugin_dir_url(__FILE__) . 'js/form.js',
        array('wp-blocks', 'wp-editor'),
        true
    );
}

add_action('enqueue_block_editor_assets', 'btdev_inscriere_gutenberg');
