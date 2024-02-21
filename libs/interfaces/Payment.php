<?php

namespace BTDEV_INSCRIERI\Interfaces;

use BTDEV_INSCRIERI\Classes\Submission as BTDEV_INSCRIERI_SUBMISSION;

interface Payment
{
    public function __construct();
    public function load_submission(BTDEV_INSCRIERI_SUBMISSION $entry);
    public function prepare_payment();
    public function success_payment();
    public function failure_payment();
}
