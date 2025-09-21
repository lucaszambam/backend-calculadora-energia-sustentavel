<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->id('id_parametro');
            $table->unsignedBigInteger('id_cidade');
            $table->foreign('id_cidade')
                ->references('id_cidade')
                ->on('cidades')
                ->cascadeOnDelete();

            $table->decimal('tarifa_base', 8, 4);       // R$/kWh
            $table->decimal('taxa_distribuicao', 5, 4); // fração (ex.: 0.2500)
            $table->decimal('co2_por_kwh', 8, 4);      // kg/kWh
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
        Schema::dropIfExists('parametros');
    }
};
