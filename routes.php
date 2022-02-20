<?php

use Illuminate\Support\Facades\Route;
use Sntools\Forms\Models\Form;
use Sntools\Forms\Models\Message;

Route::get('sntools/forms/api/forms', function () {
    return Form::paginate(20);
});

Route::get('sntools/forms/api/forms/{id}', function ($id) {
    return Form::find($id);
});

Route::get('sntools/forms/api/forms/{id}/messages', function ($id) {
    return Message::where('form_id', '=', $id)
        ->paginate(20);
});

Route::get('sntools/forms/api/messages', function () {
    return Message::paginate(20);
});

Route::get('sntools/forms/api/messages/{id}', function ($id) {
    return Message::find($id);
});

Route::post('sntools/forms/api/messages', 'Sntools\Forms\Controllers\Messages@sendMessage');
