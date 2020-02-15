<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopsRatingTable extends Migration {

	public function up()
	{
		Schema::create('shops_rating', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('shop_id')->unsigned();
			$table->integer('dealingPerformance')->unsigned()->default('0');
			$table->integer('deliveryQuality')->default('0');
			$table->integer('workExperiencek');
			$table->integer('dealingAgain')->default('0');
			$table->string('comment', 200)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('shops_rating');
	}
}