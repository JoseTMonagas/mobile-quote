<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_issue', function (Blueprint $table) {
            $table->id();
            $table->foreignId("device_id")->constrained();
            $table->foreignId("issue_id")->constrained();
            $table->unsignedFloat("deduction");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_issue');
    }
}
