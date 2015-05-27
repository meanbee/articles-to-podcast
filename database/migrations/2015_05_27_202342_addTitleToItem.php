<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleToItem extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('items', function($table)
        {
            $table->string('title');
            $table->string('excerpt');
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('items', function($table)
        {
            $table->dropColumn('title');
            $table->dropColumn('excerpt');
        });
    }

}
