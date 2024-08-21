<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->double('indonesia')->default(0);
            $table->double('matematika')->default(0);
            $table->double('ipa')->default(0);
            $table->double('ips')->default(0);
            $table->double('inggris')->default(0);
            $table->double('pkn')->default(0);
            $table->double('agama')->default(0);
            $table->decimal('nilaiakhir')->default(0);
            $table->decimal('bobot')->default(0);
            $table->decimal('bobotnilai')->default(0);
            $table->integer('id_user')->default(0);
            $table->integer('id_profile')->default(0);
            $table->integer('id_sertifikat')->default(0);

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
        Schema::dropIfExists('nilai');
    }
}
