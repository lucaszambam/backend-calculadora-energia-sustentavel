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
        Schema::create('simulacoes', function (Blueprint $table) {
            $table->id('id_simulacao');

            // FK para cidades
            $table->unsignedBigInteger('id_cidade');
            $table->foreign('id_cidade')
                ->references('id_cidade')
                ->on('cidades')
                ->cascadeOnDelete();

            // FK para tipos_energia
            $table->unsignedBigInteger('id_tipo_energia');
            $table->foreign('id_tipo_energia')
                ->references('id_tipo_energia')
                ->on('tipos_energia')
                ->cascadeOnDelete();

            // FK para tipos_instalacao
            $table->unsignedBigInteger('id_tipo_instalacao');
            $table->foreign('id_tipo_instalacao')
                ->references('id_tipo_instalacao')
                ->on('tipos_instalacao')
                ->cascadeOnDelete();

            // FK para segmentos
            $table->unsignedBigInteger('id_segmento');
            $table->foreign('id_segmento')
                ->references('id_segmento')
                ->on('segmentos')
                ->cascadeOnDelete();

            // FK para parametros
            $table->unsignedBigInteger('id_parametro');
            $table->foreign('id_parametro')
                ->references('id_parametro')
                ->on('parametros')
                ->cascadeOnDelete();

            // Campos de simulação
            $table->decimal('valor_conta_medio', 12, 2);
            $table->decimal('consumo_kwh_estimado', 12, 2);
            $table->decimal('economia_reais', 12, 2);
            $table->decimal('economia_percentual', 5, 2);
            $table->decimal('co2_evitado', 12, 2);
            $table->string('status_contato')->default('pendente'); // RF11.1
            $table->string('nome_contato');
            $table->string('email_contato')->nullable();
            $table->string('telefone_contato')->nullable();
            $table->timestamp('data_hora')->useCurrent();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simulacoes');
    }
};
