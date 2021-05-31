<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterCiclosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ciclos', function(Blueprint $table)
		{
            $table->integer('responsable')->unsigned()->nullable()->change();
			$table->foreign('responsable', 'responsable_ciclos_foreign')->references('id')->on('responsables')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('ciclos', function(Blueprint $table)
        {
            $table->dropForeign('responsable_ciclos_foreign');
        });
	}

}
