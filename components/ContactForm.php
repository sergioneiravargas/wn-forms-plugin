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
    public const HTML_FORM_ID_PREFIX = 'butils-forms-';

    public $options;
    public $form;

    public function componentDetails()
    {
        return [
            'name' => 'Contact',
            'description' => 'Contact form.',
        ];
    }

    public function onRender()
    {
        $this->form = Form::find($this->property('form_id'));
    }

    public function onSend()
    {
        $this->form = Form::find(Input::get('form_id'));

        $receivedAt = Carbon::now();

        $content = [];
        $validatorRules = [];

        foreach ($this->form->fields as $field) {
            $inputName = $field['name'];
            $content[$inputName] = Input::get($inputName);
            $validatorRules[$inputName] = $field['october_validator'];
        }

        // validate input values
        $validator = Validator::make($content, $validatorRules);

        if ($isValid = $validator->passes()) {
            // create object, save and send email
            $message = new Message();

            $message->form_id = $this->form->id;
            $message->received_at = $receivedAt;
            $message->content = $content;

            $message->save();

            if ($this->form->should_mail) {
                $message->mail();
            }
        }

        $alert = [
            'success' => $isValid,
            'message' => $isValid
                ? 'Your request has been sent successfully.'
                : (count($validator->messages()->all())
                    ? 'Please, check the invalid fields.'
                    : 'An error ocurred while sending your request.'),
        ];

        // render partial view for alerts display
        $htmlFormId = self::HTML_FORM_ID_PREFIX.$this->form->id;
        $targetHtmlElement = "#$htmlFormId-alert";

        $viewData[$targetHtmlElement] = $this->renderPartial('contactForm::default-alert.htm', [
            'html_form_id' => $htmlFormId,
            'alert' => $alert,
        ]);

        // render partial view with field validator
        foreach ($this->form->fields as $field) {
            $inputName = $field['name'];
            $targetHtmlElement = "#$htmlFormId .error-message.$inputName";

            $viewData[$targetHtmlElement] = $this->renderPartial(
                'contactForm::default-field-validator.htm',
                [
                    'html_element_selector' => $targetHtmlElement,
                    'error_messages' => $validator->errors()->get($inputName),
                ]
            );
        }

        return $viewData;
    }
}
