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
        Schema::create('eficiencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_parametro');
            $table->foreign('id_parametro')
                ->references('id_parametro')
                ->on('parametros')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('id_segmento');
            $table->foreign('id_segmento')
                ->references('id_segmento')
                ->on('segmentos')
                ->cascadeOnDelete();
            $table->decimal('eficiencia_valor', 5, 4); // fração [0..1]
            $table->timestamps();
            $table->unique(['id_parametro','id_segmento']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eficiencias');
    }
};
