<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SmsEmailNotification;

class SmsAndEmailAndNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smsemailnotification', function (Blueprint $table) {
            $table->increments('id');
            $table->string('smtp_type');
            $table->string('smtp_username');
            $table->string('smtp_password');
            $table->string('smtp_sender_email');
            $table->string('smtp_sender_name');
            $table->integer('smtp_port');
            $table->string('smtp_host');
            $table->string('smtp_encryption');
            $table->string('sms_number');
            $table->string('sms_password');
            $table->string('sms_sender_name');
            $table->string('oneSignal_application_id');
            $table->string('oneSignal_authorization');
            $table->string('fcm_server_key');
            $table->string('fcm_sender_id');
            $table->timestamps();
        });

        $sms = new SmsEmailNotification;
        $sms->smtp_type = 'smtp';
        $sms->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smsemailnotification');
    }
}
