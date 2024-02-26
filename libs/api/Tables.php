<?php

namespace BTDEV_INSCRIERI\Api;

use BTDEV_INSCRIERI\Exceptions\Api as BTDEV_INSCRIERI_EXCEPTIONSAPI;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Api\DefaultData as DEFAULT_DATA;
use BTDEV_INSCRIERI\Classes\SubmissionDb as BTDEV_INSCRIERI_SUBMISSIONDB;
use BTDEV_INSCRIERI\Classes\Tables as BTDEV_INSCRIERI_TABLES;
use Exception;

class Tables extends DEFAULT_DATA
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct()
    {
    }

    public function add_ajax_handles()
    {
        add_action('wp_ajax_btdev_inscrieri_table_operations', array($this, 'operations'));
        add_action('wp_ajax_nopriv_btdev_inscrieri_table_operations', array($this, 'operations'));
    }

    public function operations()
    {
        $return_val = $this->prepare_return_table();

        try {
            if (isset($_GET['table_type']) && $_GET['table_type'] !== '') {
                $table_type = $_GET['table_type'];

                if (isset($_GET['form_type']) && $_GET['form_type'] !== '') {
                    $form_name = $_GET['form_type'];
                    $classname = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($form_name);
                    $form_class = new $classname();

                    $table_class = new BTDEV_INSCRIERI_TABLES($form_class, $table_type);
                    $fields = $table_class->get_fields();

                    if (count($fields) > 0) {
                        $submissions_db_class = new BTDEV_INSCRIERI_SUBMISSIONDB($form_name);
                        $submissions_db_class->generate_sql_from_post($_POST, $table_class);
                        $field_id = 'id_entry';

                        if ($table_type === 'submissions') {
                        } else {
                            $entries = $submissions_db_class->get_entries();
                        }
                        // $this->var_dump($entries, true);

                        if ($entries !== false) {
                            $total_count = $submissions_db_class->get_entries_count();
                            $return_val['recordsFiltered'] = (int) $total_count; // IF search => count($entries);
                            $return_val['recordsTotal'] = (int) $total_count;
                            $return_val['data'] = [];
                            foreach ($entries as $entry) {
                                $entry_row = [
                                    "DT_RowId" => "row_" . $entry[$field_id],
                                    "DT_RowData" => [
                                        "pkey" => $entry[$field_id]
                                    ]
                                ];
                                foreach ($fields as $k_field => $field) {
                                    $field_data = $table_class->get_field_from_form($form_class, $k_field);
                                    $field_html = $table_class->get_column_html($k_field, $field_data, $entry);
                                    $entry_row[$k_field] = $field_html;
                                }
                                $return_val['data'][] = $entry_row;
                            }
                        } else {
                            $return_val['data'] = null;
                        }
                    } else {
                        throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('No fields selected.');
                    }
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('No form type sent.');
                }
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('No table type sent.');
            }

            $return_val['draw'] = $_POST['draw'];
            unset($return_val['message']);
        } catch (BTDEV_INSCRIERI_EXCEPTIONSAPI $e) {
            $return_val['message'] = 'API: ' . __($e->message, 'btdev_inscriere_text');
        } catch (Exception $e) {
            $return_val['message'] = 'API: ' . __($e->getMessage(), 'btdev_inscriere_text');
        }


        $this->return_data($return_val);
    }
}
