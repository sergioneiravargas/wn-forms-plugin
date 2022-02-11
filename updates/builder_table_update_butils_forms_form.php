<?php namespace Butils\Forms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateButilsFormsForm extends Migration
{
    public function up()
    {
        Schema::table('butils_forms_form', function($table)
        {
            $table->boolean('should_mail')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('butils_forms_form', function($table)
        {
            $table->dropColumn('should_mail');
        });
    }
}
