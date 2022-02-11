<?php

namespace Butils\Forms\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Butils\Forms\Models\Form;
use Butils\Forms\Models\Message;
use Carbon\Carbon;
use Input;
use Validator;

class Messages extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = ['butils.forms.developer', 'butils.forms.user'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Butils.Forms', 'messages', 'messages');
    }

    public function sendMessage()
    {
        // get input
        $formId = Input::get('form_id');

        if (empty($formId)) {
            return ['status' => 'error', 'message' => 'The formId field is required.'];
        }

        $receivedAt = Carbon::now();

        $content = [];
        $validatorRules = [];

        $fields = Form::find($formId)->fields;

        foreach ($fields as $field) {
            $inputName = $field['name'];

            $content[$inputName] = Input::get($inputName);
            $validatorRules[$inputName] = $field['october_validator'];

            if ($field['required'] && !$content[$inputName]) {
                exit('Error. Please fill all the required inputs.');
            }
        }

        $validator = Validator::make($content, $validatorRules, $validator_messages);

        if ($validator->fails()) {
            $alert = [
                'success' => false,
                'message' => $validator->messages()->all()[0] ?? 'An error ocurred while sending your request.',
            ];
        } else {
            // create object, save and send email
            $message = new Message();

            $message->form_id = $formId;
            $message->received_at = $receivedAt;
            $message->content = $content;

            $message->save();
            $message->mail();

            $alert = [
                'success' => true,
                'message' => 'Your request has been sent successfully.',
            ];
        }

        return $alert;
    }

    public function getParams()
    {
        return $this->params;
    }
}
