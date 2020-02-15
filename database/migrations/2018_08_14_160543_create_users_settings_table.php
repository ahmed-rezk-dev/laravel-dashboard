<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersSettingsTable extends Migration {

	public function up()
	{
		Schema::create('users_settings', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('lang', 10)->nullable();
			$table->string('notifications', 200)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users_settings');
	}
}