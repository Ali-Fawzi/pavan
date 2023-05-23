<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('age');
            $table->string('number');
            $table->string('address');
            $table->text('health_status');
            $table->text('visits_one');
            $table->text('visits_two')->nullable();
            $table->text('visits_three')->nullable();
            $table->text('visits_four')->nullable();
            $table->double('price')->nullable();
            $table->string('x_rays')->nullable();
            $table->string('doctor_name');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('patients');
    }
};
