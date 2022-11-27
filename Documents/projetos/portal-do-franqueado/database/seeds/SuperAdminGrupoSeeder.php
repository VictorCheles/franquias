<?php

use Illuminate\Database\Seeder;

class SuperAdminGrupoSeeder extends Seeder
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
            $grupo = \App\ACL\Grupo::updateOrCreate([
                'nome' => 'Super Administradores',
            ]);
            $grupo->recursos()->sync([\App\ACL\Recurso::SUPER_ADMIN]);

            $u = \App\User::find(1);
            if ($u) {
                $u->grupo_id = $grupo->id;
            }

            $grupo_franqueado = \App\ACL\Grupo::updateOrCreate([
                'nome' => 'Franqueado',
            ]);
            $grupo_franqueado->recursos()->sync([
                \App\ACL\Recurso::PUB_CANAL_FRANQUEADO,
                \App\ACL\Recurso::PUB_COMUNICADOS,
                \App\ACL\Recurso::PUB_ARQUIVOS,
                \App\ACL\Recurso::PUB_SOLICITACOES,
                \App\ACL\Recurso::PUB_PEDIDOS,
                \App\ACL\Recurso::PUB_ENQUETES,
                \App\ACL\Recurso::PUB_BUSCA,
            ]);

            $grupo_franqueadora = \App\ACL\Grupo::updateOrCreate(['nome' => 'Franqueadora']);
            $grupo_franqueadora->recursos()->sync(array_merge(
                [
                \App\ACL\Recurso::PUB_CANAL_FRANQUEADO,
                \App\ACL\Recurso::PUB_COMUNICADOS,
                \App\ACL\Recurso::PUB_ARQUIVOS,
                \App\ACL\Recurso::PUB_SOLICITACOES,
                \App\ACL\Recurso::PUB_PEDIDOS,
                \App\ACL\Recurso::PUB_ENQUETES,
                \App\ACL\Recurso::PUB_BUSCA,
                \App\ACL\Recurso::ADM_VER_MENU,

            ],
            \App\ACL\Recurso::ADM_PASTAS,
                \App\ACL\Recurso::ADM_ARQUIVOS,
                \App\ACL\Recurso::ADM_VITRINES,
            \App\ACL\Recurso::ADM_SOLICITACOES,
                \App\ACL\Recurso::ADM_VIDEOS,
                \App\ACL\Recurso::ADM_ENQUETES
            ));

            $grupo_fornecimento = \App\ACL\Grupo::updateOrCreate(['nome' => 'Fornecimento']);
            $grupo_fornecimento->recursos()->sync(array_merge(
                [
                \App\ACL\Recurso::PUB_CANAL_FRANQUEADO,
                \App\ACL\Recurso::PUB_COMUNICADOS,
                \App\ACL\Recurso::PUB_ARQUIVOS,
                \App\ACL\Recurso::PUB_SOLICITACOES,
                \App\ACL\Recurso::PUB_PEDIDOS,
                \App\ACL\Recurso::PUB_ENQUETES,
                \App\ACL\Recurso::PUB_BUSCA,
                \App\ACL\Recurso::ADM_VER_MENU,

            ],
                \App\ACL\Recurso::ADM_PASTAS,
                \App\ACL\Recurso::ADM_ARQUIVOS,
                \App\ACL\Recurso::ADM_VITRINES,
                \App\ACL\Recurso::ADM_SOLICITACOES,
                \App\ACL\Recurso::ADM_VIDEOS,
                \App\ACL\Recurso::ADM_ENQUETES,
                \App\ACL\Recurso::ADM_CATEGORIAS,
                \App\ACL\Recurso::ADM_PRODUTOS,
                \App\ACL\Recurso::ADM_FORNECIMENTO
                ));

            $grupo_marketing = \App\ACL\Grupo::updateOrCreate(['nome' => 'Marketing']);
            $grupo_marketing->recursos()->sync(array_merge(
                [
                \App\ACL\Recurso::PUB_CANAL_FRANQUEADO,
                \App\ACL\Recurso::PUB_COMUNICADOS,
                \App\ACL\Recurso::PUB_ARQUIVOS,
                \App\ACL\Recurso::PUB_SOLICITACOES,
                \App\ACL\Recurso::PUB_PEDIDOS,
                \App\ACL\Recurso::PUB_ENQUETES,
                \App\ACL\Recurso::PUB_BUSCA,
                \App\ACL\Recurso::ADM_VER_MENU,

            ],
                \App\ACL\Recurso::ADM_PASTAS,
                \App\ACL\Recurso::ADM_ARQUIVOS,
                \App\ACL\Recurso::ADM_VITRINES,
                \App\ACL\Recurso::ADM_SOLICITACOES,
                \App\ACL\Recurso::ADM_VIDEOS,
                \App\ACL\Recurso::ADM_ENQUETES,
                \App\ACL\Recurso::ADM_COMUNICADOS,
                \App\ACL\Recurso::ADM_SETORES,
                \App\ACL\Recurso::ADM_VIDEOS,
                \App\ACL\Recurso::ADM_TAGS
            ));

            \DB::commit();
        } catch (Exception $ex) {
            \DB::rollBack();
            echo 'deu bosta' . PHP_EOL . $ex->getMessage() . PHP_EOL;
            var_dump($ex);
        }
    }
}
