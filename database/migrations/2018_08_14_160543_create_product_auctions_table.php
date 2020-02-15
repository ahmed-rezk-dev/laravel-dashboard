<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductAuctionsTable extends Migration {

	public function up()
	{
		Schema::create('product_auctions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('shop_id')->unsigned();
			$table->integer('new_id')->unsigned();
			$table->string('note', 100)->nullable();
			$table->string('payment_method', 50)->nullable();
			$table->datetime('deliveryDay');
			$table->datetime('deliveryTime');
			$table->integer('price');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('product_auctions');
	}
}