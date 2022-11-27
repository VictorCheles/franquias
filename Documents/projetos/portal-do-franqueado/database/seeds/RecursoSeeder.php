<?php

use Illuminate\Database\Seeder;

class RecursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::beginTransaction();
        try {
            foreach (\App\ACL\Recurso::RECURSOS as $id => $descricao) {
                \App\ACL\Recurso::updateOrCreate([
                    'id' => $id,
                    'descricao' => $descricao,
                ]);
            }
            \DB::commit();
        } catch (Exception $ex) {
            echo 'deu bosta ' . PHP_EOL . $ex->getMessage() . PHP_EOL;
            \DB::rollBack();
        }
    }
}
