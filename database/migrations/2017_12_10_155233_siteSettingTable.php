<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SiteSetting;

class SiteSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sitesetting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_name');
            $table->string('site_logo');
            $table->longText('site_description');
            $table->longText('site_tagged');
            $table->longText('site_copyrigth');
            $table->timestamps();
        });

        $setting = new SiteSetting;
        $setting->site_name = 'YOUR SITE NAME';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sitesetting');
    }
}
