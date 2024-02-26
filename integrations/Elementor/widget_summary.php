<?php

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

class Elementor_Btdev_Widget_Summary extends \Elementor\Widget_Base
{
    use BTDEV_INSCRIERI_UTILS;

    public function get_name()
    {
        return 'btdev_submission_summary';
    }

    public function get_title()
    {
        return esc_html__('Add Submission summary', 'btdev_inscriere_text');
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

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        echo do_shortcode('[bbdev_inscrieri_entry_summary form="' . $settings['form_name'] . '"]');
    }

    public function render_plain_content()
    {
        $settings = $this->get_settings_for_display();

        echo do_shortcode('[bbdev_inscrieri_entry_summary form="' . $settings['form_name'] . '"]');
    }
}
