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
            $table->unsignedBigInteger('id_tipo_energia')->default(1);
            $table->unsignedBigInteger('id_tipo_instalacao')->default(1);

            $table->foreign('id_tipo_energia')->references('id_tipo_energia')->on('tipos_energia')->cascadeOnDelete();
            $table->foreign('id_tipo_instalacao')->references('id_tipo_instalacao')->on('tipos_instalacao')->cascadeOnDelete();
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
