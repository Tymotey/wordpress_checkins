<?php

namespace BTDEV_INSCRIERI\Classes\ThirdParty;

use BTDEV_INSCRIERI\Abstracts\Payment as A_PAYMENT;
use BTDEV_INSCRIERI\Interfaces\Payment as I_PAYMENT;
use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Classes\Submission as BTDEV_INSCRIERI_SUBMISSION;

class OrdinPlata extends A_PAYMENT implements I_PAYMENT
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct()
    {
        $this->set_name('ordinPlata');
        $this->set_title('Ordin de Plata');
        $this->set_redirect_to_gateway(false);
    }

    public function prepare_payment()
    {
        $entry = $this->get_entry();
        $items = $entry->get_items_price();

        // Price for each items AND Totals
        $total = 0;
        if (count($items) > 0) {
            foreach ($items as $item) {
                $total += $item['price_data']['unit_amount'];
            }
        }

        return [
            'total' => $total,
            'currency' => $entry->form->full_data['payment']['currency'],
            'payment_name' => $this->get_name(),
            'payment_status' => 'waiting_payment',
            'payment_session_id' => date('Y-m-d-h-i-s'),
            'payment_session_link' => '',
        ];
    }

    public function success_payment()
    {
    }

    public function failure_payment()
    {
    }
}
