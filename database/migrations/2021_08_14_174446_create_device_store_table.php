<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_store', function (Blueprint $table) {
            $table->id();
            $table->foreignId("device_id")->constrained();
            $table->foreignId("store_id")->constrained();
            $table->unsignedFloat("custom_price");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_store');
    }
}
