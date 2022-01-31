<?php

use Butils\Forms\Models\Form;
use Butils\Forms\Models\Message;

Route::get('butils/api/forms/forms', function () {
    return $forms = Form::all();
});

Route::get('butils/api/forms/forms/{id}', function ($id) {
    $form = Form::find($id);
    $response = [
        'id' => $form->id,
        'fields' => [
        ],
    ];

    foreach ($form->fields as $field) {
        $out = (object) [
            'name' => $field['name'],
            'label' => $field['label'],
            'pattern' => $field['regex_pattern'],
        ];

        array_push($response['fields'], $out);
    }

    return $response;
});

Route::get('butils/api/forms/forms/{id}/fields', function ($id) {
    $form = new Form();

    return $form->getFields($id);
});

Route::get('butils/api/forms/message', function () {
    return $message = Message::all();
});

Route::get('butils/api/forms/message/{id}', function ($id) {
    return $message = Message::find($id);
});

Route::post('butils/api/forms/message', 'Butils\Forms\Controllers\Messages@sendMessage');
