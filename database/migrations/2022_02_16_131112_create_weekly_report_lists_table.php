<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyReportListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_report_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_report_id')->nullable();
            $table->string('date', 11)->nullable();
            $table->string('weekday', 2)->nullable();
            $table->integer('situation')->length('2')->nullable();
            $table->integer('work_flg')->length('2')->nullable();
            $table->string('action')->nullable();
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
        Schema::dropIfExists('weekly_report_lists');
    }
}
