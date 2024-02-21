<?php

namespace BTDEV_INSCRIERI\Classes;

use BTDEV_INSCRIERI\Traits\Utils as BTDEV_INSCRIERI_UTILS;

use BTDEV_INSCRIERI\Classes\Field as FieldForm;
use BTDEV_INSCRIERI\Classes\Submission as BTDEV_INSCRIERI;

class SubmissionHtmlData extends BTDEV_INSCRIERI
{
    use BTDEV_INSCRIERI_UTILS;

    public function __construct(BTDEV_INSCRIERI $submission)
    {
        $this->data = $submission->data;
        $this->form = $submission->form;
        $this->entries = $submission->entries;
    }

    public function create_summary()
    {
        $html = '';
        if (count($this->entries) > 0) {
            $html .= '<div class="summary_wrapper">
            <div class="summary_title">' . __('Participants', 'btdev_inscriere_text') . '</div><br />';
            foreach ($this->entries as $entry) {
                $html .= '<div class="summary_item_wrapper">
                    <div class="name"><strong>' . $entry['firstname'] . ' ' . $entry['lastname'] . '</strong>(id: ' . $entry['id_entry'] . ')</div>
                    <div class="data">';
                $fields = ['atelier_a', 'atelier_ar'];
                foreach ($fields as $field) {
                    if (isset($this->form->full_data['repeater_fields']) && $this->form->full_data['repeater_fields'][$field]) {
                        $field_obj = new FieldForm($this->form->full_data['repeater_fields'][$field], $entry[$field], $this->form, false);
                        // $this->var_dump($field_obj);
                        $html .= $field_obj->get_html_label();
                    }
                }
                $html .= '</div>
                </div>';
            }
            $html .= '<br /><strong>' . __('Payment id:', 'btdev_inscriere_text') . '</strong> ' . $this->data['payment_session_id'] . '</div>';
        } else {
            $html .= __('Summary cannot find any entries.', 'btdev_inscriere_text');
        }

        return $html;
    }
}
