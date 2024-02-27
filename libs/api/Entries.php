<?php

namespace BTDEV_INSCRIERI\Api;

use BTDEV_INSCRIERI\Exceptions\Api as BTDEV_INSCRIERI_EXCEPTIONSAPI;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Api\DefaultData as DEFAULT_DATA;
use BTDEV_INSCRIERI\Classes\SubmissionDb as BTDEV_INSCRIERI_SUBMISSIONDB;
use BTDEV_INSCRIERI\Classes\Tables as BTDEV_INSCRIERI_TABLES;
use Exception;

class Entries extends DEFAULT_DATA
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct()
    {
    }

    public function add_ajax_handles()
    {
        add_action('wp_ajax_btdev_inscrieri_entry_delete', array($this, 'delete'));
        add_action('wp_ajax_nopriv_btdev_inscrieri_entry_delete', array($this, 'delete'));
    }

    public function delete()
    {
        $return_val = $this->prepare_return();

        try {
            // $this->var_dump($_POST, true);
            if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'btdev_inscrieri_entry_delete')) {
                if (isset($_POST['idEntry']) && $_POST['idEntry'] !== '') {
                    if (isset($_POST['idPayment']) && $_POST['idPayment'] !== '') {
                        $id_entry = (int) sanitize_text_field($_POST['idEntry']);
                        $id_payment = (int) sanitize_text_field($_POST['idPayment']);
                        $submissionDb = new BTDEV_INSCRIERI_SUBMISSIONDB($id_payment);
                        $submissionDb->delete_entry($id_entry);

                        $return_val['status'] = true;
                        $return_val['message'] = __('Entry #' . $id_entry . ' deleted.', 'btdev_inscriere_text');
                    } else {
                        throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('No payment id sent');
                    }
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('No entry id sent');
                }
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('Incorrect nonce. Refresh the page and try again.');
            }
        } catch (BTDEV_INSCRIERI_EXCEPTIONSAPI $e) {
            $return_val['message'] = 'API Entries: ' . __($e->message, 'btdev_inscriere_text');
        } catch (Exception $e) {
            $return_val['message'] = 'API: ' . __($e->getMessage(), 'btdev_inscriere_text');
        }


        $this->return_data($return_val);
    }
}
