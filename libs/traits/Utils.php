<?php

namespace BTDEV_INSCRIERI\Traits;

use BTDEV_INSCRIERI\Forms\DefaultData as DEFAULT_DATA;

trait Utils
{
    public function utils_get_absolute_path()
    {
        return __DIR__ . '/../../';
    }

    public function utils_get_absolute_url()
    {
        return plugin_dir_url(dirname(dirname(__FILE__)));
    }

    public function utils_get_plugin_js_var()
    {
        return 'btdev_inscriere_ajax';
    }

    public function utils_get_recaptcha()
    {
        $data = require_once $this->utils_get_absolute_path() . 'secrets.php';
        $mode = $this->utils_is_site_local() ? 'local' : 'live';

        if ($name !== null) {
            return $data['captcha'][$mode];
        } else return false;
    }

    public function utils_get_url_parameter($name = null)
    {
        $temp = [
            'create' => 'btdevCreate',
            'edit' => 'btdevEdit',
            'payment_ok' => 'btdevSuccess',
            'payment_return' => 'btdevReturn',
            'payment_not_ok' => 'btdevError',
        ];

        if ($name !== null) {
            return $temp[$name];
        } else return $temp;
    }

    public function utils_get_forms($null_title = false)
    {
        $forms = [];

        // TBD: very temporary!!!!;
        if ($null_title !== false) {
            $forms[''] = ['title' => __($null_title)];
        }

        $forms['momentum'] = ['title' => 'Momentum'];

        return $forms;
    }

    public function utils_get_db_tables($name = null, $form = null)
    {
        $temp = [
            'submission' => 'btdev_submission',
            'entry_form' => 'btdev_entry_%s',
            'checkins' => 'btdev_checkin',
            'present' => 'btdev_present',
        ];

        if ($name !== null) {
            if ($name !== 'entry_form') {
                return $temp[$name];
            } else {
                $return_val = str_replace('%s', $form, $temp[$name]);

                return $return_val;
            }
        } else return $temp;
    }

    public function utils_get_payment_data($name = null, $mode = 'live')
    {
        $data = require_once $this->utils_get_absolute_path() . 'secrets.php';

        if ($name !== null && isset($data[$name])) {
            return $data[$name][$mode];
        } else return false;
    }

    public function utils_get_payments_stats()
    {
        return [
            'success' => __('Succes', 'btdev_inscriere_text'),
            'waiting_payment' => __('Waiting payment', 'btdev_inscriere_text'),
            'canceled' => __('Canceled', 'btdev_inscriere_text'),
            'canceled_by_us' => __('Canceled by us', 'btdev_inscriere_text')
        ];
    }

    public function var_dump($var, $die = false)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';

        if ($die) {
            die();
        }
    }

    public function utils_get_assets_version()
    {
        if (defined('WP_DEBUG') && WP_DEBUG === true) {
            return date('ymdhis');
        }
        global $wp_version;

        return $wp_version;
    }

    public function utils_encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'btdev_inscriere';
        $secret_iv = 'btdev_inscriere_iv';
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public function utils_is_site_local()
    {
        if (strpos(get_site_url(), '.test') !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function do_redirect($url)
    {
        wp_redirect($url, 301);
        exit;
    }
}
