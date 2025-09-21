<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TabelasBasicasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Segmentos
        DB::table('segmentos')->insert([
            ['id_segmento' => 1, 'nome' => 'Residencial'],
            ['id_segmento' => 2, 'nome' => 'Comercial'],
            ['id_segmento' => 3, 'nome' => 'Industrial'],
            ['id_segmento' => 4, 'nome' => 'Rural'],
        ]);

        // Tipos de Energia
        DB::table('tipos_energia')->insert([
            ['id_tipo_energia' => 1, 'descricao' => 'Solar'],
            ['id_tipo_energia' => 2, 'descricao' => 'Eólica'],
        ]);

        // Tipos de Instalação
        DB::table('tipos_instalacao')->insert([
            ['id_tipo_instalacao' => 1, 'descricao' => 'Monofásico'],
            ['id_tipo_instalacao' => 2, 'descricao' => 'Bifásico'],
            ['id_tipo_instalacao' => 3, 'descricao' => 'Trifásico'],
        ]);

        // Estados
        DB::table('estados')->insert([
            ['id_estado' => 1, 'sigla' => 'SC', 'nome' => 'Santa Catarina'],
            ['id_estado' => 2, 'sigla' => 'SP', 'nome' => 'São Paulo'],
        ]);

        // Cidades
        DB::table('cidades')->insert([
            ['id_cidade' => 1, 'nome' => 'Florianópolis', 'id_estado' => 1],
            ['id_cidade' => 2, 'nome' => 'Joinville', 'id_estado' => 1],
            ['id_cidade' => 3, 'nome' => 'Rio do Sul', 'id_estado' => 1],
            ['id_cidade' => 4, 'nome' => 'São Paulo', 'id_estado' => 2],
            ['id_cidade' => 5, 'nome' => 'Campinas', 'id_estado' => 2],
        ]);

        // Parâmetros mínimos (tarifa_base, taxa_distribuicao, co2_por_kwh)
        DB::table('parametros')->insert([
            ['id_parametro' => 1, 'id_cidade' => 1, 'tarifa_base' => 0.75, 'taxa_distribuicao' => 0.10, 'co2_por_kwh' => 0.084],
            ['id_parametro' => 2, 'id_cidade' => 2, 'tarifa_base' => 0.70, 'taxa_distribuicao' => 0.10, 'co2_por_kwh' => 0.084],
            ['id_parametro' => 3, 'id_cidade' => 3, 'tarifa_base' => 0.70, 'taxa_distribuicao' => 0.10, 'co2_por_kwh' => 0.084],
            ['id_parametro' => 4, 'id_cidade' => 4, 'tarifa_base' => 0.80, 'taxa_distribuicao' => 0.10, 'co2_por_kwh' => 0.084],
            ['id_parametro' => 5, 'id_cidade' => 5, 'tarifa_base' => 0.78, 'taxa_distribuicao' => 0.10, 'co2_por_kwh' => 0.084],
        ]);

        // Eficiências por segmento (valores fictícios de exemplo)
        DB::table('eficiencias')->insert([
            ['id_parametro' => 1, 'id_segmento' => 1, 'eficiencia_valor' => 0.85],
            ['id_parametro' => 1, 'id_segmento' => 2, 'eficiencia_valor' => 0.80],
            ['id_parametro' => 1, 'id_segmento' => 3, 'eficiencia_valor' => 0.78],
            ['id_parametro' => 1, 'id_segmento' => 4, 'eficiencia_valor' => 0.82],
        ]);

        DB::table('administradores')->updateOrInsert(
            ['email' => 'admin@calc.com'],
            ['nome' => 'Admin', 'senha_hash' => Hash::make('Admin@2025'), 'updated_at'=>now(), 'created_at'=>now()]
        );
    }


}
