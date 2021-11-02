<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropConstrainedForeignId("device_id");
            $table->dropColumn("value");
            $table->dropColumn("serial_ref");
            $table->dropColumn("internal_ref");

            $table->string("name")->nullable();
            $table->string("internal_number")->nullable();
            $table->unsignedSmallInteger("store_margin")->nullable();
            $table->json("items");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn("name");
            $table->dropColumn("internal_number");
            $table->dropColumn("store_margin");
            $table->dropColumn("items");

            $table->string("serial_ref")->nullable();
            $table->string("internal_ref")->nullable();
            $table->unsignedDouble("value");
            $table->foreignId("device_id")->constrained();
        });
    }
}
