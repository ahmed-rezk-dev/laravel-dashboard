<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewProductTable extends Migration {

	public function up()
	{
		Schema::create('new_product', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('product_id');
			$table->string('payment_method', 50)->nullable();
			$table->string('airConditioningPipes', 5)->nullable();
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
		Schema::drop('new_product');
	}
}