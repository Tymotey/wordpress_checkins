<?php

namespace BTDEV_INSCRIERI\Classes;

use BTDEV_INSCRIERI\Exceptions\Submission as BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Classes\SubmissionsDb as BTDEV_INSCRIERI_SUBMISSIONDB;

class Submission
{
    use BTDEV_INSCRIERI_UTILS;

    public $data = [];
    public $form = null;
    public $entries = [];

    public function __construct($data, $form = null)
    {
        global $wpdb;

        if ($form !== null) {
            if (!is_object($form)) {
                $form = $data['form_name'];
                $classname = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($form);
                $form_class = new $classname();
                $this->form = $form_class;
            } else {
                $this->form = $form;
            }
        }

        if (is_numeric($data) || is_string($data)) {
            $table = $this->utils_get_db_tables('submission');

            $field = 'id_submission';
            if (is_string($data)) {
                $field = 'payment_session_id';
            }

            // TODO: use SubmissionDb class
            $submission = $wpdb->get_row("SELECT * FROM " . $table . " WHERE " . $field . " = '" . $data . "'", ARRAY_A);
            if ($submission !== null) {
                $this->data = $submission;
            } else {
                $redirect_to = $this->form->get_form_page_url('url');
                throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION(__('No submission found', 'btdev_inscriere_text'), ($redirect_to !== false ? $redirect_to : '/'));
            }
        } else {
            $this->data = $data;
        }

        if ($form === null) {
            $form = $this->data['form_name'];
            $classname = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($form);
            $form_class = new $classname();
            $this->form = $form_class;
        }
    }

    // DB DATA
    public function set_submission_data($name = null, $data = null)
    {
        if ($data !== null) {
            $this->data[$name] = $data;
        }
    }

    public function get_entries()
    {
        global $wpdb;

        // TODO: use SubmissionDb class
        if (isset($this->data['id_submission'])) {
            $table = $this->utils_get_db_tables('entry_form', $this->form->name);

            $sql = "SELECT * FROM " . $table . " WHERE id_submission = " . $this->data['id_submission'];
            $entries = $wpdb->get_results($sql, ARRAY_A);

            if ($entries !== null) {
                $this->entries = $entries;
            }
        }
    }

    // ADD TO DB
    public function add_to_db()
    {
        // TODO: use SubmissionDb class
        $id_submission = $this->add_submission();
        $entries = $this->add_entries($id_submission);

        return [
            'id_submission' => $id_submission,
            'id_entries' => $entries,
        ];
    }

    public function add_submission()
    {
        global $wpdb;
        // Coded data
        $fields = [
            'total',
            'currency',
            'payment_status',
            'payment_session_id',
            'payment_session_link',
            'payment_id',
            'invoice_id',
            'referred_from',
            'form_name',
        ];
        $skip_fields = ['html', 'total_row'];

        // Add form fields
        foreach ($this->form->full_data['submission_fields'] as $k => $field) {
            if (array_search($field['type'], $skip_fields) === false) {
                $fields[] = $k;
            }
        }

        foreach ($this->form->full_data['payment_fields'] as $k => $field) {
            if (array_search($field['type'], $skip_fields) === false) {
                $fields[] = $k;
            }
        }


        $redirect_to = '/';
        if (isset($this->data['current_url']) && $this->data['current_url'] !== '') {
            $redirect_to = $this->data['current_url'];
        }

        $data_add = [];
        foreach ($fields as $allowed_field) {
            if (isset($this->data[$allowed_field])) {
                $data_add[$allowed_field] = $this->data[$allowed_field];
            }
        }


        if (isset($this->form->full_data['payment']) && isset($this->form->full_data['payment']['enabled']) && $this->form->full_data['payment']['enabled'] === true) {
        } else {
            $data_add['payment_name'] = 'fara_plata';
        }

        // Save Submission
        $table = $this->utils_get_db_tables('submission');
        $wpdb->insert(
            $table,
            $data_add
        );
        $id_submission = $wpdb->insert_id;

        if ($id_submission === 0) {
            throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION(__('Error saving submitted data.', 'btdev_inscriere_text'), $redirect_to);
        } else {
            return $id_submission;
        }
    }

    public function add_entries(int $id_submission)
    {
        global $wpdb;
        $table = $this->utils_get_db_tables('entry_form', $this->form->name);
        $return_data = [];
        $redirect_to = '/';
        if (isset($this->data['current_url']) && $this->data['current_url'] !== '') {
            $redirect_to = $this->data['current_url'];
        }
        $skip_fields = ['html', 'total_row'];

        if (count($this->data['entries']) >= $this->form->full_data['min_entries']) {
            foreach ($this->data['entries'] as $key => $entry) {
                $data_add = [];
                foreach ($this->form->full_data['repeater_fields'] as $k => $field) {
                    if (array_search($field['type'], $skip_fields) === false) {
                        $data_add[$k] = $entry[$k];
                    }
                }
                $data_add['id_submission'] = $id_submission;
                $wpdb->insert(
                    $table,
                    $data_add
                );
                $id_entry = $wpdb->insert_id;

                if ($id_entry === 0) {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION(
                        sprintf(
                            __('Error saving entry #%s from submitted data.', 'btdev_inscriere_text'),
                            $key
                        ),
                        $redirect_to
                    );
                } else {
                    $return_data[] = $id_entry;
                }
            }
        } else {
            throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION(__('No minimum number entries submitted.', 'btdev_inscriere_text'), $redirect_to);
        }

        return $return_data;
    }

    // Utils
    public function get_taxes()
    {
        $taxes = [];

        // TODO: implement this

        return $taxes;
    }

    public function get_items_price()
    {
        $items = [];
        $base_price = $this->form->full_data['payment']['base_price'];
        $name = $this->form->full_data['payment']['description'];

        if (isset($this->data['entries'])) {
            foreach ($this->data['entries'] as $key => $entry) {
                $price = (int) $base_price;

                foreach ($this->form->full_data['repeater_fields'] as $field_name => $field) {
                    if (isset($entry[$field_name])) {
                        $value = $entry[$field_name];

                        if (isset($field['options']) && isset($field['options'][$value]) && isset($field['options'][$value]['price'])) {
                            $option_price = $field['options'][$value]['price'];
                            if (is_array($option_price)) {
                                if ($option_price['type'] === 'absolute') {
                                    $price = $option_price['value'];
                                } else if ($option_price['type'] === 'relative') {
                                    $price += $option_price['value'];
                                }
                            } else {
                                $price += $option_price;
                            }
                        } elseif (isset($field['price']) && $entry[$field_name] !== '') {
                            $price_field = $field['price'];
                            if (is_array($price_field)) {
                                if ($price_field['type'] === 'absolute') {
                                    $price = $price_field['value'];
                                } else if ($price_field['type'] === 'relative') {
                                    $price += $price_field['value'];
                                }
                            } else {
                                $price += $price_field;
                            }
                        }
                    }
                }

                $items[] = [
                    'price_data' => [
                        'currency' => $this->form->full_data['payment']['currency'],
                        'product_data' => [
                            'name' => $name,
                            // TBD: add participant name to name
                        ],
                        'unit_amount' => $price,
                    ],
                    'quantity' => 1,
                ];
            }
        }

        return $items;
    }

    public function get_quantity()
    {
        return count($this->data['entries']);
    }
}
