<?php

namespace BTDEV_INSCRIERI\Classes;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

class Field
{
    use BTDEV_INSCRIERI_UTILS;

    public $field = [];
    public $value = null;
    public $form = false;
    public $index = false;

    public function __construct($field, $value, $form, $index = false)
    {
        $this->field = $field;
        $this->value = $value;
        $this->form = $form;
        $this->index = $index;
    }

    public function return_price_html($data = null, $pre_html = '(', $post_html = ')')
    {
        $return_html = '';

        if ($data !== null) {
            if ($this->form->full_data['payment']['currency'] === "USD") {
                $return_html .= '$';
            }

            // Value
            $is_plus = $data['value'] > 0 && $data['type'] === "relative";
            $is_minus = $data['value'] < 0 && $data['type'] === "relative";
            if ($is_minus) {
                $data['value'] = $data['value'] * -1;
            }
            $return_html .= $data['value'] / 100;

            // Currency
            if ($this->form->full_data['payment']['currency'] !== "USD") {
                $return_html .= $this->form->full_data['payment']['currency'];
            }

            // Sign
            if ($is_plus !== false) $return_html = '+' . $return_html;
            if ($is_minus !== false) {
                $return_html = '-' . $return_html;
            }
            if ($data['type'] === "absolute") {
                $return_html = '=' . $return_html;
            }

            // Add Pre and Post strings
            $return_html = $pre_html . $return_html;
            $return_html = $return_html . $post_html;
        }

        return $return_html;
    }

    public function get_price()
    {
        $return_data = null;
        if (isset($this->field['price'])) {
            if ($return_data === null) {
                $return_data = [];
            }
            if (!isset($return_data['type'])) {
                $return_data['type'] = $this->field['type'];
            }
            $return_data['price'] = $this->field['price'];
        }


        if (isset($this->field['options'])) {
            foreach ($this->field['options'] as $k => $option) {
                if (isset($option['price'])) {
                    if ($return_data === null) {
                        $return_data = [];
                    }
                    if (!isset($return_data['options'])) {
                        $return_data['options'] = [];
                    }
                    if (!isset($return_data['type'])) {
                        $return_data['type'] = $this->field['type'];
                    }
                    $return_data['options'][$k] = $option['price'];
                }
            }
        }

        if ($return_data !== null) return $return_data;
        else return false;
    }

    public function get_logic()
    {
        $return_data = null;
        if (isset($this->field['field_logic'])) {
            $return_data = ['type' => $this->field['type'], 'actions' => []];
            foreach ($this->field['field_logic'] as $k => $value) {
                $return_data['actions'][] = ['value' => $k, 'data' => $value];
            }
            // TBD: logic for others than radio/checkboxes
        }

        if ($return_data !== null) return $return_data;
        else return false;
    }

