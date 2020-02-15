<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldsTable extends Migration {

	public function up()
	{
		Schema::create('Fields', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->string('name_ar', 50)->nullable();
			$table->string('name_en', 50)->nullable();
			$table->string('vaule_ar', 50)->nullable();
			$table->string('vaule_en', 50)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('Fields');
	}
}