<?php

namespace Sntools\Forms\Updates;

use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

class BuilderTableCreateSntoolsFormsMessage extends Migration
{
    public function up()
    {
        Schema::create('sntools_forms_message', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('form_id');
            $table->timestamp('received_at');
            $table->json('content');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sntools_forms_message');
    }
}
