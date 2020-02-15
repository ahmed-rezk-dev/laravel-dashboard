<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMaintenanceTable extends Migration {

	public function up()
	{
		Schema::create('maintenance', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('subCategory_id');
			$table->string('type', 50)->nullable();
			$table->string('image', 200)->nullable();
			$table->string('address', 100)->nullable();
			$table->string('lat', 50)->nullable();
			$table->string('lng', 50)->nullable();
			$table->date('deliveryDay');
			$table->time('deliveryTime');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('maintenance');
	}
}