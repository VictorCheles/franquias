<?php

use Illuminate\Database\Seeder;

class CodigoOriginalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Cupom::all()->each(function (\App\Models\Cupom $item) {
            $item->timestamps = false;
            $item->codigo_original = $item->code;
            $item->save();
        });
    }
}
