<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $praca = \App\Models\Praca::updateOrCreate([
            'nome' => 'Franqueadora',
            'data_limite_pedido' => date('Y-m-d'),
        ]);

        $loja = \App\Models\Loja::updateOrCreate([
            'nome' => 'Franqueadora',
            'cep' => '59025-090',
            'uf' => 'RN',
            'cidade' => 'Natal',
            'bairro' => 'Cidade Alta',
            'endereco' => 'Rua Ulisses Caldas',
            'numero' => '1',
            'complemento' => '',
            'valor_minimo_pedido' => 0,
            'praca_id' => $praca->id,
        ]);

        if (\App\User::where('email', '=', 'admin@admin.com.br')->count() == 0) {
            $u = \App\User::updateOrCreate([
                'nome' => 'Administrador',
                'email' => 'admin@admin.com.br',
                'password' => bcrypt('1234'),
                'nivel_acesso' => \App\User::ACESSO_ADMIN,
                'status' => \App\User::STATUS_ATIVO,
            ]);

            $u->save();
            echo 'OK => Usuário ADMIN criado com sucesso';
        } else {
            echo 'AVISO => Usuário ADMIN já existe';
        }

        echo PHP_EOL;
    }
}
