<?php

namespace Butils\Forms\Models;

use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;
use Winter\Storm\Support\Facades\Mail;
use Winter\Storm\Support\Facades\Config;

/**
 * Model.
 */
class Message extends Model
{
    use Validation;

    protected $jsonable = [
        'content',
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string the database table used by the model
     */
    public $table = 'butils_forms_message';

    /**
     * @var array Validation rules
     */
    public $rules = [];

    public $belongsTo = [
        'form' => ['Butils\Forms\Models\Form'],
    ];

    /**
     * @param string $mailView
     */
    public function mail($mailView = 'butils.forms::mail.default')
    {
        $subject = Form::find($this->form_id)->subject;
        $data = [];

        foreach ($this->content as $key => $value) {
            $data[$key] = $value;
        }

        foreach (Config::get('mail.butilsMailingList') ?? [] as $to) {
            Mail::send($mailView, $data, function ($message) use ($subject, $to, $data) {
                $message->subject($subject);
                $message->to($to['address'], $to['name']);
                $message->data = $data;
            });
        }
    }
}
