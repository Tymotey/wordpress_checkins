<?php

namespace BTDEV_INSCRIERI\Integrations\Elementor;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

// https://luigicavalieri.com/blog/registering-multiple-gutenberg-blocks-using-block_json-files/
class Elementor
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct()
    {
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_widget_categories'));
        add_action('elementor/widgets/register', array($this, 'register_new_widgets'));
    }

    public function add_elementor_widget_categories($elements_manager)
    {

        $elements_manager->add_category(
            'btdev',
            [
                'title' => esc_html__('BTDEV', 'btdev_inscriere_text'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    // https://developers.elementor.com/docs/widgets/
    public function register_new_widgets($widgets_manager)
    {
        require_once 'widget_form_add.php';
        $widgets_manager->register(new \Elementor_Btdev_Widget_Form_Add());

        require_once 'widget_summary.php';
        $widgets_manager->register(new \Elementor_Btdev_Widget_Summary());

        require_once 'widget_list.php';
        $widgets_manager->register(new \Elementor_Btdev_Widget_List());
    }
}


