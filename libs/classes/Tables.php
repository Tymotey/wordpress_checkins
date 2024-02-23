<?php

namespace BTDEV_INSCRIERI\Classes;

use BTDEV_INSCRIERI\Exceptions\Table as BTDEV_INSCRIERI_EXCEPTIONSTABLE;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Traits\HtmlMessages as BTDEV_INSCRIERI_MESSAGES;

class Tables
{
    use BTDEV_INSCRIERI_UTILS;
    use BTDEV_INSCRIERI_MESSAGES;

    public $form = null;
    public $table_type = null;

    public function __construct($form = null, $table_type = null)
    {
        if ($form !== null) {
            $this->form = $form;
        }
        if ($table_type !== null) {
            $this->table_type = $table_type;
        }

        if ($form !== null && $table_type !== null) {
            $this->sort_columns_by_order();
        }
    }

    public function sort_columns_by_order()
    {
        foreach ($this->form->full_data['tables'] as $k_t => $table) {
            $order = array();
            foreach ($this->form->full_data['tables'][$k_t]['fields'] as $k_f => $field) {
                $order[$k_f] = $field['order'];
            }
            array_multisort($order, SORT_ASC, $this->form->full_data['tables'][$k_t]['fields']);
        }
    }

    public function get_table_types($show_admin = true)
    {
        $data = [
            'entries_public' => ['title' => __('Entries - public data', 'btdev_inscriere_text'), 'show_admin' => true],
            'entries_admin' => ['title' => __('Entries - private data', 'btdev_inscriere_text'), 'show_admin' => true],
            'submissions' => ['title' => __('All payments', 'btdev_inscriere_text'), 'show_admin' => true],
            'checkins' => ['title' => __('Checkins', 'btdev_inscriere_text'), 'show_admin' => true],
            'presents' => ['title' => __('Presents', 'btdev_inscriere_text'), 'show_admin' => true],
        ];
        $return_val = [];

        if ($show_admin !== null) {
            foreach ($data as $k => $d) {
                if ($d['show_admin'] === $show_admin) {
                    $return_val[$k] = $d['title'];
                }
            }
        } else {
            $return_val = $data;
        }

        return $return_val;
    }

    public function get_filters_html($filters = [])
    {
        $html = '';
        if (count($filters) > 0) {
            $html .= '<div id="filters_wrapper">';
            foreach ($filters as $filter) {
                if ($filter === 'status') {
                    $html .= '<div class="filter_row">
                        <label>' . __('Choose payment status', 'btdev_inscriere_text') . ': </label>
                        <select id="filter_payment_status">';
                    $statuses = $this->utils_get_payments_stats();
                    foreach ($statuses as $k => $status) {
                        $html .= '<option value="' . $k . '">' . $status . '</option>';
                    }
                    $html .= '</select>
                    </div>';
                }
            }
            $html .= '</div>';
        }

        return $html;
    }

    public function get_fields()
    {
        $fields = false;

        if (isset($this->form->full_data['tables'][$this->table_type]) && count($this->form->full_data['tables'][$this->table_type]['fields']) > 0) {
            $fields = [];
            $fields = $this->form->full_data['tables'][$this->table_type]['fields'];
        }

        return $fields;
    }

    public function get_sort()
    {
        if (isset($this->form->full_data['tables'][$this->table_type]['sort_by'])) {
            return $this->form->full_data['tables'][$this->table_type]['sort_by'];
        } else {
            return false;
        }
    }

