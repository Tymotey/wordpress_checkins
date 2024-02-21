<?php

namespace BTDEV_INSCRIERI\Traits;

trait HtmlMessages
{
    private $messages_var = 'btdev_message';

    public function messages_show_html()
    {
        $html = '';

        if (isset($_SESSION[$this->messages_var]) && count($_SESSION[$this->messages_var]) > 0) {
            $html .= '<div id="form_messages_wrapper">';
            foreach ($_SESSION[$this->messages_var] as $message) {
                $html .= '<div class="message">' . $message . '</div>';
            }
            $html .= '</div>';
            $_SESSION[$this->messages_var] = [];
        }

        return $html;
    }

    public function messages_add($message = null)
    {
        if ($message !== null) {
            $_SESSION[$this->messages_var][] = $message;
        }
    }

    public function messages_reset()
    {
        unset($_SESSION[$this->messages_var]);
    }
}
