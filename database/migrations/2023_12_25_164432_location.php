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
        Schema::create('location', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lat');
            $table->string('long');
            $table->unsignedBigInteger('bus_id');
            $table->unsignedBigInteger('route_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('bus_id')->references('id')->on('bus_list')->onDelete('cascade');
            $table->foreign('route_id')->references('id')->on('route_list')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            // Create an index on the columns.
            $table->index(['id', 'bus_id', 'route_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location');
    }
};
