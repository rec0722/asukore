<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstDeptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_depts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable();
            $table->string('name', 25);
            $table->integer('report_type')->length('1')->nullable();
            $table->string('report_text1', 11)->nullable();
            $table->string('report_text2', 11)->nullable();
            $table->string('report_text3', 25)->nullable();
            $table->string('report_text4', 25)->nullable();
            $table->integer('report_num')->length('2')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_depts');
    }
}
