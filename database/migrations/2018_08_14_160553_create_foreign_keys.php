<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('shops', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('users_settings', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('photos', function(Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('Fields', function(Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('basket', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('basket', function(Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('products_ratings', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('products_ratings', function(Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('maintenance', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('product_auctions', function(Blueprint $table) {
			$table->foreign('shop_id')->references('id')->on('shops')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('product_auctions', function(Blueprint $table) {
			$table->foreign('new_id')->references('id')->on('new_product')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('maintenance__auctions', function(Blueprint $table) {
			$table->foreign('shop_id')->references('id')->on('shops')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('maintenance__auctions', function(Blueprint $table) {
			$table->foreign('maint_id')->references('id')->on('maintenance')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('users_analysis', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('shops_products', function(Blueprint $table) {
			$table->foreign('shop_id')->references('id')->on('shops')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('shops_products', function(Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('shops_products', function(Blueprint $table) {
			$table->foreign('subCategory_id')->references('id')->on('SubCategories')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('new_products_finished', function(Blueprint $table) {
			$table->foreign('shop_id')->references('id')->on('shops')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('maintenances_finished', function(Blueprint $table) {
			$table->foreign('shop_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('shop_categories', function(Blueprint $table) {
			$table->foreign('shop_id')->references('id')->on('shops')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('shop_categories', function(Blueprint $table) {
			$table->foreign('subCategory_id')->references('id')->on('SubCategories')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('shop_maintenance', function(Blueprint $table) {
			$table->foreign('shop_id')->references('id')->on('shops')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('shops_rating', function(Blueprint $table) {
			$table->foreign('shop_id')->references('id')->on('shops')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('maintenance_sub_categories', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('maintenance_categories')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('maintenance_sub_categories', function(Blueprint $table) {
			$table->foreign('father_id')->references('id')->on('maintenance_sub_categories')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('shops', function(Blueprint $table) {
			$table->dropForeign('shops_user_id_foreign');
		});
		Schema::table('users_settings', function(Blueprint $table) {
			$table->dropForeign('users_settings_user_id_foreign');
		});
		Schema::table('photos', function(Blueprint $table) {
			$table->dropForeign('photos_product_id_foreign');
		});
		Schema::table('Fields', function(Blueprint $table) {
			$table->dropForeign('Fields_product_id_foreign');
		});
		Schema::table('basket', function(Blueprint $table) {
			$table->dropForeign('basket_user_id_foreign');
		});
		Schema::table('basket', function(Blueprint $table) {
			$table->dropForeign('basket_product_id_foreign');
		});
		Schema::table('products_ratings', function(Blueprint $table) {
			$table->dropForeign('products_ratings_user_id_foreign');
		});
		Schema::table('products_ratings', function(Blueprint $table) {
			$table->dropForeign('products_ratings_product_id_foreign');
		});
		Schema::table('maintenance', function(Blueprint $table) {
			$table->dropForeign('maintenance_user_id_foreign');
		});
		Schema::table('product_auctions', function(Blueprint $table) {
			$table->dropForeign('product_auctions_shop_id_foreign');
		});
		Schema::table('product_auctions', function(Blueprint $table) {
			$table->dropForeign('product_auctions_new_id_foreign');
		});
		Schema::table('maintenance__auctions', function(Blueprint $table) {
			$table->dropForeign('maintenance__auctions_shop_id_foreign');
		});
		Schema::table('maintenance__auctions', function(Blueprint $table) {
			$table->dropForeign('maintenance__auctions_maint_id_foreign');
		});
		Schema::table('users_analysis', function(Blueprint $table) {
			$table->dropForeign('users_analysis_user_id_foreign');
		});
		Schema::table('shops_products', function(Blueprint $table) {
			$table->dropForeign('shops_products_shop_id_foreign');
		});
		Schema::table('shops_products', function(Blueprint $table) {
			$table->dropForeign('shops_products_product_id_foreign');
		});
		Schema::table('shops_products', function(Blueprint $table) {
			$table->dropForeign('shops_products_subCategory_id_foreign');
		});
		Schema::table('new_products_finished', function(Blueprint $table) {
			$table->dropForeign('new_products_finished_shop_id_foreign');
		});
		Schema::table('maintenances_finished', function(Blueprint $table) {
			$table->dropForeign('maintenances_finished_shop_id_foreign');
		});
		Schema::table('shop_categories', function(Blueprint $table) {
			$table->dropForeign('shop_categories_shop_id_foreign');
		});
		Schema::table('shop_categories', function(Blueprint $table) {
			$table->dropForeign('shop_categories_subCategory_id_foreign');
		});
		Schema::table('shop_maintenance', function(Blueprint $table) {
			$table->dropForeign('shop_maintenance_shop_id_foreign');
		});
		Schema::table('shops_rating', function(Blueprint $table) {
			$table->dropForeign('shops_rating_shop_id_foreign');
		});
		Schema::table('maintenance_sub_categories', function(Blueprint $table) {
			$table->dropForeign('maintenance_sub_categories_category_id_foreign');
		});
		Schema::table('maintenance_sub_categories', function(Blueprint $table) {
			$table->dropForeign('maintenance_sub_categories_father_id_foreign');
		});
	}
}