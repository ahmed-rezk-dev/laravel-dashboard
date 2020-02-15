<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('city');
			$table->string('address');
			$table->biginteger('lat');
			$table->biginteger('lng');
            $table->string('avatar')->default('default.png');
            $table->string('arrears')->default('0');
            $table->integer('active')->default(0);
            $table->integer('role')->default('0');
            $table->string('device_type',50)->nullable();
            $table->string('device_id',500)->nullable();
            $table->rememberToken();
            $table->timestamps();

        });

         // Insert some stuff
        $user = new User;
        $user->name ='Ahmed Mesbah';
        $user->email ='admin@test.com';
        $user->password =bcrypt(123456);
        $user->phone ='123456789';
        $user->city ='Mansoura';
        $user->address ='egypt,Mansoura';
        $user->lat ='31.040948';
        $user->lng ='31.378470';
        $user->avatar ='default.png';
        $user->arrears ='100';
        $user->active ='1';
        $user->role ='1';
        $user->device_id ='1111111111';
        $user->save();


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
