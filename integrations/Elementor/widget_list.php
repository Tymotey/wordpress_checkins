<?php

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Classes\Tables as BTDEV_INSCRIERI_TABLES;

class Elementor_Btdev_Widget_List extends \Elementor\Widget_Base
{
    use BTDEV_INSCRIERI_UTILS;

    public function get_name()
    {
        return 'btdev_list_entries';
    }

    public function get_title()
    {
        return esc_html__('List Form Entries', 'btdev_inscriere_text');
    }

    public function get_icon()
    {
        return 'eicon-code';
    }

    public function get_custom_help_url()
    {
        return 'https://go.elementor.com/widget-name';
    }

    public function get_categories()
    {
        return ['btdev'];
    }

    public function get_keywords()
    {
        return ['form'];
    }

    protected function register_controls()
    {
        $table = new BTDEV_INSCRIERI_TABLES();
        $forms = $this->utils_get_forms('Choose a form');

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'btdev_inscriere_text'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_name',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => esc_html__('Form', 'btdev_inscriere_text'),
                'options' => $forms
            ]
        );

        $this->add_control(
            'table_type',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => esc_html__('Table Type', 'btdev_inscriere_text'),
                'options' => $table->get_table_types()
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        echo do_shortcode('[bbdev_inscrieri_list_entries form="' . $settings['form_name'] . '" type="' . $settings['table_type'] . '"]');
    }

    public function render_plain_content()
    {
        $settings = $this->get_settings_for_display();

        echo do_shortcode('[bbdev_inscrieri_list_entries form="' . $settings['form_name'] . '" type="' . $settings['table_type'] . '"]');
    }
}
