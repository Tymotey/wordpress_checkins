<?php

class Elementor_Btdev_Widget_Form_Add extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'btdev_form';
    }

    public function get_title()
    {
        return esc_html__('Add Entry Form', 'btdev_inscriere_text');
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
                'type' => \Elementor\Controls_Manager::TEXT,
                'label' => esc_html__('Form', 'btdev_inscriere_text'),
                'placeholder' => esc_html__('Enter your form id', 'btdev_inscriere_text'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        echo do_shortcode('[bbdev_inscrieri_form form="' . $settings['form_name'] . '"]');
    }

    public function render_plain_content()
    {
        $settings = $this->get_settings_for_display();

        echo do_shortcode('[bbdev_inscrieri_form form="' . $settings['form_name'] . '"]');
    }
}
