<?php
function btdev_inscriere_gutenberg()
{
    wp_enqueue_script(
        'btdev_inscriere_blocks',
        plugin_dir_url(__FILE__) . 'js/blocks.js',
        array('wp-blocks', 'wp-editor'),
        true
    );
}
add_action('enqueue_block_editor_assets', 'btdev_inscriere_gutenberg');

function btdev_inscriere_gutenberg_category_blocks($categories)
{
    $categories[] = array(
        'slug'  => 'btdev-inscrieri',
        'title' => 'BTDEV Inscrieri'
    );

    return $categories;
}
add_filter('block_categories_all', 'btdev_inscriere_gutenberg_category_blocks');
