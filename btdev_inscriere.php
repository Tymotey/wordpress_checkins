<?php

/**
 * BTDEV - Form Inscriere Stripe
 *
 * @package btdev_insciere
 * @author            Timotei
 * @copyright         2023 Bondas
 * @license           GPL-2.0-or-late
 *
 * Plugin Name: Form Inscriere + Stripe
 * Plugin URI: https://bondas.ro/
 * Description: Form inscriere integrat cu Stripe
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.1
 * Author: Bondas Timotei
 * Author URI: https://bondas.ro/
 * License: GPLv2 or later
 * Text Domain: btdev_inscriere_text
 * Domain Path: /languages
 */

namespace BTDEV_INSCRIERI;

use BTDEV_INSCRIERI\Exceptions\Submission as BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION;
use BTDEV_INSCRIERI\Exceptions\Payment as BTDEV_INSCRIERI_EXCEPTIONSPAYMENT;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Traits\HtmlMessages as BTDEV_INSCRIERI_MESSAGES;

use BTDEV_INSCRIERI\Api\Tables as BTDEV_INSCRIERI_API_TABLES;
use BTDEV_INSCRIERI\Api\Entries as BTDEV_INSCRIERI_API_ENTRIES;
use BTDEV_INSCRIERI\Api\Submissions as BTDEV_INSCRIERI_API_SUBMISSIONS;
use BTDEV_INSCRIERI\Classes\Shortcodes as BTDEV_INSCRIERI_SHORTCODES;
use BTDEV_INSCRIERI\Classes\Submission as BTDEV_INSCRIERI_SUBMISSION;
use BTDEV_INSCRIERI\Classes\Tables as BTDEV_INSCRIERI_TABLES;
use BTDEV_INSCRIERI\Classes\ThirdParty\Captcha as BTDEV_INSCRIERI_THIRDPARTY_CAPTCHA;

