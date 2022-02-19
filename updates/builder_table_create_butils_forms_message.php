<?php

namespace Butils\Forms\Updates;

use Winter\Storm\Database\Updates\Migration;
use Schema;

class BuilderTableCreateButilsFormsMessage extends Migration
{
    public function up()
    {
        Schema::create('butils_forms_message', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('form_id');
            $table->timestamp('received_at');
            $table->json('content');
        });
    }

    public function down()
    {
        Schema::dropIfExists('butils_forms_message');
    }
}
