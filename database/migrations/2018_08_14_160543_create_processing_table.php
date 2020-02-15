<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProcessingTable extends Migration {

	public function up()
	{
		Schema::create('processing', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('shop_id');
			$table->integer('new_id')->nullable();
			$table->string('maint_id')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('processing');
	}
}