    public function create_html()
    {
        $html = '';
        $index_html = $this->index !== false ? $this->index : '';
        $input_name = $this->index !== false ? 'entries[' . $index_html . '][' . $this->field['name'] . ']' : $this->field['name'];
        $input_id = $this->index !== false ? $this->field['name'] . '_' . $index_html : $this->field['name'];

        if (isset($this->field['type'])) {
            $required = false;
            $required_input = false;
            if (isset($this->field['required']) && $this->field['required'] === true) {
                $required = true;
                $required_input = true;
            }

            $wrapper_class_add = [];
            if (isset($this->field['wrapper_class_add'])) {
                $wrapper_class_add = array_merge($wrapper_class_add, $this->field['wrapper_class_add']);
            }
            if (isset($this->field['width'])) {
                switch ($this->field['width']) {
                    case 'third':
                        $wrapper_class_add[] = 'one_third';
                        break;
                    case 'full':
                        $wrapper_class_add[] = 'full_width';
                        break;
                }
            }
            if (isset($this->field['hidden'])) {
                $wrapper_class_add[] = 'hidden_element';
                $required_input = false;
            }
            if ($required) {
                $wrapper_class_add[] = 'required_element';
            }
            if (in_array($this->field['type'], ['radio', 'checkbox', 'payment'])) {
                $wrapper_class_add[] = 'radio_checkbox_wrapper';
            }

            $input_class_add = [];
            if (isset($this->field['input_class_add'])) {
                $input_class_add = array_merge($input_class_add, $this->field['input_class_add']);
            }

            $attr_html = [];
            if (isset($this->field['attr_html'])) {
                foreach ($this->field['attr_html'] as $attr_index => $attr) {
                    $attr_html[$attr_index] = $attr;
                }
            }

            $placeholder = '';
            if (isset($this->field['placeholder'])) $placeholder = ' placeholder="' . $this->field['placeholder'] . '"';

            $label_add = '';
            if (isset($this->field['label_add']) && $this->field['label_add'] !== '')  $label_add = '<span class="extra_data">' . $this->field['label_add'] . '</span>';

            $price_label = '';
            if (isset($this->field['price']) && $this->field['price']) {
                $price_label = $this->return_price_html($this->field['price']);
            }

            $input_value = $this->value;
            if ($input_value === null && isset($this->field['default_value'])) {
                $input_value = $this->field['default_value'];
            }

            $html .= '<div id="wrapper_' . $input_name . '" class="form-group ' . implode(' ', $wrapper_class_add) . '">';
            switch ($this->field['type']) {
                case 'text':
                case 'email':
                case 'date':
                    $html .= '<label for="' . $input_id . '">' . __($this->field['title'], 'btdev_inscriere_text') . ($required ? '<sup>*</sup>' : '') . $label_add . $price_label . '</label>';
                    $html .= '<input type="' . $this->field['type'] . '" name="' . $input_name . '" value="' . $input_value . '" id="' . $input_id . '" class="form-input input_named_' . $this->field['name'] . ' ' . implode(' ', $input_class_add) . '"' . ($required_input ? ' required' : '') . ' ' . http_build_query($attr_html, '=', ' ') . $placeholder . '>';
                    break;
                case 'select':
                    $html .= '<label for="' . $input_id . '">' . __($this->field['title'], 'btdev_inscriere_text') . ($required ? '<sup>*</sup>' : '') . $label_add . $price_label . '</label>';
                    $html .= '<select name="' . $input_name . '" id="' . $input_id . '" class="form-input input_named_' . $this->field['name'] . '"' . ($required_input ? ' required' : '') . http_build_query($attr_html, '=', ' ') . $placeholder . '>';
                    if (isset($this->field['options']) && count($this->field['options']) > 0) {
                        foreach ($this->field['options'] as $value_option => $option) {
                            if (!isset($option['show_in_form']) || (isset($option['show_in_form']) && $option['show_in_form'] === true)) {
                                $price_option = '';
                                if (isset($option['price']) && $option['price']) {
                                    $price_option = $this->return_price_html($option['price']);
                                }

                                $disabled = '';
                                if (isset($option['disabled']) && $option['disabled'] === true) {
                                    $disabled = ' disabled="disabled"';
                                }

                                $html .= '<option value="' . $value_option . '" ' . ($value_option === $input_value ? 'selected' : '') . $disabled .  '>' . __($option['title'], 'btdev_inscriere_text') . $price_option . '</option>';
                            }
                        }
                    }
                    $html .= '</select>';
                    break;
                case 'radio':
                case 'checkbox':
                case 'payment':
                    // TBD: selected si default value
                    $field_type = $this->field['type'];
                    $attr_wrapper_input = [];
                    $disable_other = false;
                    if (isset($this->field['disable_other']) && $this->field['disable_other'] !== '') {
                        $disable_other = true;
                        $attr_wrapper_input['attr-change'] = $this->field['disable_other'];
                    }

                    $inner_wrapper_class_add = [];
                    if (isset($this->field['inner_wrapper_class_add'])) {
                        $inner_wrapper_class_add = array_merge($inner_wrapper_class_add, $this->field['inner_wrapper_class_add']);
                    }

                    $html .= '<div class="checkbox_title">
                        <span class="text">' . $this->field['title'] . $label_add . '</span>
                    </div>
                    <div id="wrapper_' . $input_id . '" class="form-group full_width radio_wrapper ' . implode(' ', $inner_wrapper_class_add) . ' ' . ($disable_other === true ? 'disable_other' : '') . '" ' . http_build_query($attr_wrapper_input, '', ' ') . '>';

                    $options = [];
                    if (isset($this->field['options']) && count($this->field['options']) > 0) {
                        $options = $this->field['options'];
                    }
                    if ($field_type === 'payment') {
                        if (isset($this->form->full_data['payment']) && isset($this->form->full_data['payment']['with']) && count($this->form->full_data['payment']['with']) > 0) {
                            foreach ($this->form->full_data['payment']['with'] as $payment) {
                                $classname = 'BTDEV_INSCRIERI\\Classes\\ThirdParty\\' . ucfirst($payment);
                                if (class_exists($classname)) {
                                    $payment_class = new $classname();
                                    $options[$payment_class->get_name()] = ['title' => $payment_class->get_title(), 'short_id' => $payment_class->get_name()];
                                }
                            }
                        }
                    }

                    if (count($options) > 0) {
                        foreach ($options as $value_option => $option) {
                            if (!isset($option['show_in_form']) ||  (isset($option['show_in_form']) && $option['show_in_form'] === true)) {
                                $price_option = '';
                                if (isset($option['price']) && $option['price']) {
                                    $price_option = $this->return_price_html($option['price']);
                                }

                                $disabled = '';
                                if (isset($option['disabled']) && $option['disabled'] === true) {
                                    $disabled = ' disabled="disabled"';
                                }

                                $html .= '<label for="' . $this->field['name'] . '_' . $value_option . '_' . $this->index . '">
                                <input type="' . ($field_type === 'payment' ? 'radio' : $this->field['type']) . '" name="' . $input_name . '" id="' . $this->field['name'] . '_' . $value_option . '_' . $this->index . '" class="form-input input_named_' . $this->field['name'] . ' input_val_' . $option['short_id'] . '" value="' . $value_option . '"' . ($required_input ? ' required' : '') . $disabled . (($input_value !== null && $input_value === $value_option) ? ' checked' : '') . '>
                                <span class="label_text">' . __($option['title'], 'btdev_inscriere_text') . $price_option . '</span>
                            </label>';
                            }
                        }
                    }
                    $html .= '</div>';


                    break;
                case 'html':
                    $html .= $this->field['html'];
                    break;
                case 'total_row':
                    $html .= '<span class="btdev_inscriere_total_text">
                        ' . __('Total', 'btdev_inscriere_text') . ': 
                        <span class="btdev_inscriere_total_amount">' . ($this->form->full_data['payment']['base_price'] / 100) . '</span>
                        <span class="btdev_inscriere_total_currency">' . $this->form->full_data['payment']['currency'] . '</span>
                    </span>';
                    break;
            }
            $html .= '</div>';
        }

        return $html;
    }

    public function get_html_label($label_value_separator = ': ', $label_wrap = '<strong>%s</strong>', $value_wrap = '%s', $html_append = '<br />')
    {
        $html = '';

        if ($this->value !== null) {
            $value_html = $this->value;

            switch ($this->field['type']) {
                case 'select':
                case 'radio':
                case 'checkbox':
                    if (isset($this->field['options'][$value_html])) {
                        $value_html = $this->field['options'][$value_html]['title'];
                    }
                    break;
            }

            $html = sprintf($label_wrap, $this->field['title']) . $label_value_separator . sprintf($value_wrap, $value_html) . $html_append;
        }

        return $html;
    }
}
