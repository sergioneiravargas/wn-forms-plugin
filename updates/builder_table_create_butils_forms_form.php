<?php

namespace Butils\Forms\Updates;

use October\Rain\Database\Updates\Migration;
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
        });
    }

    public function down()
    {
        Schema::dropIfExists('butils_forms_form');
    }
}
