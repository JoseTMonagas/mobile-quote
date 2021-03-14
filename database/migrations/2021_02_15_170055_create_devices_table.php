<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string("model");
            $table->string("brand");
            $table->string("image");
            $table->unsignedFloat("base_price");
            $table->unsignedFloat("excellent_factor");
            $table->unsignedFloat("good_factor");
            $table->unsignedFloat("acceptable_factor");
            $table->unsignedFloat("broken_factor");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
