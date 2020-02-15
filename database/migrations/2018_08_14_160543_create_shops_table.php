<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopsTable extends Migration {

	public function up()
	{
		Schema::create('shops', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('name')->nullable();
			$table->string('desc')->nullable();
			$table->string('commercial_register')->nullable();
			$table->string('commercial_register_img', 200)->nullable();
			$table->string('logo', 200)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('shops');
	}
}