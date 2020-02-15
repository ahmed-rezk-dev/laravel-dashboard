<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasketTable extends Migration {

	public function up()
	{
		Schema::create('basket', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->integer('number')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('basket');
	}
}