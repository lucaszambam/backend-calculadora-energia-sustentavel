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
        Schema::table('eficiencias', function (Blueprint $table) {
            $table->dropUnique('eficiencias_id_parametro_id_segmento_unique'); 
            $table->unique(
                ['id_parametro','id_segmento','id_tipo_energia','id_tipo_instalacao'],
                'eficiencias_unq'
            );
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
