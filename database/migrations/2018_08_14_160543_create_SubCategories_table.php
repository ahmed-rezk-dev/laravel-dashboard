<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('SubCategories', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('category_id')->unsigned();
			$table->integer('father_id')->nullable();
			$table->string('name_ar', 50)->nullable();
			$table->string('name_en', 50)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('SubCategories');
	}
}