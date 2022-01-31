<?php

namespace Butils\Forms\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Butils\Forms\Models\Message;

class FormMessageContent extends FormWidgetBase
{
    public function widgetDetails()
    {
        return [
            'name' => 'FormMessageContent',
            'description' => 'Displays the content of a message.',
        ];
    }

    public function render()
    {
        $id = $this->controller->getParams()[0];

        $message = new Message();
        $currentMessage = $message->find($id);

        $this->vars['form'] = $currentMessage->form;
        $this->vars['content'] = $currentMessage->content;

        return $this->makePartial('widget');
    }

    public function loadAssets()
    {
        $this->addCss('css/default.css');
        $this->addJs('js/default.js');
    }
}
