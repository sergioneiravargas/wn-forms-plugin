<?php

namespace Butils\Forms\Updates;

use Winter\Storm\Database\Updates\Migration;
use Schema;

class BuilderTableCreateButilsFormsForm extends Migration
{
    public function up()
    {
        Schema::create('butils_forms_form', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('subject');
            $table->text('fields')->nullable();
            $table->boolean('should_mail')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('butils_forms_form');
    }
}
