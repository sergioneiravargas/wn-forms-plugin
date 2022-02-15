<?php

namespace Butils\Forms;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Butils\Forms\Components\Form' => 'form',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerNavigation()
    {
        return [
            'messages' => [
                'label' => 'Forms',
                'url' => Backend::url('butils/forms/messages'),
                'icon' => 'icon-envelope',
                'sideMenu' => [
                    'messages' => [
                        'label' => 'Messages',
                        'icon' => 'icon-envelope',
                        'url' => Backend::url('butils/forms/messages'),
                    ],
                    'forms' => [
                        'label' => 'Forms',
                        'icon' => 'icon-list-ul',
                        'url' => Backend::url('butils/forms/forms'),
                    ],
                ],
            ],
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Butils\Forms\FormWidgets\FormMessageContent' => [
                'label' => 'FormMessageContent field',
                'code' => 'formMessageContent',
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'butils.forms.developer' => [
                'label' => 'Manage forms',
                'tab' => 'Forms',
            ],
            'butils.forms.user' => [
                'label' => 'Check messages',
                'tab' => 'Forms',
            ],
        ];
    }
}
