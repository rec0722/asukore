<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->nullable();
            $table->time('time1')->nullable();
            $table->time('time2')->nullable();
            $table->string('customer', 11)->nullable();
            $table->string('action')->nullable();
            $table->string('approach', 50)->nullable();
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
        Schema::dropIfExists('report_actions');
    }
}
