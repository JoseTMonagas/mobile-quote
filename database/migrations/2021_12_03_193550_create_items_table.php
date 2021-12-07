<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->string("supplier");
            $table->string("manufacturer");
            $table->string("model");
            $table->string("colour");
            $table->string("battery");
            $table->string("grade");
            $table->json("issues");
            $table->unsignedFloat("cost");
            $table->string("imei");
            $table->unsignedFloat("selling_price")->nullable();
            $table->dateTime("sold")->nullable();
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
        Schema::dropIfExists('items');
    }
}
