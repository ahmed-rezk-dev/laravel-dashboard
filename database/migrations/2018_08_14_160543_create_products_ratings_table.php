<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsRatingsTable extends Migration {

	public function up()
	{
		Schema::create('products_ratings', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('product_id')->unsigned()->nullable();
			$table->integer('quality');
			$table->string('comment')->nullable();
			$table->integer('performance');
			$table->integer('recommendation');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('products_ratings');
	}
}