<?php

namespace Sntools\Forms\Components;

use Cms\Classes\ComponentBase;
use Winter\Storm\Support\Facades\Input;
use Winter\Storm\Support\Facades\Validator;
use Sntools\Forms\Models\Form as FormModel;
use Sntools\Forms\Models\Message as MessageModel;
use Carbon\Carbon;

class Form extends ComponentBase
{
    public const HTML_FORM_ID_PREFIX = 'sntools-forms-';

    public $options;
    public $form;

    public function componentDetails()
    {
        return [
            'name' => 'Form',
            'description' => 'Displays a form.',
        ];
    }

    public function defineProperties()
    {
        return [
            'formId' => [
                'title' => 'Form',
                'description' => 'The form to display',
                'type' => 'dropdown'
            ]
        ];
    }

    public function getFormIdOptions()
    {
        $options = [];
        foreach (FormModel::all() as $form) {
            $options[$form->id] = $form->name;
        }

        return $options;
    }

    public function onRender()
    {
        $this->form = FormModel::find($this->property('formId'));
    }

    public function onSend()
    {
        $this->form = FormModel::find(Input::get('form_id'));

        $receivedAt = Carbon::now();

        $content = [];
        $validatorRules = [];

        foreach ($this->form->fields as $field) {
            $inputName = $field['name'];
            $content[$inputName] = Input::get($inputName);
            $validatorRules[$inputName] = $field['winter_validator'];
        }

        // validate input values
        $validator = Validator::make($content, $validatorRules);

        if ($isValid = $validator->passes()) {
            // create object, save and send email
            $message = new MessageModel();

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
        $htmlFormId = self::HTML_FORM_ID_PREFIX . $this->form->id;
        $targetHtmlElement = "#$htmlFormId-alert";

        $viewData[$targetHtmlElement] = $this->renderPartial('form::default-alert.htm', [
            'html_form_id' => $htmlFormId,
            'alert' => $alert,
        ]);

        // render partial view with field validator
        foreach ($this->form->fields as $field) {
            $inputName = $field['name'];
            $targetHtmlElement = "#$htmlFormId .error-message.$inputName";

            $viewData[$targetHtmlElement] = $this->renderPartial(
                'form::default-field-validator.htm',
                [
                    'html_element_selector' => $targetHtmlElement,
                    'error_messages' => $validator->errors()->get($inputName),
                ]
            );
        }

        return $viewData;
    }
}
