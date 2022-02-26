<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Sntools\Forms\Models\Form;
use Sntools\Forms\Models\Message;
use Winter\Storm\Auth\Manager;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Key, Authorization');
header('Access-Control-Allow-Credentials: true');

Route::get('sntools/forms/api/forms', function () {
    return Form::paginate(20);
});

Route::get('sntools/forms/api/forms/{id}', function ($id) {
    return Form::find($id);
});

Route::get('sntools/forms/api/forms/{id}/messages', function ($id) {
    if (!Manager::instance()->getUser()) {
        App::abort(403);
    }

    return Message::where('form_id', '=', $id)
        ->paginate(20);
});

Route::get('sntools/forms/api/messages', function () {
    if (!Manager::instance()->getUser()) {
        App::abort(403);
    }

    return Message::paginate(20);
});

Route::get('sntools/forms/api/messages/{id}', function ($id) {
    if (!Manager::instance()->getUser()) {
        App::abort(403);
    }

    return Message::find($id);
});

Route::options('sntools/forms/api/messages', function () {
});
Route::post('sntools/forms/api/messages', 'Sntools\Forms\Controllers\Messages@sendMessage');
