<?php

namespace BTDEV_INSCRIERI\Forms;

use BTDEV_INSCRIERI\Interfaces\Form as InterfaceForm;
use BTDEV_INSCRIERI\Classes\Field as FieldForm;
use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

abstract class DefaultData implements InterfaceForm
{
    use BTDEV_INSCRIERI_UTILS;

    public $name = '';
    public $default_data = [
        'mode' => 'test', // 'live', 'test'
        'currency' => 'RON',
        'description' => '',
        'min_entries' => 1,
        'submission' => [
            'max_edits' => 1,
            'max_date_edit' => ''
        ],

        'tandc' => [
            'enabled' => false,
            'text' => 'I agree that my personal data will be used for a better organization of the event and for further interactions about this or further events.'
        ],
        'captcha' => [
            'enabled' => false
        ],
        'payment' => [
            'enabled' => true,
            'with' => [],
            'currency' => 'EUR',
            'base_price' => '0',
            'payment_for' => '',
            'description' => '',
            'allow_company' => true,
            'send_invoice' => true,
        ],

        'links' => [
            'url' => '/',
            'success' => '/',
            'cancel' => '/',
            'edit' => '/',
            'view' => '/',
        ],

        'table' => '',

        'checkins' => [
            'enabled' => true,
            'need_presence' => true,
        ],

        'emails' => [
            'description' => '',
            'list' => [
                'saved' => [
                    'enabled' => false,
                    'content' => '',
                    'subject' => '',
                ],
                'done' => [
                    'enabled' => true,
                    'content' => '',
                    'subject' => '',
                ],
                'not_done' => [
                    'enabled' => true,
                    'content' => '',
                ],
                'edit' => [
                    'enabled' => true,
                    'content' => '',
                    'subject' => 'x',
                ]
            ]
        ],

        // Fields
        'submission_fields' => [
            'sub_fullname' => [
                'type' => 'text',
                'title' => 'Name',
                'name' => 'sub_fullname',
                'required' => true,
            ],
            'sub_email' => [
                'type' => 'email',
                'title' => 'Email',
                'name' => 'sub_email',
                'required' => true,
            ],
        ],
        'payment_fields' => [
            'html_payment' => [
                'type' => 'html',
                'name' => 'html_payment',
                'html' => '<h6 class="black_text normal_case">Payment info</h6>',
                'width' => 'full'
            ],
            'payment_name' => [
                'type' => 'payment',
                'title' => 'Choose payment method',
                'name' => 'payment_name',
                'width' => 'full',
                'required' => true,
                'inner_wrapper_class_add' => ['cols_2_radios'],
                'default_value' => 'stripe',
                'field_logic' => [
                    'stripe' => [
                        'action' => 'click',
                        'events' => [
                            'hide' => ['html_payment_op'],
                        ]
                    ],
                    'ordinPlata' => [
                        'action' => 'click',
                        'events' => [
                            'show' => ['html_payment_op'],
                        ]
                    ]
                ]
            ],
            'html_payment_op' => [
                'type' => 'html',
                'name' => 'html_payment_op',
                'html' => '<h6 class="black_text normal_case">Plata cu OP</h6>QWEQWEQWEQWEQWE',
                'width' => 'full',
                'hidden' => true,
            ],
            'payment_invoice' => [
                'type' => 'radio',
                'title' => 'Do you want invoice?',
                'name' => 'payment_invoice',
                'options' => [
                    'no' => ['title' => 'No', 'short_id' => 'no', 'show_in_form' => true, 'disabled' => false],
                    'yes' => ['title' => 'Yes', 'short_id' => 'yes', 'show_in_form' => true, 'disabled' => false],
                ],
                'width' => 'full',
                'required' => true,
                'inner_wrapper_class_add' => ['cols_2_radios'],
                'default_value' => 'no',
                'field_logic' => [
                    'no' => [
                        'action' => 'click',
                        'events' => [
                            'show' => ['company_test'],
                            'hide' => ['company_name', 'company_cui', 'company_j', 'company_address', 'company_delegate'],
                        ]
                    ],
                    'yes' => [
                        'action' => 'click',
                        'events' => [
                            'show' => ['company_name', 'company_cui', 'company_j', 'company_address', 'company_delegate'],
                            'hide' => ['company_test'],
                        ]
                    ]
                ]
            ],
            'company_test' => [
                'type' => 'text',
                'title' => 'Company Test',
                'name' => 'company_test',
                'required' => true,
            ],
            'company_name' => [
                'type' => 'text',
                'title' => 'Company Name',
                'name' => 'company_name',
                'required' => true,
                'hidden' => true,
            ],
            'company_cui' => [
                'type' => 'text',
                'title' => 'CUI',
                'name' => 'company_cui',
                'required' => true,
                'hidden' => true,
            ],
            'company_j' => [
                'type' => 'text',
                'title' => 'J',
                'name' => 'company_j',
                'hidden' => true,
            ],
            'company_address' => [
                'type' => 'text',
                'title' => 'Address',
                'name' => 'company_address',
                'required' => true,
                'hidden' => true,
            ],
            'company_delegate' => [
                'type' => 'text',
                'title' => 'Delegate',
                'name' => 'company_delegate',
                'hidden' => true,
            ],
        ],
        'repeater_fields' => [],

        // Tables
        'tables' => [
            'entries_public' => [
                'settings' => [
                    'lengthMenu' => [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'All']
                    ]
                ],
                'sort_by' => [[0, 'asc']],
                'fields' => [
                    'id_entry' => [
                        'order' => 0,
                        'title' => 'Id',
                        'table' => 'entry',
                    ],
                    'firstname' => [
                        'order' => 20,
                        'title' => 'Firstname',
                        'table' => 'entry'
                    ],
                    'lastname' => [
                        'order' => 30,
                        'title' => 'Lastname',
                        'table' => 'entry'
                    ],
                    'added_entry' => [
                        'order' => 100,
                        'title' => 'Date Added',
                        'format' => 'date',
                        'table' => 'entry',
                        'settings_sql' => [
                            'searchable' => false,
                        ]
                    ],
                    'actions' => [
                        'order' => 110,
                        'title' => 'Actions',
                        'settings_sql' => [
                            'searchable' => false,
                        ],
                        'settings' => [
                            'searchable' => false,
                            'orderable' => false,
                        ]
                    ]
                ]
            ],
            'entries_admin' => [
                'fields' => [
                    'id_entry' => [
                        'order' => 0,
                        'title' => 'Id',
                    ],
                    'id_submission' => [
                        'order' => 10,
                        'title' => 'Id Payment',
                    ],
                    'firstname' => [
                        'order' => 20,
                        'title' => 'Firstname',
                    ],
                    'lastname' => [
                        'order' => 30,
                        'title' => 'Lastname',
                    ],
                    'added_entry' => [
                        'order' => 100,
                        'title' => 'Date Added',
                    ],
                    'actions' => [
                        'order' => 110,
                        'title' => 'Actions',
                        'custom_html' => true,
                        'settings' => [
                            'searchable' => false,
                            'orderable' => false,
                        ]
                    ]
                ]
            ],
            'submissions' => [
                'fields' => [
                    'id_submission' => [
                        'order' => 0,
                        'title' => 'Id',
                    ],
                    'sub_fullname' => [
                        'order' => 20,
                        'title' => 'Name',
                    ],
                    'sub_email' => [
                        'order' => 30,
                        'title' => 'Email',
                    ],
                    'total' => [
                        'order' => 50,
                        'title' => 'Total',
                        'custom_html' => true,
                    ],
                    'payment_name' => [
                        'order' => 60,
                        'title' => 'Payed with',
                    ],
                    'payment_status' => [
                        'order' => 70,
                        'title' => 'Status',
                        'custom_html' => true,
                    ],
                    'payment_invoice' => [
                        'order' => 80,
                        'title' => 'Invoice requested',
                        'custom_html' => true,
                    ],
                    'referred_from' => [
                        'order' => 90,
                        'title' => 'Referred',
                    ],
                    'added_payment' => [
                        'order' => 100,
                        'title' => 'Date Added',
                    ]
                ]
            ],
        ],
    ];

    public $full_data = [];

    public function __construct()
    {
    }

    public function html_form_begin_area($form_data, $action = 'create')
    {
        $html = '';

        if ($action === 'create') {
            $html = '<div class="form-group full_width no_margin">
                <h6 class="black_text normal_case">' . __('Who is making the submission', 'btdev_inscriere_text') . '</h6>
            </div>';

            if (isset($this->full_data['submission_fields'])) {
                foreach ($this->full_data['submission_fields'] as $field_payment) {
                    $field_html = new FieldForm($field_payment, isset($form_data[$field_payment['name']]) ? $form_data[$field_payment['name']] : null, $this);
                    $html .= $field_html->create_html();
                }
            }

            if (isset($this->full_data['payment']) && isset($this->full_data['payment']['enabled']) && $this->full_data['payment']['enabled'] === true) {
                if (isset($this->full_data['payment_fields'])) {
                    foreach ($this->full_data['payment_fields'] as $field_payment) {
                        $field_html = new FieldForm($field_payment, isset($form_data[$field_payment['name']]) ? $form_data[$field_payment['name']] : null, $this);
                        $html .= $field_html->create_html();
                    }
                }
            }

            if (isset($this->full_data['tandc']) && isset($this->full_data['tandc']['enabled']) && $this->full_data['tandc']['enabled'] === true) {
                $text = $this->full_data['tandc']['text'];
                $html .= '<div class="form-group full_width tac_wrapper no_margin">
                    <input type="checkbox" name="agree_tac" id="agree_tac" value="true" checked />
                    <label for="agree_tac" class="tac_text">' . $text . '</label>
                </div>';
            }
            $html .= '<div class="form-group full_width">
                <hr class="color_accent" />
            </div>
            <div class="form-group full_width">
                <button class="form_add_new_data">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13.5C9.15 13.5 9.271 13.4543 9.363 13.363C9.45433 13.271 9.5 13.15 9.5 13V9.5H13.025C13.1583 9.5 13.271 9.45 13.363 9.35C13.4543 9.25 13.5 9.13333 13.5 9C13.5 8.85 13.4543 8.729 13.363 8.637C13.271 8.54567 13.15 8.5 13 8.5H9.5V4.975C9.5 4.84167 9.45 4.729 9.35 4.637C9.25 4.54567 9.13333 4.5 9 4.5C8.85 4.5 8.729 4.54567 8.637 4.637C8.54567 4.729 8.5 4.85 8.5 5V8.5H4.975C4.84167 8.5 4.72933 8.55 4.638 8.65C4.546 8.75 4.5 8.86667 4.5 9C4.5 9.15 4.546 9.271 4.638 9.363C4.72933 9.45433 4.85 9.5 5 9.5H8.5V13.025C8.5 13.1583 8.55 13.271 8.65 13.363C8.75 13.4543 8.86667 13.5 9 13.5ZM9 18C7.75 18 6.575 17.7667 5.475 17.3C4.375 16.8333 3.421 16.196 2.613 15.388C1.80433 14.5793 1.16667 13.625 0.7 12.525C0.233333 11.425 0 10.25 0 9C0 7.75 0.233333 6.575 0.7 5.475C1.16667 4.375 1.80433 3.42067 2.613 2.612C3.421 1.804 4.375 1.16667 5.475 0.7C6.575 0.233333 7.75 0 9 0C10.25 0 11.425 0.233333 12.525 0.7C13.625 1.16667 14.5793 1.804 15.388 2.612C16.196 3.42067 16.8333 4.375 17.3 5.475C17.7667 6.575 18 7.75 18 9C18 10.25 17.7667 11.425 17.3 12.525C16.8333 13.625 16.196 14.5793 15.388 15.388C14.5793 16.196 13.625 16.8333 12.525 17.3C11.425 17.7667 10.25 18 9 18ZM9 17C11.2167 17 13.1043 16.221 14.663 14.663C16.221 13.1043 17 11.2167 17 9C17 6.78333 16.221 4.89567 14.663 3.337C13.1043 1.779 11.2167 1 9 1C6.78333 1 4.896 1.779 3.338 3.337C1.77933 4.89567 1 6.78333 1 9C1 11.2167 1.77933 13.1043 3.338 14.663C4.896 16.221 6.78333 17 9 17Z" fill="#171718"/>
                    </svg>
                    <span>Adaugă un participant nou</span>
                </button>
            </div>';
        }

        return $html;
    }

    public function html_form_repeater_area($form_data, $action = 'create')
    {
        $html = '<div class="repeater_wrapper">
            <div id="repeater_data">';

        if (isset($form_data['entries'])) {
            foreach ($form_data['entries'] as $index => $entry) {
                $html .= $this->get_repeater_html($entry, $action, $index);
            }
        } else {
            $html .= $this->get_repeater_html([], $action, 0);
        }

        $html .= '</div>';
        if ($action === 'create') {
            $html .= '<button class="form_add_new_data">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9 13.5C9.15 13.5 9.271 13.4543 9.363 13.363C9.45433 13.271 9.5 13.15 9.5 13V9.5H13.025C13.1583 9.5 13.271 9.45 13.363 9.35C13.4543 9.25 13.5 9.13333 13.5 9C13.5 8.85 13.4543 8.729 13.363 8.637C13.271 8.54567 13.15 8.5 13 8.5H9.5V4.975C9.5 4.84167 9.45 4.729 9.35 4.637C9.25 4.54567 9.13333 4.5 9 4.5C8.85 4.5 8.729 4.54567 8.637 4.637C8.54567 4.729 8.5 4.85 8.5 5V8.5H4.975C4.84167 8.5 4.72933 8.55 4.638 8.65C4.546 8.75 4.5 8.86667 4.5 9C4.5 9.15 4.546 9.271 4.638 9.363C4.72933 9.45433 4.85 9.5 5 9.5H8.5V13.025C8.5 13.1583 8.55 13.271 8.65 13.363C8.75 13.4543 8.86667 13.5 9 13.5ZM9 18C7.75 18 6.575 17.7667 5.475 17.3C4.375 16.8333 3.421 16.196 2.613 15.388C1.80433 14.5793 1.16667 13.625 0.7 12.525C0.233333 11.425 0 10.25 0 9C0 7.75 0.233333 6.575 0.7 5.475C1.16667 4.375 1.80433 3.42067 2.613 2.612C3.421 1.804 4.375 1.16667 5.475 0.7C6.575 0.233333 7.75 0 9 0C10.25 0 11.425 0.233333 12.525 0.7C13.625 1.16667 14.5793 1.804 15.388 2.612C16.196 3.42067 16.8333 4.375 17.3 5.475C17.7667 6.575 18 7.75 18 9C18 10.25 17.7667 11.425 17.3 12.525C16.8333 13.625 16.196 14.5793 15.388 15.388C14.5793 16.196 13.625 16.8333 12.525 17.3C11.425 17.7667 10.25 18 9 18ZM9 17C11.2167 17 13.1043 16.221 14.663 14.663C16.221 13.1043 17 11.2167 17 9C17 6.78333 16.221 4.89567 14.663 3.337C13.1043 1.779 11.2167 1 9 1C6.78333 1 4.896 1.779 3.338 3.337C1.77933 4.89567 1 6.78333 1 9C1 11.2167 1.77933 13.1043 3.338 14.663C4.896 16.221 6.78333 17 9 17Z" fill="#171718"/>
        </svg>
                <span>Adaugă un participant nou</span>
            </button>';
        }
        $html .= '</div>';

        return $html;
    }

    public function html_form_end_area($form_data, $action = 'create')
    {
        $html = '';

        if (isset($this->full_data['payment']) && isset($this->full_data['payment']['enabled']) && $this->full_data['payment']['enabled'] === true) {
            $count = 1;
            if (isset($form_data['entries'])) {
                $count = count($form_data['entries']);
            }
            $html .= '<div id="grand_total_wrapper" class="form-group submit_wrapper full_width">
                <span class="btdev_inscriere_total_text">
                    ' . __('Total', 'btdev_inscriere_text') . ': 
                    <span class="btdev_inscriere_total_amount">' . (($this->full_data['payment']['base_price'] * $count) / 100) . '</span>
                    <span class="btdev_inscriere_total_currency">' . $this->full_data['payment']['currency'] . '</span>
                </span>
            </div>';
        }
        $html .= '
        <div class="form-group submit_wrapper full_width">
            <button id="form_submit" type="submit">' . ($action === 'create' ? 'Finalizează înscrierile' : 'Salvează datele') . '</button>
        </div>';

        return $html;
    }

    public function get_repeater_html($form_data, $action = 'create', $index = 0)
    {
        $html = '';

        $name = __('Participant', 'btdev_inscriere_text');
        if (isset($form_data['firstname']) || isset($form_data['lastname'])) {
            $new_name = [];
            if ($form_data['firstname'] !== '') $new_name[] = $form_data['firstname'];
            if ($form_data['lastname'] !== '') $new_name[] = $form_data['lastname'];
            $name = implode(' ', $new_name);
        }

        $html .= '<div class="btdev_data_wrapper" attr-index="' . $index . '">
            <div class="form-group full_width form_remove_data_wrapper">
                <h6 class="name_entry">' . $name . '</h6>
                <button class="form_remove_data"><span>' . __('Delete', 'btdev_inscriere_text') . '</span></button>
            </div>';
        foreach ($this->full_data['repeater_fields'] as $field) {
            $field_html = new FieldForm($field, isset($form_data[$field['name']]) ? $form_data[$field['name']] : null, $this, $index);
            $html .= $field_html->create_html();
        }
        $html .= '</div>';
        // } else if ($action === 'edit') {
        //     if (isset($form_data['entries']) && count($form_data['entries']) > 0) {
        //         foreach ($form_data['entries'] as $index => $entry) {
        //             $html .= '<div class="btdev_data_wrapper" attr-index="' . $index . '">
        //                 <div class="form-group full_width form_remove_data_wrapper">
        //                     <h6 class="name_entry">' . __('Participant', 'btdev_inscriere_text') . '</h6>
        //                     <button class="form_remove_data"><span>' . __('Delete', 'btdev_inscriere_text') . '</span></button>
        //                 </div>';
        //             foreach ($this->full_data['repeater_fields'] as $field) {
        //                 $field_html = new FieldForm($field, isset($entry[$index][$field['name']]) ? $entry[$index][$field['name']] : null, $this, $index);
        //                 $html .= $field_html->create_html();
        //             }
        //             $html .= '</div>';
        //         }
        //     }
        // }

        return $html;
    }

    public function get_price_logic()
    {
        $return_data = [];

        foreach ($this->full_data['submission_fields'] as $field) {
            $field_obj = new FieldForm($field, null, $this, false);
            $price = $field_obj->get_price();
            if ($price !== false) {
                $return_data[$field['name']] = $price;
            }
        }

        foreach ($this->full_data['payment_fields'] as $field) {
            $field_obj = new FieldForm($field, null, $this, false);
            $price = $field_obj->get_price();
            if ($price !== false) {
                $return_data[$field['name']] = $price;
            }
        }

        foreach ($this->full_data['repeater_fields'] as $field) {
            $field_obj = new FieldForm($field, null, $this, false);
            $price = $field_obj->get_price();
            if ($price !== false) {
                $return_data[$field['name']] = $price;
            }
        }

        if (!empty($return_data)) return $return_data;
        else return false;
    }

    public function get_fields_logic()
    {
        $return_data = [];

        foreach ($this->full_data['submission_fields'] as $field) {
            $field_obj = new FieldForm($field, null, $this, false);
            $logic = $field_obj->get_logic();
            if ($logic !== false) {
                $return_data[$field['name']] = $logic;
            }
        }

        foreach ($this->full_data['payment_fields'] as $field) {
            $field_obj = new FieldForm($field, null, $this, false);
            $logic = $field_obj->get_logic();
            if ($logic !== false) {
                $return_data[$field['name']] = $logic;
            }
        }

        foreach ($this->full_data['repeater_fields'] as $field) {
            $field_obj = new FieldForm($field, null, $this, false);
            $logic = $field_obj->get_logic();
            if ($logic !== false) {
                $return_data[$field['name']] = $logic;
            }
        }

        if (!empty($return_data)) return $return_data;
        else return false;
    }

    public function get_form_page_url($for)
    {
        if (isset($this->full_data['links']) && isset($this->full_data['links'][$for])) {
            return $this->full_data['links'][$for];
        } else {
            return false;
        }
    }
}
