<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateInbodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbodies', function (Blueprint $table) {
            $table->id();
            $table->string('uhid')->unique();
            $table->float('height');
            $table->float('weight');
            $table->float('bmi');
            $table->string('body_mass');
            $table->string('muscle_strength');
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
        Schema::dropIfExists('inbodies');
    }
}
?> 
