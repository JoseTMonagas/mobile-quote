<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueQuoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_quote', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained();
            $table->foreignId('quote_id')->constrained();
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
        Schema::dropIfExists('issue_quote_table');
    }
}
