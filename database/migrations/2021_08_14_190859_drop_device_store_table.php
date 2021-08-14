<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDeviceStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('device_store_table');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('device_store', function (Blueprint $table) {
            $table->id();
            $table->foreignId("device_id")->constrained();
            $table->foreignId("store_id")->constrained();
            $table->unsignedFloat("custom_price");
        });
    }
}
