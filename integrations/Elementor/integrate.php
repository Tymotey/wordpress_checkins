<?php
function add_elementor_widget_categories($elements_manager)
{

    $elements_manager->add_category(
        'btdev',
        [
            'title' => esc_html__('BTDEV', 'btdev_inscriere_text'),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'add_elementor_widget_categories');


// https://developers.elementor.com/docs/widgets/
function register_new_widgets($widgets_manager)
{
    require_once('widget_form_add.php');
    $widgets_manager->register(new \Elementor_Btdev_Widget_Form_Add());

    require_once('widget_summary.php');
    $widgets_manager->register(new \Elementor_Btdev_Widget_Summary());

    require_once('widget_list_entries.php');
    $widgets_manager->register(new \Elementor_Btdev_Widget_List_Entries());

    require_once('widget_list_payments.php');
    $widgets_manager->register(new \Elementor_Btdev_Widget_List_Payments());
}
add_action('elementor/widgets/register', 'register_new_widgets');
