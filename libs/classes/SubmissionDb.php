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
        'select' => '*',
        'where' => [],
        'order_by' => [],
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
    public function set_sql_param($param, $value)
    {
        $this->sql[$param] = $value;
    }

    public function prepare_sql_param($param)
    {
        if (in_array($param, ['limit', 'offset'])) {
            return $this->sql[$param];
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

        $sql = "SELECT COUNT(*) FROM " . $this->table_entries . ' ' . $this->prepare_sql_param('where');
        $entries_count = $wpdb->get_var($sql);

        return $entries_count;
    }

    public function get_entries()
    {
        global $wpdb;

        $sql = "SELECT " . $this->prepare_sql_param('select') . " FROM " . $this->table_entries . ' ' . $this->prepare_sql_param('where') . ' ' . $this->prepare_sql_param('order_by') . ' LIMIT ' . $this->prepare_sql_param('limit') . ' OFFSET ' . $this->prepare_sql_param('offset');
        $this->var_dump($sql, true);
        $entries = $wpdb->get_results($sql, ARRAY_A);

        if ($entries !== null) {
            return $entries;
        } else {
            return false;
        }
    }
}
