<?php

use Butils\Forms\Models\Form;
use Butils\Forms\Models\Message;

Route::get('butils/forms/api/forms', function () {
    return Form::paginate(20);
});

Route::get('butils/forms/api/forms/{id}', function ($id) {
    return Form::find($id);
});

Route::get('butils/forms/api/forms/{id}/messages', function ($id) {
    return Message::where('form_id', '=', $id)
        ->paginate(20)
    ;
});

Route::get('butils/forms/api/messages', function () {
    return Message::paginate(20);
});

Route::get('butils/forms/api/messages/{id}', function ($id) {
    return Message::find($id);
});

Route::post('butils/forms/api/messages', 'Butils\Forms\Controllers\Messages@sendMessage');
