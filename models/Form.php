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
        'fields.*.name' => 'required',
        'fields.*.tag' => 'required',
    ];

    protected $jsonable = ['fields'];

    public $hasMany = [
        'messages' => ['Butils\Forms\Models\Message'],
    ];

    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if ($fieldName == 'tag') {
            return [
                '' => 'Select an option',
                'input' => 'Input',
                'textarea' => 'Textarea',
            ];
        }

        if ($fieldName == 'type') {
            return  $this->tag === 'input'
                ? [
                    'text' => 'Text',
                    'number' => 'Number',
                    'email' => 'Email',
                    'tel' => 'Telephone',
                    'date' => 'Date',
                    'datetime-local' => 'Datetime',
                    'month' => 'Month',
                    'time' => 'Time',
                ]
                : [
                    '' => ''
                ];
        }
    }
}
