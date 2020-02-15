<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopMaintenanceTable extends Migration {

	public function up()
	{
		Schema::create('shop_maintenance', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('shop_id')->unsigned();
			$table->json('ids_group');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('shop_maintenance');
	}
}