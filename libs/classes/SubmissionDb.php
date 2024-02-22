<?php

namespace BTDEV_INSCRIERI\Classes;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

class SubmissionDb
{
    use BTDEV_INSCRIERI_UTILS;

    public $form = null;
    public $table_submissions = null;
    public $table_entries = null;
    public $sql = [
        'select' => ['*'],
        'where' => [],
        'order_by' => [],
        'group_by' => [],
        'limit' => 10,
        'offset' => 0,
    ];

    public function __construct($form = null)
    {
        if (!is_object($form)) {
            $classname = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($form);
            $form_class = new $classname();
            $this->form = $form_class;
        } else {
            $this->form = $form;
        }

        $this->table_submissions = $this->utils_get_db_tables('submission');
        $this->table_entries = $this->utils_get_db_tables('entry_form', $this->form->name);
    }

    // SQL Parameters
    public function generate_sql_from_post($data)
    {
        // Limits
        if (isset($data['start'])) {
            $this->set_sql_param('offset', $data['start']);
        }
        if (isset($data['length'])) {
            $this->set_sql_param('limit', $data['length']);
        }

        // Order by
        if (isset($data['order'])) {
            foreach ($data['order'] as $i => $order) {
                $this->set_sql_param('order_by', [$data['columns'][$order['column']]['name'], strtoupper($order['dir'])]);
            }
        }

        // Search
        if (isset($data['search']) && isset($data['search']['value']) && $data['search']['value'] !== '') {
            foreach ($data['order'] as $i => $order) {
                $this->set_sql_param('order_by', [$data['columns'][$order['column']]['name'], strtoupper($order['dir'])]);
            }
        }

        // Custom data
        if (isset($data['custom_data'])) {
            if (isset($data['custom_data']['payment_status'])) {
                $this->set_sql_param('where', "s.payment_status='" . $_POST['custom_data']['payment_status'] . "'");
            }
        }
    }

    public function set_sql_param($param, $value)
    {
        if (in_array($param, ['limit', 'offset'])) {
            $this->sql[$param] = $value;
        } else {
            $this->sql[$param][] = $value;
        }
    }

    public function prepare_sql_param($param)
    {
        if (in_array($param, ['limit', 'offset'])) {
            if ($this->sql['limit'] !== '-1') {
                $add = ' LIMIT ';
                if ($param === 'offset') {
                    $add = ' OFFSET ';
                }
                return $add . $this->sql[$param];
            } else {
                return '';
            }
        } else if (in_array($param, ['limit', 'offset', 'select', 'order_by'])) {
            if (count($this->sql[$param]) > 0) {
                if ($param === 'select') {
                    return implode(', ', $this->sql[$param]);
                } else if ($param === 'order_by') {
                    $return_val = [];
                    foreach ($this->sql[$param] as $order_by) {
                        $return_val[] = $order_by[0] . ' ' . $order_by[1];
                    }

                    return ' ORDER BY ' . implode(', ', $return_val);
                }
            } else {
                return $this->sql[$param];
            }
        }
    }

    // Submissions
    public function get_submissions_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $this->table_submissions;
        $entries_count = $wpdb->get_var($sql);

        return $entries_count;
    }

    public function get_submissions()
    {
        global $wpdb;

        $sql = "SELECT " . $this->prepare_sql_param('select') . " FROM " . $this->table_submissions;
        $entries = $wpdb->get_results($sql, ARRAY_A);

        if ($entries !== null) {
            return $entries;
        } else {
            return false;
        }
    }

    // Entries
    public function get_entries_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $this->table_entries . $this->prepare_sql_param('where') . $this->prepare_sql_param('group_by');
        $entries_count = $wpdb->get_var($sql);

        return $entries_count;
    }

    public function get_entries()
    {
        global $wpdb;

        $sql = "SELECT " . $this->prepare_sql_param('select') . " FROM " . $this->table_entries . $this->prepare_sql_param('where') . $this->prepare_sql_param('order_by') . $this->prepare_sql_param('group_by') . $this->prepare_sql_param('limit') . $this->prepare_sql_param('offset');
        $this->var_dump($sql);
        $entries = $wpdb->get_results($sql, ARRAY_A);

        if ($entries !== null) {
            return $entries;
        } else {
            return false;
        }
    }
}