if (!defined('ABSPATH')) {
    exit;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once 'vendor/autoload.php';

class Main
{
    use BTDEV_INSCRIERI_UTILS;
    use BTDEV_INSCRIERI_MESSAGES;

    public function __construct()
    {
        $this->load_module_hooks();
        // TODO: Install script
    }

    public function load_module_hooks()
    {
        $this->add_scripts_css();
        add_action('init', array($this, 'add_shortcodes'));
        add_action('init', array($this, 'add_get_actions'));
        add_action('wp_footer', array($this, 'add_footer_notification'));
        add_action('init', array(new BTDEV_INSCRIERI_API_TABLES(), 'add_ajax_handles'));
        add_action('init', array(new BTDEV_INSCRIERI_API_ENTRIES(), 'add_ajax_handles'));
        add_action('init', array(new BTDEV_INSCRIERI_API_SUBMISSIONS(), 'add_ajax_handles'));

        add_action('admin_init', array($this, 'load_integrations'));
    }

    public function add_scripts_css()
    {
        add_action('wp_enqueue_scripts', array($this, 'init_assets'));
        add_action('admin_enqueue_scripts', array($this, 'admin_init_assets'));
    }

    public function common_assets($is_admin = false)
    {
        $handle = 'btdev_inscriere_main' . ($is_admin ? '_admin' : '') . '_script';
        $data = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'translation' => [
                'participant' => __('Participant', 'btdev_inscriere_text'),
                'you_must_have_at_least_one_entry' => __('You must have at least one participant.', 'btdev_inscriere_text'),
                'are_you_sure_you_want_to_delete' => __('Are you sure you want to delete?', 'btdev_inscriere_text'),
                'please_confirm_the_action' => __('Please confirm the action.', 'btdev_inscriere_text'),
            ]
        ];

        if (is_admin()) {
            $data['forms'] = $this->utils_get_forms('Choose a form');
            $table = new BTDEV_INSCRIERI_TABLES();
            $data['tables'] = $table->get_table_types();
        }

        wp_localize_script($handle, $this->utils_get_plugin_js_var(), $data);
    }

    public function init_assets()
    {
        wp_enqueue_style('btdev_inscriere_datatable', '//cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css', array(), '2.0.1', 'all');
        wp_enqueue_style('btdev_inscriere_main', $this->utils_get_absolute_url() . 'assets/style.css', array(), $this->utils_get_assets_version(), 'all');

        wp_enqueue_script('btdev_inscriere_recaptcha', 'https://www.google.com/recaptcha/api.js', array(), '1.0', array('in_footer' => true, 'strategy' => 'async'));
        wp_enqueue_script('btdev_inscriere_datatable', '//cdn.datatables.net/2.0.1/js/dataTables.min.js', array('jquery'), '2.0.1', true);
        wp_enqueue_script('btdev_inscriere_datatable_responsive', 'https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js', array('jquery'), '2.5.0', true);
        wp_enqueue_script('btdev_inscriere_datatable_fixed', 'https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js', array('jquery'), '3.4.0', true);
        wp_enqueue_script('btdev_inscriere_main_script', $this->utils_get_absolute_url() . 'assets/script.js', array('jquery'), $this->utils_get_assets_version(), true);
        $this->common_assets();

        if (!wp_style_is('dashicons')) {
            wp_enqueue_style('dashicons');
        }
    }

    public function admin_init_assets()
    {
        wp_enqueue_script('btdev_inscriere_main_admin_script', $this->utils_get_absolute_url() . 'assets/admin/script.js', array('jquery'), $this->utils_get_assets_version(), true);
        $this->common_assets(true);
    }

    public function add_footer_notification()
    {
        echo '<div id="btdev_popup_notifications"></div>';
    }

    public function add_shortcodes()
    {
        $shortcodes = new BTDEV_INSCRIERI_SHORTCODES();

        // General
        add_shortcode('bbdev_inscrieri_list_entries', array($shortcodes, 'list_entries'));
        add_shortcode('bbdev_inscrieri_list_payments', array($shortcodes, 'list_payments'));
        // For forms
        add_shortcode('bbdev_inscrieri_form', array($shortcodes, 'form'));
        // TODO: must do edit
        add_shortcode('bbdev_inscrieri_form_edit', array($shortcodes, 'form'));
        // For email
        add_shortcode('bbdev_inscrieri_entry_summary', array($shortcodes, 'submission_summary'));
    }

    public function add_get_actions()
    {
        if (isset($_POST['form_type']) && $_POST['form_type'] === 'btdev_inscrieri') {
            $redirect_to = isset($_POST['current_url']) ? $_POST['current_url'] : '/';
            $return_data = $_POST;

            try {
                if (isset($_GET[$this->utils_get_url_parameter('create')])) {
                    if (isset($return_data['form_name']) && $return_data['form_name'] !== '') {
                        // Form Class
                        $form_name = $return_data['form_name'];
                        $classname = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($form_name);
                        $form_class = new $classname();

                        // Create Submission class and data
                        $submission = new BTDEV_INSCRIERI_SUBMISSION($return_data, $form_class);

                        // throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION(__('Asa vreau', 'btdev_inscriere_text'), $redirect_to);

                        // CAPTCHA
                        if (isset($form_class->full_data['captcha']) && isset($form_class->full_data['captcha']['enabled']) && $form_class->full_data['captcha']['enabled'] === true) {
                            $captcha_class = new BTDEV_INSCRIERI_THIRDPARTY_CAPTCHA($return_data);
                            $captcha_result = $captcha_class->checkCaptcha();

                            if ($captcha_result['result'] !== true) {
                                $redirect_to = $captcha_result['redirect_to'];

                                throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION($captcha_result['message'], $redirect_to);
                            }
                        }

                        // Terms agree test
                        if (isset($form_class->full_data['tandc']) && isset($form_class->full_data['tandc']['enabled']) && $form_class->full_data['tandc']['enabled'] === true) {
                            if (!isset($return_data['agree_tac']) || $return_data['agree_tac'] !== 'true') {
                                throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION('You must accept terms and conditions.', $redirect_to);
                            }
                        }

                        // Have payment?
                        $redirect_to_gateway = false;
                        if (isset($form_class->full_data['payment']) && isset($form_class->full_data['payment']['enabled']) && $form_class->full_data['payment']['enabled'] === true) {
                            $classname = 'BTDEV_INSCRIERI\\Classes\\ThirdParty\\' . ucfirst($return_data['payment_name']);
                            if (class_exists($classname)) {
                                $payment_class = new $classname();
                                $payment_class->load_submission($submission);
                                $redirect_to_gateway = $payment_class->get_redirect_to_gateway();
                            } else {
                                throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION('Payment method not found.', $redirect_to);
                            }

                            $payment = $payment_class->prepare_payment();
                            foreach ($payment as $key => $field) {
                                $submission->set_submission_data($key, $field);
                            }
                        } else {
                            $submission->set_submission_data('payment_status', 'success');
                        }

                        // Save to DB
                        $db_data = $submission->add_to_db();

                        // Redirect to gateway OR site success
                        if ($redirect_to_gateway === true && $submission->data['payment_session_link'] !== '') {
                            $this->do_redirect($submission->data['payment_session_link']);
                        } else {
                            $url = $form_class->get_form_page_url('success');
                            $this->do_redirect($url);
                        }
                    } else {
                        throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION('No form name sent.', $redirect_to);
                    }
                }
            } catch (BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION $e) {
                $_SESSION['btdev_saved_data'] = $return_data;
                $this->messages_add(__($e->message, 'btdev_inscriere_text'));
                $this->do_redirect($e->redirect_to);
            } catch (\Exception $e) {
                $_SESSION['btdev_saved_data'] = $return_data;
                $this->messages_add(
                    sprintf(
                        __('Unexpected exception: %s.', 'btdev_inscriere_text'),
                        $e->getMessage()
                    )
                );
                $this->do_redirect($redirect_to);
            }
        }

        // Return from Stripe on cancel + embed back button
        if (isset($_GET[$this->utils_get_url_parameter('payment_return')]) && isset($_GET['session_id'])) {
            global $wpdb;
            $session_id = $_GET['session_id'];

            $table = $this->utils_get_db_tables('submission');
            $submission_db = $wpdb->get_row("SELECT * FROM " . $table . " WHERE payment_session_id = '" . $session_id . "'", ARRAY_A);
            try {
                if ($submission_db !== null) {
                    $classnameForm = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($submission_db['form_name']);
                    $form_class = new $classnameForm();
                    $submission = new BTDEV_INSCRIERI_SUBMISSION($submission_db, $form_class);
                    $classname = 'BTDEV_INSCRIERI\\Classes\\ThirdParty\\' . ucfirst($submission->data['payment_name']);
                    if (class_exists($classname)) {
                        $payment_class = new $classname();
                        $payment_class->load_submission($submission);
                        $payment_class->failure_payment();
                    } else {
                        throw new BTDEV_INSCRIERI_EXCEPTIONSPAYMENT('No payment class found.');
                        $this->do_redirect('/');
                    }
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSPAYMENT('No submission found.');
                    $this->do_redirect('/');
                }
            } catch (BTDEV_INSCRIERI_EXCEPTIONSPAYMENT $e) {
                $this->messages_add(__($e->message, 'btdev_inscriere_text'));
            } catch (\Exception $e) {
                $this->messages_add(
                    sprintf(
                        __('Unexpected exception: %s.', 'btdev_inscriere_text'),
                        $e->getMessage()
                    )
                );
            }
        }

        // Return from Stripe on success
        if (isset($_GET[$this->utils_get_url_parameter('payment_ok')]) && isset($_GET['session_id'])) {
            global $wpdb;
            $session_id = $_GET['session_id'];

            $table = $this->utils_get_db_tables('submission');
            $submission_db = $wpdb->get_row("SELECT * FROM " . $table . " WHERE payment_session_id = '" . $session_id . "'", ARRAY_A);
            try {
                if ($submission_db !== null) {
                    $classnameForm = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($submission_db['form_name']);
                    $form_class = new $classnameForm();
                    $submission = new BTDEV_INSCRIERI_SUBMISSION($submission_db, $form_class);
                    $classname = 'BTDEV_INSCRIERI\\Classes\\ThirdParty\\' . ucfirst($submission->data['payment_name']);
                    if (class_exists($classname)) {
                        $payment_class = new $classname();
                        $payment_class->load_submission($submission);
                        $payment_class->success_payment();
                    } else {
                        throw new BTDEV_INSCRIERI_EXCEPTIONSPAYMENT('No payment class found.');
                        $this->do_redirect('/');
                    }
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSPAYMENT('No submission found.');
                    $this->do_redirect('/');
                }
            } catch (BTDEV_INSCRIERI_EXCEPTIONSPAYMENT $e) {
                $this->messages_add(__($e->message, 'btdev_inscriere_text'));
            } catch (\Exception $e) {
                $this->messages_add(
                    sprintf(
                        __('Unexpected exception: %s.', 'btdev_inscriere_text'),
                        $e->getMessage()
                    )
                );
            }
        }
    }

    public function load_integrations()
    {
        require_once 'integrations/Gutenberg/integrate.php';

        if (is_plugin_active('elementor/elementor.php')) {
            require_once 'integrations/Elementor/integrate.php';
        }
    }
}

$bt_dev_main = new Main();
