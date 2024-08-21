<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelolaWawancarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelolawawancara', function (Blueprint $table) {
            $table->id('id_kelolawawancara');
            $table->string('hasilkelola')->nullable();
            $table->decimal('bobot')->default(0);
            $table->decimal('bobotwawancara')->default(0);
            $table->integer('id_wawancara');
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
        Schema::dropIfExists('kelolawawancara');
    }
}
