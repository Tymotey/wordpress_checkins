<?php

namespace BTDEV_INSCRIERI\Classes\ThirdParty;

use BTDEV_INSCRIERI\Abstracts\Payment as A_PAYMENT;
use BTDEV_INSCRIERI\Interfaces\Payment as I_PAYMENT;
use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Classes\Submission as BTDEV_INSCRIERI_SUBMISSION;

class Stripe extends A_PAYMENT implements I_PAYMENT
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct()
    {
        $this->set_name('stripe');
        $this->set_title('Card bancar');
        $this->set_redirect_to_gateway(true);
    }

    public function prepare_payment()
    {
        $entry = $this->get_entry();
        $mode = $entry->form->full_data['mode'];
        $data_gateway = $this->get_payment_data($mode);
        $link_ok = $entry->form->get_form_page_url('success');
        $link_cancel = $entry->form->get_form_page_url('cancel');
        $stripe = new \Stripe\StripeClient($data_gateway['secret_key']);
        $taxes = $entry->get_taxes();
        $items = $entry->get_items_price();

        $checkout_session = $stripe->checkout->sessions->create([
            'payment_intent_data' => [
                'description' => $entry->form->full_data['payment']['description'],
                'metadata' => [
                    'payment_for' => $entry->form->full_data['payment']['payment_for'],
                ],
            ],
            'customer_email' => $entry->data['sub_email'],
            'line_items' => $items,
            'mode' => 'payment',
            'success_url' => get_site_url() . $link_ok . "?" . $this->utils_get_url_parameter('payment_ok') . "&session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => get_site_url() . $link_cancel . "?" . $this->utils_get_url_parameter('payment_return') . "&session_id={CHECKOUT_SESSION_ID}",
        ]);

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
            'payment_session_id' => $checkout_session->id,
            'payment_session_link' => $checkout_session->url,
        ];
    }

    public function success_payment()
    {
        global $wpdb;
        $entry = $this->get_entry();

        $table = $this->utils_get_db_tables('submission');
        $wpdb->update($table, ['payment_status' => 'success'], ['id_submission' => $entry->data['id_submission']]);
    }

    public function failure_payment()
    {
        global $wpdb;
        $entry = $this->get_entry();

        $table = $this->utils_get_db_tables('submission');
        $wpdb->update($table, ['payment_status' => 'canceled'], ['id_submission' => $entry->data['id_submission']]);
    }
}
