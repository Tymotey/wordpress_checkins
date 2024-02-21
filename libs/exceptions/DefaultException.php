<?php

namespace BTDEV_INSCRIERI\Exceptions;

abstract class DefaultException extends \Exception
{
    public $message = '';
    public $redirect_to = '';

    public function __construct($message, $redirect_to = null)
    {
        $this->message = $message;

        if ($redirect_to !== null) {
            $this->redirect_to = $redirect_to;
        }
    }
}
