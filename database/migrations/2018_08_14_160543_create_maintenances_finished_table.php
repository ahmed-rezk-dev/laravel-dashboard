<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMaintenancesFinishedTable extends Migration {

	public function up()
	{
		Schema::create('maintenances_finished', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('shop_id')->unsigned();
			$table->integer('maint_id');
			$table->boolean('user_confirmation')->nullable();
			$table->boolean('shop_confirmation')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('maintenances_finished');
	}
}