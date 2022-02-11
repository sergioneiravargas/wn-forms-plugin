<?php

use Butils\Forms\Models\Form;
use Butils\Forms\Models\Message;
use Input;

Route::get('butils/forms/api/forms', function () {
    $limit = Input::get('per_page') ?? 20;

    return Form::paginate($limit);
});

Route::get('butils/forms/api/forms/{id}', function ($id) {
    return Form::find($id);
});

Route::get('butils/forms/api/forms/{id}/messages', function ($id) {
    $limit = Input::get('per_page') ?? 20;

    return Message::where('form_id', '=', $id)
        ->paginate($limit)
    ;
});

Route::get('butils/forms/api/messages', function () {
    $limit = Input::get('per_page') ?? 20;

    return Message::paginate($limit);
});

Route::get('butils/forms/api/messages/{id}', function ($id) {
    return Message::find($id);
});

Route::post('butils/forms/api/messages', 'Butils\Forms\Controllers\Messages@sendMessage');
