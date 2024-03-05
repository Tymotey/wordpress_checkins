<?php

namespace BTDEV_INSCRIERI\Api;

use BTDEV_INSCRIERI\Exceptions\Api as BTDEV_INSCRIERI_EXCEPTIONSAPI;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Api\DefaultData as DEFAULT_DATA;
use BTDEV_INSCRIERI\Classes\SubmissionDb as BTDEV_INSCRIERI_SUBMISSIONDB;
use BTDEV_INSCRIERI\Classes\Tables as BTDEV_INSCRIERI_TABLES;
use Exception;

class Submissions extends DEFAULT_DATA
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct()
    {
    }

    public function add_ajax_handles()
    {
        add_action('wp_ajax_btdev_inscrieri_submission_delete', array($this, 'delete'));
        add_action('wp_ajax_nopriv_btdev_inscrieri_submission_delete', array($this, 'delete'));
        add_action('wp_ajax_btdev_inscrieri_submission_cancel', array($this, 'cancel'));
        add_action('wp_ajax_nopriv_btdev_inscrieri_submission_cancel', array($this, 'cancel'));
    }

    public function delete()
    {
        $return_val = $this->prepare_return();

        try {
            if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'btdev_inscrieri_submission_delete')) {
                $id_submission = (int) sanitize_text_field($_POST['idSubmission']);
                $submissionDb = new BTDEV_INSCRIERI_SUBMISSIONDB($id_submission);
                $deleted_entries = $submissionDb->delete_entries($id_submission);
                $deleted_submission = $submissionDb->delete_submission($id_submission);
                if ($deleted_entries && $deleted_submission) {
                    $return_val['status'] = true;
                    $return_val['message'] = __('Submission #' . $id_submission . ' deleted.', 'btdev_inscriere_text');
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('Cannot delete submission');
                }
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('Incorrect nonce');
            }
        } catch (BTDEV_INSCRIERI_EXCEPTIONSAPI $e) {
            $return_val['message'] = 'API Submissions: ' . __($e->message, 'btdev_inscriere_text');
        } catch (Exception $e) {
            $return_val['message'] = 'API: ' . __($e->getMessage(), 'btdev_inscriere_text');
        }


        $this->return_data($return_val);
    }

    public function cancel()
    {
        $return_val = $this->prepare_return();

        try {
            if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'btdev_inscrieri_submission_cancel')) {
                $id_submission = (int) sanitize_text_field($_POST['idSubmission']);
                $submissionDb = new BTDEV_INSCRIERI_SUBMISSIONDB($id_submission);
                if ($submissionDb->cancel_submission($id_submission)) {
                    $return_val['status'] = true;
                    $return_val['message'] = __('Submission #' . $id_submission . ' canceled.', 'btdev_inscriere_text');
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('Cannot cancel submission');
                }
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSAPI('Incorrect nonce');
            }
        } catch (BTDEV_INSCRIERI_EXCEPTIONSAPI $e) {
            $return_val['message'] = 'API Submissions: ' . __($e->message, 'btdev_inscriere_text');
        } catch (Exception $e) {
            $return_val['message'] = 'API: ' . __($e->getMessage(), 'btdev_inscriere_text');
        }


        $this->return_data($return_val);
    }
}
