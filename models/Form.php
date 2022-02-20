<?php

namespace Sntools\Forms\Models;

use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

/**
 * Model.
 */
class Form extends Model
{
    use Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string the database table used by the model
     */
    public $table = 'sntools_forms_form';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'subject' => 'required',
        'fields.*.name' => 'required',
        'fields.*.tag' => 'required',
        'fields.*.options.*.label' => 'required',
        'fields.*.options.*.value' => 'required',
        'fields.*.type' => 'required_if:fields.*.tag,input',
    ];

    protected $jsonable = ['fields'];

    public $hasMany = [
        'messages' => ['Sntools\Forms\Models\Message', 'delete' => true],
    ];

    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if ($fieldName == 'tag') {
            return [
                '' => 'Select an option',
                'input' => 'Input',
                'textarea' => 'Textarea',
                'select' => 'Select',
            ];
        }

        if ($fieldName == 'type') {
            return  isset($formData->tag) && $formData->tag === 'input'
                ? [
                    'text' => 'Text',
                    'number' => 'Number',
                    'email' => 'Email',
                    'tel' => 'Telephone',
                    'checkbox' => 'Checkbox',
                    'date' => 'Date',
                    'datetime-local' => 'Datetime',
                    'month' => 'Month',
                    'time' => 'Time',
                ]
                : [];
        }
    }

    public function beforeSave()
    {
        $fields = $this->fields;
        foreach ($fields as &$field) {
            if ($field['tag'] !== 'input') {
                $field['type'] = '';
            }
            if ($field['tag'] !== 'select') {
                $field['options'] = [];
            }
        }

        $this->fields = $fields;
    }
}
