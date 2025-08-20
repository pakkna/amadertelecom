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
        Schema::create('driver_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('driver_name');
            $table->string('primary_contact');
            $table->string('license_number')->nullable();
            $table->string('license_photo')->nullable();
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('sex')->nullable();
            $table->string('nid_number')->nullable();
            $table->string('nid_photo')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('is_sign_in', ['Yes', 'No', 'None'])->default('None');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            // Create an index on the columns.
            $table->index(['id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_info');
    }
};
