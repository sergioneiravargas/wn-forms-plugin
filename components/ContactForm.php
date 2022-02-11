<?php

namespace Butils\Forms\Components;

use Butils\Forms\Models\Form;
use Butils\Forms\Models\Message;
use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Input;
use Validator;

class ContactForm extends ComponentBase
{
    public $options;
    public $form;
    public $fields;
    public $alert;

    public function componentDetails()
    {
        return [
            'name' => 'Contact',
            'description' => 'Contact form.',
        ];
    }

    public function onRender()
    {
        $this->alert = (object) [
            'type' => 'success',
            'message' => 'Success message.',
        ];

        $this->form = Form::find($this->property('formId'));
    }

    public function onSend()
    {
        // get input
        $form_id = Input::get('form_id');
        $received_at = Carbon::now();

        $content = [];
        $validator_rules = [];

        $fields = Form::find(Input::get('form_id'))->fields;

        foreach ($fields as $field) {
            $input_name = $field['name'];
            $content[$input_name] = Input::get($input_name);
            $validator_rules[$input_name] = $field['october_validator'];
        }

        // validate input values
        $validator_messages = [
        ];

        $validator = Validator::make($content, $validator_rules, $validator_messages);

        if ($validator->fails()) {
            $alert = [
                'success' => false,
                'message' => count($validator->messages()->all())
                    ? 'Please, check the invalid fields.'
                    : 'An error ocurred while sending your request.',
            ];
        } else {
            // create object, save and send email
            $message = new Message();

            $message->form_id = $form_id;
            $message->received_at = $received_at;
            $message->content = $content;

            $message->save();
            $message->sendMail();

            $alert = [
                'success' => true,
                'message' => 'Your request has been sent successfully.',
            ];
        }

        // render partial view for alerts display
        $form_id_attr = 'butils-forms-'.Form::find($form_id)->id;

        $out['#'.$form_id_attr.'-alert'] = $this->renderPartial('contactForm::default-alert.htm', [
                'alert' => $alert,
                'form_id' => $form_id_attr,
            ]);

        // render partial view with field validator
        foreach ($fields as $field) {
            $input_name = $field['name'];

            $out['#'.$form_id_attr.' div.'.$input_name] = $this->renderPartial(
                'contactForm::default-field-validator.htm',
                [
                    'container_selector' => '#'.$form_id_attr.' div.'.$input_name,
                    'error_messages' => $validator->errors()->get($input_name),
                ]
            );
        }

        return $out;
    }
}
