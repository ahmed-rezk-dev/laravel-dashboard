<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('shop_categories', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('shop_id')->unsigned();
			$table->integer('subCategory_id')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('shop_categories');
	}
}