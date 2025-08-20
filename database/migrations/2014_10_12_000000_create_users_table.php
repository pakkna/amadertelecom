<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->enum('usertype', ['User', 'Driver', 'Admin', 'SuperAdmin'])->default('User');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_photo')->nullable();
            $table->string('address')->nullable();
            $table->string('otp')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->string('registered_by')->default('App');
            $table->enum('is_active', ['Active', 'InActive'])->default('Active');
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
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
};
