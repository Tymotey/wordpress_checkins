<?php

namespace BTDEV_INSCRIERI\Classes;

use BTDEV_INSCRIERI\Exceptions\Submission as BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION;
use BTDEV_INSCRIERI\Exceptions\Table as BTDEV_INSCRIERI_EXCEPTIONSTABLE;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;
use BTDEV_INSCRIERI\Traits\HtmlMessages as BTDEV_INSCRIERI_MESSAGES;
use BTDEV_INSCRIERI\Classes\Submission as BTDEV_INSCRIERI_SUBMISSION;
use BTDEV_INSCRIERI\Classes\SubmissionHtmlData as BTDEV_INSCRIERI_SUBMISSION_HTML;
use BTDEV_INSCRIERI\Classes\Tables as BTDEV_INSCRIERI_TABLES;

class Shortcodes
{
    use BTDEV_INSCRIERI_UTILS;
    use BTDEV_INSCRIERI_MESSAGES;

    public function __construct()
    {
    }

    public function extract_args($args, $var)
    {
        if (isset($args[$var])) return $args[$var];
        else return false;
    }

    public function extract_submit_data()
    {
        $form_data = false;

        if (isset($_SESSION['btdev_saved_data'])) {
            $form_data = $_SESSION['btdev_saved_data'];
            unset($_SESSION['btdev_saved_data']);
        }

        return $form_data;
    }

    // List
    public function list_entries($args)
    {
        $html = '';

        try {
            $form_name = $this->extract_args($args, 'form');
            if ($form_name !== false && $form_name !== '') {
                $table_type = $this->extract_args($args, 'type');
                if ($table_type !== false && $table_type !== '') {
                    $classname = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($form_name);

                    if (class_exists($classname)) {
                        $form_class = new $classname();
                        $table = new BTDEV_INSCRIERI_TABLES($form_class, $table_type);
                        $html .= $table->generate_table();
                    } else {
                        throw new BTDEV_INSCRIERI_EXCEPTIONSTABLE('Incorrect form name argument sent.');
                    }
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSTABLE('No table type sent.');
                }
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSTABLE('No form "name" argument sent.');
            }
        } catch (BTDEV_INSCRIERI_EXCEPTIONSTABLE $e) {
            $this->messages_add('Tables: ' . __($e->message, 'btdev_inscriere_text'));
        } catch (\Exception $e) {
            $this->messages_add(
                'Tables: ' . sprintf(
                    __('Unexpected exception: %s.', 'btdev_inscriere_text'),
                    $e->getMessage()
                )
            );
        }

        $html = $this->messages_show_html() . $html;

        return $html;
    }

    public function list_payments($args)
    {
    }

    public function submission_summary($args)
    {
        try {
            $html = '';
            $form_name = $this->extract_args($args, 'form');
            if ($form_name !== false && $form_name !== '') {
                $classname = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($form_name);

                if (class_exists($classname)) {
                    $form_class = new $classname();
                    $arg_id = $this->extract_args($args, 'id');
                    $search_id = false;

                    if ($arg_id !== false) {
                        $search_id = $arg_id;
                    } else {
                        if ((isset($_GET[$this->utils_get_url_parameter('payment_ok')]) || isset($_GET[$this->utils_get_url_parameter('payment_not_ok')])) && isset($_GET['session_id'])) {
                            $search_id = $_GET['session_id'];
                        }
                    }

                    if ($search_id !== false) {
                        $submission_data = new BTDEV_INSCRIERI_SUBMISSION($search_id, $form_class);
                        $submission_data->get_entries();
                        $submission_html = new BTDEV_INSCRIERI_SUBMISSION_HTML($submission_data);

                        $html .= $submission_html->create_summary();
                    }

                    return $html;
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION('Incorrect form name argument sent.');
                }
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION('No form "name" argument sent.');
            }
        } catch (BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION $e) {
            return 'Summary: ' . __($e->message, 'btdev_inscriere_text');
        } catch (\Exception $e) {
            return 'Summary: ' . sprintf(
                __('Unexpected exception: %s.', 'btdev_inscriere_text'),
                $e->getMessage()
            );
        }
    }

    // For Form
    public function form($args)
    {
        $html = '';

        try {
            $form_name = $this->extract_args($args, 'form');
            if ($form_name !== false && $form_name !== '') {
                $classname = 'BTDEV_INSCRIERI\\Forms\\Data' . ucfirst($form_name);

                if (class_exists($classname)) {
                    $form_class = new $classname();
                    $form_submit_data = $this->extract_submit_data();

                    $html .= $this->messages_show_html();

                    $action = 'create';
                    $form_action = 'btdevCreate';
                    if ($form_submit_data === false) {
                        $form_submit_data = [];
                    }
                    if (isset($attr['editRow'])) {
                        $action = 'edit';
                        $form_action = 'btdevEditRow';
                    }

                    $custom_price = '';
                    if (isset($attr['price']) && $attr['price'] >= 0) {
                        $custom_price = '<input type="hidden" name="custom_data[price]" value="' . $attr['price'] . '" />';
                    }

                    $custom_referred_from = '';
                    if (isset($_GET['referred_from']) && $_GET['referred_from'] !== '') {
                        $custom_referred_from = '<input type="hidden" name="custom_data[referred_from]" value="' . $_GET['referred_from'] . '" />';
                    }

                    $html .= '<form class="btdev_form" id="form_' . $form_name . '" action="' . get_site_url() . '?' . $form_action . '" method="POST">
                        ' . $custom_price . '
                        ' . $custom_referred_from . '
                        <input type="hidden" name="form_type" value="btdev_inscrieri" />
                        <input type="hidden" name="form_name" value="' . $form_name . '" />
                        <input type="hidden" name="current_url" value="https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" />';

                    $html .= $form_class->html_form_begin_area($form_submit_data, $action);
                    $html .= $form_class->html_form_repeater_area($form_submit_data, $action);
                    $html .= $form_class->html_form_end_area($form_submit_data, $action);

                    $price_logic = $form_class->get_price_logic();
                    $logic = $form_class->get_fields_logic();
                    $html .= '</form>
                        </div>
                        <script type="text/javascript">
                            window[\'btdevForm\'] = {
                                count: ' . (isset($form_data['entries']) ? count($form_data['entries']) : 1) . ',
                                repeaterData: `' . $form_class->get_repeater_html(false, 'create', '%s') . '`,
                                basePrice: ' . $form_class->full_data['payment']['base_price'] . ',
                                priceChanges: `' . json_encode($price_logic) . '`,
                                hideLogic: `' . json_encode($logic) . '`
                            }
                        </script>';
                } else {
                    throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION('Incorrect form name argument sent.');
                }
            } else {
                throw new BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION('No form "name" argument sent.');
            }

            return $html;
        } catch (BTDEV_INSCRIERI_EXCEPTIONSSUBMISSION $e) {
            return 'Form: ' . __($e->message, 'btdev_inscriere_text');
        } catch (\Exception $e) {
            return 'Form: ' . sprintf(
                __('Unexpected exception: %s.', 'btdev_inscriere_text'),
                $e->getMessage()
            );
        }
    }
}
