<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapehTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapeh', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_status_id')->nullable();
            $table->integer('mapeh_id')->nullable();
            $table->double('first')->nullable();
            $table->double('second')->nullable();
            $table->double('third')->nullable();
            $table->double('fourth')->nullable();
            $table->double('average')->nullable();
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
        Schema::dropIfExists('mapeh');
    }
}
