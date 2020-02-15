<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewProductsFinishedTable extends Migration {

	public function up()
	{
		Schema::create('new_products_finished', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('shop_id')->unsigned();
			$table->integer('new_id');
			$table->boolean('user_confirmation')->nullable();
			$table->boolean('shop_confirmation')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('new_products_finished');
	}
}