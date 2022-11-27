<?php

use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    protected $sheetsMap = [
        'Praças' => 'pracas',
        'Lojas' => 'lojas',
        'Usuários' => 'usuarios',
        'Setores' => 'setores',
        'Categoria de Produtos' => 'categorias',
        'Lista de Produtos' => 'produtos',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::load('/tmp/implantacao.xlsx', function ($reader) {
            foreach ($reader->get() as $sheet) {
                foreach ($sheet as $row) {
                    $methodName = $this->sheetsMap[$sheet->getTitle()];
                    if (method_exists($this, $methodName)) {
                        call_user_func([$this, $methodName], $row);
                    }
                }
            }
        });
    }

    public function pracas($row)
    {
        if (! is_null($row->nome)) {
            \App\Models\Praca::create([
                'nome' => $row->nome,
                'data_limite_pedido' => $row->data_limite_para_pedidos,
            ]);
        }
    }

    public function lojas($row)
    {
        $praca = \App\Models\Praca::where('nome', 'ilike', "%{$row->praca}%")->get()->first();
        if (! $praca) {
            die('praça ' . $row->praca. ' não encontrada');
        }

        if (! is_null($row->nome)) {
            $praca->lojas()->create([
                'nome' => $row->nome,
                'cep' => $row->cep,
                'uf' => $row->uf,
                'cidade' => $row->cidade,
                'bairro' => $row->bairro,
                'endereco' => $row->endereco,
                'numero' => $row->numero,
                'complemento' => $row->complemento ? $row->complemento : ' ',
                'valor_minimo_pedido' => $row->valor,
            ]);
        }
    }

    public function usuarios($row)
    {
        if (! is_null($row->nome)) {
            $nivel = App\User::ACESSO_CAIXA;
            if ($row->nivel_de_acesso == 'Franqueadora') {
                $nivel = \App\User::ACESSO_ADMIN;
            }

            $grupo = \App\ACL\Grupo::where('nome', 'ilike', "%{$row->grupo_de_acesso}%")->get()->first();

            if (! $grupo) {
                die('Grupo não encontrado');
            }

            $loja = \App\Models\Loja::where('nome', 'ilike', "%{$row->loja}%")->get()->first();
            if (! $loja) {
                die('Loja não encontrada');
            }

            \App\User::create([
                'nome' => $row->nome,
                'email' => $row->e_mail,
                'nivel_acesso' => $nivel,
                'loja_id' => $loja->id,
                'grupo_id' => $grupo->id,
                'status' => \App\User::STATUS_ATIVO,
                'password' => bcrypt(123456),
            ]);
        }
    }

    public function setores($row)
    {
        if (! is_null($row->setor)) {
            $user = \App\User::where('nome', 'ilike', "%{$row->responsavel}%")->get()->first();
            if (! $user) {
                die('Usuário não encontrado');
            }

            $setor = \App\Models\Setor::create([
                'nome' => $row->setor,
                'tag' => $row->tag_3_caracteres,
            ]);
            $setor->responsaveis()->sync([$user->id]);
        }
    }

    public function categorias($row)
    {
        if (! is_null($row->categoria_de_produtos_da_loja)) {
            \App\Models\CategoriaProduto::create([
                'nome' => $row->categoria_de_produtos_da_loja,
                'disponivel' => true,
            ]);
        }
    }

    public function produtos($row)
    {
        if (! is_null($row->produto)) {
            $categoria = \App\Models\CategoriaProduto::where('nome', 'ilike', "%{$row->categoria}%")->get()->first();
            if (! $categoria) {
                die('categoria não encontrada');
            }

            \App\Models\Produto::create([
                'nome' => $row->produto,
                'descricao' => $row->descricao,
                'preco' => $row->preco_unitario,
                'peso' => $row->peso_kg,
                'categoria_id' => $categoria->id,
            ]);
        }
    }
}
