<?php

namespace BTDEV_INSCRIERI\Abstracts;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Classes\Submission as BTDEV_INSCRIERI_SUBMISSION;
use BTDEV_INSCRIERI\Exceptions\Submission as BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION;

abstract class Payment
{
    use BTDEV_INSCRIERI_UTILS;

    private $name = '';
    private $title = '';
    private $redirect_to_gateway = false;
    private $entry = null;

    abstract public function __construct();
    public function load_submission(BTDEV_INSCRIERI_SUBMISSION $entry)
    {
        if (is_array($entry)) {
            $this->entry = new BTDEV_INSCRIERI_SUBMISSION($entry, $entry['payment_name']);
        } else {
            $this->entry = $entry;
        }
    }
    public function get_entry()
    {
        return $this->entry;
    }
    public function set_entry($value)
    {
        $this->entry = $value;
    }
    public function get_name()
    {
        return $this->name;
    }
    public function set_name($value)
    {
        $this->name = $value;
    }
    public function get_title()
    {
        return $this->title;
    }
    public function set_title($value)
    {
        $this->title = $value;
    }
    public function get_redirect_to_gateway()
    {
        return $this->redirect_to_gateway;
    }
    public function set_redirect_to_gateway($value)
    {
        $this->redirect_to_gateway = $value;
    }
    public function get_payment_data($mode)
    {
        $data_gateway = $this->utils_get_payment_data($this->get_name(), $mode);
        if ($data_gateway !== false) {
            return $data_gateway;
        } else {
            throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION(__('Incorrect payment configuration.', 'btdev_inscriere_text'), $this->entry->form->get_form_page_url('url'));
        }
    }
    abstract public function prepare_payment();
    abstract public function success_payment();
    abstract public function failure_payment();
}
