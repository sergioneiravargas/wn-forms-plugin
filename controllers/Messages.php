<?php

namespace Sntools\Forms\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use Winter\Storm\Support\Facades\Input;
use Winter\Storm\Support\Facades\Validator;
use Sntools\Forms\Models\Form;
use Sntools\Forms\Models\Message;
use Carbon\Carbon;

class Messages extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = ['sntools.forms.developer', 'sntools.forms.user'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Sntools.Forms', 'messages', 'messages');
    }

    public function sendMessage()
    {
        // get input
        $formId = Input::get('form_id');

        if (empty($formId)) {
            return [
                'success' => false,
                'messages' => [
                    'form_id' => 'The "form_id" field is required.',
                ]
            ];
        }

        $receivedAt = Carbon::now();

        $content = [];
        $validatorRules = [];

        $fields = Form::find($formId)->fields;

        foreach ($fields as $field) {
            $inputName = $field['name'];

            $content[$inputName] = Input::get($inputName);
            $validatorRules[$inputName] = $field['winter_validator'];
        }

        $validator = Validator::make($content, $validatorRules);

        if ($validator->fails()) {
            $alert = [
                'success' => false,
                'messages' => $validator->errors(),
            ];
        } else {
            // create object, save and send email
            $message = new Message();

            $message->form_id = $formId;
            $message->received_at = $receivedAt;
            $message->content = $content;

            $message->save();
            $message->mail();

            $alert = ['success' => true];
        }

        return $alert;
    }

    public function getParams()
    {
        return $this->params;
    }
}
