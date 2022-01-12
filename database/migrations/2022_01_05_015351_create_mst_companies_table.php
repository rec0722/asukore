<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('zipcode')->length('7')->nullable();
            $table->foreignId('prefecture_id')->nullable();
            $table->string('city', 25)->nullable();
            $table->string('address')->nullable();
            $table->string('tel', 13)->nullable();
            $table->string('fax', 13)->nullable();
            $table->string('email')->nullable();
            $table->integer('report_type')->length('1')->nullable();
            $table->integer('report_num')->length('2')->default('1');
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
        Schema::dropIfExists('mst_companies');
    }
}