    public function generate_table()
    {
        $html = '';
        $fields = $this->get_fields();

        if (count($fields) > 0) {
            $columns_header = [];
            $columns_data = [];
            foreach ($fields as $k => $field) {
                // Header
                $columns_header[] = '<th>' . (isset($field['title']) ? $field['title'] : $k) . '</th>';

                // Settings
                $data = ['name' => $k, 'data' => $k];
                if (isset($field['settings']) || isset($field['settings'])) {
                    if (count($field['settings']) > 0) {
                        $data = array_replace_recursive($data, $field['settings']);
                    }
                }
                $columns_data[] = $data;
            }

            if (count($columns_header) > 0) {
                $columns_header = implode('', $columns_header);
            } else {
                $columns_header = false;
            }

            if ($columns_header !== false) {
                $sort_data = $this->get_sort();

                $html .= '<div class="table_wrapper">
                    ' . $this->get_filters_html(['status']) . '
                    <table id="btdev_inscrieri_table_' . $this->table_type . '" class="btdev_inscrieri_table">
                        <thead>
                            <tr>' . $columns_header . '</tr>
                        </thead>
                        <tfoot>
                            <tr>' . $columns_header . '</tr>
                        </tfoot>
                    </table>
                    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
                    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            let dataTablesSettings = {
                                ajax: {
                                    url: btdev_inscriere_ajax.ajax_url + "?action=btdev_inscrieri_table_operations&table_type=' . $this->table_type . '&form_type=' . $this->form->name . '",
                                    type: "POST",
                                    data: function (d) {
                                        return $.extend({}, d, {
                                            custom_data: {
                                                payment_status: $("#filter_payment_status").val()
                                            }
                                        });
                                    }
                                },
                                processing: true,
                                serverSide: true,
                                ' . ($columns_data !== false ? 'columns: ' . json_encode($columns_data) . ',' : '') . '
                                ' . ($sort_data !== false ? 'order: ' . json_encode($sort_data) . ',' : '') . '
                                stateSave: true,
                                scrollX: true,
                                fixedHeader: true,
                            };
                            ' . (isset($this->form->full_data['tables'][$this->table_type]['settings']) ? 'dataTablesSettings = {...dataTablesSettings, ...' . json_encode($this->form->full_data['tables'][$this->table_type]['settings']) . '};' : '') . '
                            window["datatableBTDEV"] = $("#btdev_inscrieri_table_' . $this->table_type . '").DataTable(dataTablesSettings);
                        });
                    </script>
                </div>';
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSTABLE('No columns added');
            }
        } else {
            throw new BTDEV_INSCRIERI_EXCEPTIONSTABLE('No fields found in table');
        }

        return $html;
    }

    public function get_field_from_form($form, $field)
    {
        $return_val = false;

        if (isset($form->full_data['repeater_fields']) && isset($form->full_data['repeater_fields'][$field])) {
            return $form->full_data['repeater_fields'][$field];
        }

        return $return_val;
    }

    public function get_column_html($field, $field_data, $entry)
    {
        if ($field === 'total') {
            return $entry['total'] . $entry['currency'];
        } else if ($field === 'payment_status') {
            $statuses = $this->utils_get_payments_stats();
            return $statuses[$entry['payment_status']];
        } else if ($field === 'payment_invoice') {
            $return_data = $entry['payment_invoice'];
            if ($return_data === 'yes') {
                $return_data = $this->get_invoice_data($entry);
            } else {
                $return_data = 'No';
            }

            return $return_data;
        } else if ($field === 'actions') {
            // TODO: add actions
            $html = 'asdcascas';

            return $html;
        } else {
            if (in_array($field_data['type'], ['select', 'radio', 'checkbox'])) {
                $found = null;
                foreach ($field_data['options'] as $k => $option) {
                    if ($k === $entry[$field]) {
                        $found = $option['title'];
                    }
                }

                if ($found === null) {
                    return $entry[$field];
                } else {
                    return $found;
                }
            } else {
                return $entry[$field];
            }
        }
    }

    public function get_invoice_data($entry, $string = true)
    {
        $invoice_data = [];

        if (isset($entry['company_name']) && $entry['company_name'] !== '') {
            $invoice_data[] = __('Company', 'btdev_inscriere_text') . ': ' . $entry['company_name'];
        }
        if (isset($entry['company_cui']) && $entry['company_cui'] !== '') {
            $invoice_data[] = $entry['company_cui'];
        }
        if (isset($entry['company_j']) && $entry['company_j'] !== '') {
            $invoice_data[] = $entry['company_j'];
        }
        if (isset($entry['company_delegate']) && $entry['company_delegate'] !== '') {
            $invoice_data[] = $entry['company_delegate'];
        }

        if ($string) return implode(', ', $invoice_data);
        else return $invoice_data;
    }
}
