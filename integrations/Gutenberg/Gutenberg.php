<?php

namespace BTDEV_INSCRIERI\Integrations\Gutenberg;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

// https://luigicavalieri.com/blog/registering-multiple-gutenberg-blocks-using-block_json-files/
class Gutenberg
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct()
    {
        add_filter('block_categories_all', array($this, 'register_blocks_category'));

        // Blocks
        register_block_type(__DIR__ . '/blocks/build/summary/block.json');
        register_block_type(__DIR__ . '/blocks/build/list/block.json');
        register_block_type(__DIR__ . '/blocks/build/form/block.json');
    }


    public function register_blocks_category($categories)
    {
        $categories[] = array(
            'slug' => 'btdev-inscrieri',
            'title' => 'BTDEV Inscrieri'
        );

        return $categories;
    }
}


