<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('subCategory_id');
			$table->string('name_ar', 50)->nullable();
			$table->string('name_en', 50)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('products');
	}
}