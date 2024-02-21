<?php

namespace BTDEV_INSCRIERI\Api;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

abstract class DefaultData
{
    use BTDEV_INSCRIERI_UTILS;

    public function prepare_return()
    {
        return array(
            'data' => [],
            'status' => false,
            'message' => __('Incorrect parameters sent.', 'bbso_form'),
        );
    }

    public function prepare_return_table()
    {
        return array(
            'draw' => '',
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
            "error" => null
        );
    }

    public function return_data($return_val, bool $json = true, bool $die = true): void
    {
        if ($json) {
            wp_send_json($return_val);
        } else {
            echo $return_val;
        }

        if ($die) {
            wp_die();
        }
    }
}
