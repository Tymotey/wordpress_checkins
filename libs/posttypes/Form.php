<?php

namespace BTDEV_INSCRIERI\Posttypes;

use BTDEV_INSCRIERI\Abstracts\Posttype as A_POSTTYPE;
use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

class Form extends A_POSTTYPE
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct()
    {
        $this->set_slug('btdev_forms');

        $labels = array(
            'name' => _x('Forms', 'Forms', 'btdev_inscriere_text'),
            'singular_name' => _x('Form', 'Post Type Singular Name', 'btdev_inscriere_text'),
            'menu_name' => __('Inscriere Form', 'btdev_inscriere_text'),
            'name_admin_bar' => __('Inscriere Form', 'btdev_inscriere_text'),
            'all_items' => __('All Forms', 'btdev_inscriere_text'),
            'add_new_item' => __('Add New Form', 'btdev_inscriere_text'),
            'add_new' => __('Add New', 'btdev_inscriere_text'),
            'new_item' => __('New Form', 'btdev_inscriere_text'),
            'edit_item' => __('Edit Form', 'btdev_inscriere_text'),
            'update_item' => __('Update Form', 'btdev_inscriere_text'),
            'view_item' => __('View Form', 'btdev_inscriere_text'),
            'view_items' => __('View Forms', 'btdev_inscriere_text'),
            'search_items' => __('Search Form', 'btdev_inscriere_text'),
            'not_found' => __('Not found', 'btdev_inscriere_text'),
            'not_found_in_trash' => __('Not found in Trash', 'btdev_inscriere_text'),
            'featured_image' => __('Featured Image', 'btdev_inscriere_text'),
            'set_featured_image' => __('Set featured image', 'btdev_inscriere_text'),
            'remove_featured_image' => __('Remove featured image', 'btdev_inscriere_text'),
            'use_featured_image' => __('Use as featured image', 'btdev_inscriere_text'),
            'insert_into_item' => __('Insert into form', 'btdev_inscriere_text'),
            'uploaded_to_this_item' => __('Uploaded to this form', 'btdev_inscriere_text'),
            'items_list' => __('Forms list', 'btdev_inscriere_text'),
            'items_list_navigation' => __('Forms list navigation', 'btdev_inscriere_text'),
            'filter_items_list' => __('Filter forms list', 'btdev_inscriere_text'),
        );

        $rewrites = array(
            'with_front' => true,
            'pages' => false,
            'feeds' => false,
        );

        $args = array(
            'label' => __('Form', 'btdev_inscriere_text'),
            'description' => __('BTDEV Inscriere - Forms', 'btdev_inscriere_text'),
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 80,
            'menu_icon' => 'dashicons-welcome-widgets-menus',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
        );

        $this->prepare_args($args, $labels, $rewrites);
        $this->register_post_type();

        $this->set_meta_priority('high');
        $this->set_meta_fields(
            array(
                'btdev_forms_post_content' => array(
                    'type' => 'textarea',
                    'wrapper' => false,
                    'input_style' => 'display: nonee;width: 100%;height: 300px;',
                    'from' => array(
                        'table' => 'post',
                        'field_name' => 'post_content',
                    ),
                )
            )
        );

        add_action('admin_init', array($this, 'admin_init'));
    }

    public function admin_init()
    {
        global $pagenow;

        if ($pagenow === 'post.php') {
            $this->register_post_type_meta_box(
                __('Form settings', 'btdev_inscriere_text'),
                '<div id="btdev-inscriere-edit-form">Loading data...</div>'
            );
        }
    }
}
