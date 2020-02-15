<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhotosTable extends Migration {

	public function up()
	{
		Schema::create('photos', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->string('path', 200);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('photos');
	}
}