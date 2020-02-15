<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('categories', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('name_ar', 50)->nullable();
			$table->string('name_en', 50)->nullable();
			$table->string('logo', 200)->nullable();
			$table->boolean('status')->default(true);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('categories');
	}
}