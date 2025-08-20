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
        Schema::create('route_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('route_name');
            $table->string('route_code')->nullable();
            $table->longText('route_details')->nullable();
            $table->string('start_time_slot');
            $table->string('departure_time_slot');
            $table->string('route_map_url');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            // Create an index on the columns.
            $table->index('id', 'route_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_list');
    }
};
