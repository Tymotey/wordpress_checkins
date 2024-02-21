<?php

namespace BTDEV_INSCRIERI\Interfaces;

interface Form
{
    function html_form_begin_area($form_data, $action);
    function html_form_repeater_area($form_data, $action);
    function html_form_end_area($form_data, $action);
    function get_repeater_html($form_data, $action, $index);
    function get_fields_logic();
    function get_form_page_url($for);
}
