<?php

namespace Sntools\Forms;

use System\Classes\PluginBase;
use Backend\Facades\Backend;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Sntools\Forms\Components\Form' => 'form',
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
                'url' => Backend::url('sntools/forms/messages'),
                'icon' => 'icon-bars',
                'sideMenu' => [
                    'messages' => [
                        'label' => 'Messages',
                        'icon' => 'icon-envelope',
                        'url' => Backend::url('sntools/forms/messages'),
                    ],
                    'forms' => [
                        'label' => 'Forms',
                        'icon' => 'icon-bars',
                        'url' => Backend::url('sntools/forms/forms'),
                    ],
                ],
            ],
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Sntools\Forms\FormWidgets\FormMessageContent' => [
                'label' => 'FormMessageContent field',
                'code' => 'formMessageContent',
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'sntools.forms.developer' => [
                'label' => 'Manage forms',
                'tab' => 'Forms',
            ],
            'sntools.forms.user' => [
                'label' => 'Check messages',
                'tab' => 'Forms',
            ],
        ];
    }
}
