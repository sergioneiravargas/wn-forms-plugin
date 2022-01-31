<?php

namespace Butils\Forms\Models;

use Model;

/**
 * Model.
 */
class Form extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string the database table used by the model
     */
    public $table = 'butils_forms_form';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
    protected $jsonable = ['fields'];

    public $hasMany = [
        'messages' => ['Butils\Forms\Models\Message'],
    ];
}
