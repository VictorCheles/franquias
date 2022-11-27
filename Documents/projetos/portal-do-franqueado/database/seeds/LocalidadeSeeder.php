<?php

use Illuminate\Database\Seeder;

class LocalidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\App\Models\Localidade\Regiao::all()->count() > 0) {
            echo 'ja foi';
        } else {
            $regioes = collect(['Norte', 'Nordeste', 'Centro-Oeste', 'Sudeste', 'Sul']);

            $regioes->each(function ($regiao) {
                \App\Models\Localidade\Regiao::create(['nome' => $regiao]);
            });

            $nordeste = \App\Models\Localidade\Regiao::whereNome('Nordeste')->get()->first();

            $rn = \App\Models\Localidade\Estado::create(['regiao_id' => $nordeste->id, 'nome' => 'Rio Grande do Norte', 'sigla' => 'RN']);
            $rn->municipios()->createMany([['nome' => 'Natal'], ['nome' => 'Mossoró']]);

            $pi = \App\Models\Localidade\Estado::create(['regiao_id' => $nordeste->id, 'nome' => 'Piauí', 'sigla' => 'PI']);
            $pi->municipios()->create(['nome' => 'Teresina']);

            $pe = \App\Models\Localidade\Estado::create(['regiao_id' => $nordeste->id, 'nome' => 'Pernambuco', 'sigla' => 'PE']);
            $pe->municipios()->create(['nome' => 'Recife']);

            $pb = \App\Models\Localidade\Estado::create(['regiao_id' => $nordeste->id, 'nome' => 'Paraíba', 'sigla' => 'PB']);
            $pb->municipios()->create(['nome' => 'João Pessoa']);

            $ce = \App\Models\Localidade\Estado::create(['regiao_id' => $nordeste->id, 'nome' => 'Ceará', 'sigla' => 'CE']);
            $ce->municipios()->create(['nome' => 'Fortaleza']);
        }
    }
}
