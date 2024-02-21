<?php

namespace BTDEV_INSCRIERI\Classes\ThirdParty;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Traits\HtmlMessages as BTDEV_INSCRIERI_MESSAGES;

class Captcha
{
    use BTDEV_INSCRIERI_UTILS;
    use BTDEV_INSCRIERI_MESSAGES;

    private $data = null;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function checkCaptcha()
    {
        $return_val = [
            'result' => false,
            'message' => '',
            'redirect_to' => ''
        ];

        if ($this->data !== null) {
            if (!isset($this->data['g-recaptcha-response']) || empty($this->data['g-recaptcha-response'])) {
                $return_val['message'] = __('reCAPTHCA was not resolved.', 'btdev_inscriere_text');
                $return_val['redirect_to'] = $this->data['current_url'];
            } else {
                $recaptcha = $this->utils_get_recaptcha();
                $secret = $recaptcha['secret'];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $this->data['g-recaptcha-response']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response);

                if ($response->success) {
                    $return_val['result'] = true;
                } else {
                    $return_val['message'] = __('reCAPTHCA result was not valid.', 'btdev_inscriere_text');
                    $return_val['redirect_to'] = $this->data['current_url'];
                }
            }
        }

        return $return_val;
    }
}
