<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMaintenanceAuctionsTable extends Migration {

	public function up()
	{
		Schema::create('maintenance__auctions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('shop_id')->unsigned();
			$table->integer('maint_id')->unsigned();
			$table->string('note', 100)->nullable();
			$table->datetime('deliveryDay');
			$table->time('deliveryTime');
			$table->integer('price');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('maintenance__auctions');
	}
}