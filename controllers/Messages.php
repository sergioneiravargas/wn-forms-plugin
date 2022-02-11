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
        $form_id = Input::get('form_id');

        if (empty($form_id)) {
            return ['status' => 'error', 'message' => 'The form_id field is required.'];
        }

        $received_at = Carbon::now();

        $content = [];
        $validator_rules = [];

        $fields = Form::find($form_id)->fields;

        foreach ($fields as $field) {
            $input_name = $field['name'];

            $content[$input_name] = Input::get($input_name);
            $validator_rules[$input_name] = $field['october_validator'];

            if ($field['required'] && !$content[$input_name]) {
                exit('Error. Please fill all the required inputs.');
            }
        }

        $validator = Validator::make($content, $validator_rules, $validator_messages);

        if ($validator->fails()) {
            $alert = [
                'success' => false,
                'message' => $validator->messages()->all()[0] ?? 'An error ocurred while sending your request.',
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

        return $alert;
    }

    public function getParams()
    {
        return $this->params;
    }
}
