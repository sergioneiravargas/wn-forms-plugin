<?php

use Illuminate\Support\Facades\Route;
use Sntools\Forms\Models\Form;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Key, Authorization');
header('Access-Control-Allow-Credentials: true');

Route::get('api/forms', function () {
    return Form::paginate(20);
});

Route::get('api/forms/{id}', function ($id) {
    return Form::find($id);
});

Route::options('api/messages', function () {
});
Route::post('api/messages', 'Sntools\Forms\Controllers\Messages@sendMessage');
