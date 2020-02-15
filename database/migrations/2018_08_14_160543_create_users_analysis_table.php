<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersAnalysisTable extends Migration {

	public function up()
	{
		Schema::create('users_analysis', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->datetime('last_seen')->nullable();
			$table->integer('replying_speed')->nullable();
			$table->string('complated_orders', 50)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users_analysis');
	}
}