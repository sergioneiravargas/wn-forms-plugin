<?php

namespace Sntools\Forms\Updates;

use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

class BuilderTableCreateSntoolsFormsForm extends Migration
{
    public function up()
    {
        Schema::create('sntools_forms_form', function ($table) {
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
        Schema::dropIfExists('sntools_forms_form');
    }
}
