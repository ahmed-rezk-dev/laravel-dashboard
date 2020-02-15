<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopsProductsTable extends Migration {

	public function up()
	{
		Schema::create('shops_products', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('shop_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->integer('subCategory_id')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('shops_products');
	}
}