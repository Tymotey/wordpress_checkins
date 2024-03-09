<?php

namespace BTDEV_INSCRIERI\Abstracts;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

abstract class Posttype
{
    use BTDEV_INSCRIERI_UTILS;

    private $slug = '';
    private $args = [];
    private $label = [];
    private $rewrite = [];
    private $meta_box_title = '';
    private $meta_box_html_before = '';
    private $meta_box_html_after = '';
    private $meta_position = 'normal';
    private $meta_priority = 'default';
    private $meta_fields = [];

    abstract public function __construct();


    public function prepare_args($args, $labels, $rewrites)
    {
        $args['labels'] = $labels;
        $args['rewrite'] = $rewrites;
        $args['rewrite']['slug'] = $this->get_slug();

        $this->set_args($args);
    }
    public function register_post_type()
    {
        register_post_type('btdev_forms', $this->get_args());
    }

    public function register_post_type_meta_box($title, $html_before = "", $html_after = "")
    {
        $this->set_meta_box_title($title);
        if ($html_before !== '') {
            $this->set_meta_box_html_before($html_before);
        }
        if ($html_after !== '') {
            $this->set_meta_box_html_after($html_after);
        }

        add_action('add_meta_boxes', array($this, 'post_type_meta_box'));
        add_action('save_post', array($this, 'post_type_meta_box_action'), 10, 2);
    }
    function post_type_meta_box()
    {
        add_meta_box(
            'global-notice',
            $this->get_meta_box_title(),
            array($this, 'echo_meta_box'),
            $this->get_slug(),
            $this->get_meta_position(),
            $this->get_meta_priority()
        );
    }

    public function post_type_meta_box_action($post_id, $post_obj)
    {

        if (!wp_verify_nonce($_POST['btdev_inscriere_meta_box_nonce'], 'btdev_inscriere_meta_box_nonce')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
        if ($this->get_slug() === $_POST['post_type']) {
            $fields = $this->get_meta_fields();
            $post_has_form_fields = false;
            foreach ($fields as $field => $field_data) {
                if (isset($_POST[$field])) {
                    $post_has_form_fields = true;
                    break;
                }
            }
            if (!$post_has_form_fields) {
                return;
            }

            foreach ($fields as $field_name => $field_data) {
                if (!isset($_POST[$field_name])) {
                    continue;
                }

                $data = $field_data['from'];
                if (isset($field_data['to'])) {
                    $data = $field_data['to'];
                }

                if ($data['table'] === 'post') {
                    // Skip infinite by removing action
                    remove_action('save_post', array($this, 'post_type_meta_box_action'), 10);
                    $save_post = [
                        'ID' => $post_obj->ID,
                        $data['field_name'] => $_POST[$field_name],
                    ];
                    wp_update_post($save_post);
                    add_action('save_post', array($this, 'post_type_meta_box_action'), 10, 2);
                } else {
                    update_post_meta($post_id, $field_name, $_POST[$field_name]);
                }
            }

        }
    }

    public function get_slug()
    {
        return $this->slug;
    }
    public function set_slug($value)
    {
        $this->slug = $value;
    }

    public function get_args()
    {
        return $this->args;
    }
    public function set_args($value)
    {
        $this->args = $value;
    }

    public function get_meta_box_title()
    {
        return $this->meta_box_title;
    }
    public function set_meta_box_title($value)
    {
        $this->meta_box_title = $value;
    }

    public function set_meta_box_html_before($value)
    {
        $this->meta_box_html_before = $value;
    }
    public function get_meta_box_html_before()
    {
        return $this->meta_box_html_before;
    }
    public function set_meta_box_html_after($value)
    {
        $this->meta_box_html_after = $value;
    }
    public function get_meta_box_html_after()
    {
        return $this->meta_box_html_after;
    }

    public function get_meta_box()
    {
        $nonce = wp_nonce_field('btdev_inscriere_meta_box_nonce', 'btdev_inscriere_meta_box_nonce');

        return $nonce . $this->get_meta_box_html_before() . $this->return_meta_fields_html() . $this->get_meta_box_html_after();
    }
    public function return_meta_box()
    {
        return $this->get_meta_box();
    }
    public function echo_meta_box()
    {
        echo $this->get_meta_box();
    }

    public function get_meta_position()
    {
        return $this->meta_position;
    }
    public function set_meta_position($value)
    {
        $this->meta_position = $value;
    }

    public function get_meta_priority()
    {
        return $this->meta_priority;
    }
    public function set_meta_priority($value)
    {
        $this->meta_priority = $value;
    }

    public function get_meta_fields()
    {
        return $this->meta_fields;
    }
    public function set_meta_fields($value)
    {
        $this->meta_fields = $value;
    }

    public function return_meta_fields_html()
    {
        global $post;
        $html = '';
        $fields = $this->get_meta_fields();

        foreach ($fields as $k_field => $field) {
            $css_class = (isset($field['input_class']) && count($field['input_class']) > 0) ? implode(' ', $field['input_class']) : '';
            $css_style = isset($field['input_style']) ? $field['input_style'] : '';

            $data = $field['from'];
            if (isset($field['to'])) {
                $data = $field['to'];
            }

            $value = '';
            if ($data['table'] === 'post') {
                $obj_name = $data['field_name'];
                $value = $post->$obj_name;
            } else {
                $value = get_post_meta($post->ID, $k_field, true);
            }

            switch ($field['type']) {
                case 'textarea':
                    $html .= '<textarea name="' . $k_field . '" id="' . $k_field . '"' . ($css_class !== '' ? ' class="' . $css_class . '"' : '') . ($css_style !== '' ? ' style="' . $css_style . '"' : '') . '>' . $value . '</textarea>';
                    break;
            }
        }

        return $html;
    }
}
