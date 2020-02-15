<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersTable extends Migration {

	public function up()
	{
		Schema::create('offers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('image_ar', 200)->nullable();
			$table->string('image_en', 200)->nullable();
			$table->string('link', 100)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('offers');
	}
}