<?php

namespace BTDEV_INSCRIERI\Api;

use BTDEV_INSCRIERI\Exceptions\Api as BTDEV_INSCRIERI_EXCEPTIONSAPI;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Api\DefaultData as DEFAULT_DATA;
use BTDEV_INSCRIERI\Classes\SubmissionDb as BTDEV_INSCRIERI_SUBMISSIONDB;
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

        // add_action('wp_ajax_bbso_form_add_checkin', 'bbso_form_add_checkin_action');
        // add_action('wp_ajax_nopriv_bbso_form_add_checkin', 'bbso_form_add_checkin_action');
        // add_action('wp_ajax_bbso_form_delete_checkin', 'bbso_form_delete_checkin_action');
        // add_action('wp_ajax_nopriv_bbso_form_delete_checkin', 'bbso_form_delete_checkin_action');
        // add_action('wp_ajax_bbso_schedule', 'bbso_ajax_schedule_action');
        // add_action('wp_ajax_nopriv_bbso_schedule', 'bbso_ajax_schedule_action');

        // add_action('wp_ajax_bbso_form_delete_person', 'bbso_form_delete_person_action');
        // add_action('wp_ajax_bbso_form_pay_person', 'bbso_form_pay_person_action');
        // add_action('wp_ajax_bbso_form_unpay_person', 'bbso_form_unpay_person_action');
    }

    public function operations()
    {
        $return_val = $this->prepare_return_table();

        try {
            if (isset($_GET['table_type']) && $_GET['table_type'] !== '') {
                $submissions_db_class = new BTDEV_INSCRIERI_SUBMISSIONDB($_GET['form_type']);
                if (isset($_POST['custom_data']['payment_status'])) {
                    $submissions_db_class->set_sql_param('where', "s.payment_status='" . $_POST['custom_data']['payment_status'] . "'");
                }

                if ($_GET['table_type'] === 'submissions') {
                } else {
                    $submissions_db_class->get_entries();
                }
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('No table type sent.');
            }

            $return_val['draw'] = $_POST['draw'];
            unset($return_val['error']);

            // $this->var_dump($_GET);
            // $this->var_dump($_POST, true);
            // $this->var_dump($return_val, true);
        } catch (BTDEV_INSCRIERI_EXCEPTIONSAPI $e) {
            $return_val['error'] = 'API: ' . __($e->message, 'btdev_inscriere_text');
        } catch (Exception $e) {
            $return_val['error'] = 'API: ' . __($e->getMessage(), 'btdev_inscriere_text');
        }


        $this->return_data($return_val);
    }
}
