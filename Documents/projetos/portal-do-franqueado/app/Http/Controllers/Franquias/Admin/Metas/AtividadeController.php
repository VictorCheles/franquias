<?php

namespace App\Http\Controllers\Franquias\Admin\Metas;

use App\Models\Metas\Meta;
use Illuminate\Http\Request;
use App\Models\Metas\Atividade;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AtividadeController extends Controller
{
    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'descricao' => 'required',
            'valor' => 'required',
        ]);

        Meta::findOrFail($id);
        DB::beginTransaction();

        try {
            Atividade::create([
                'descricao' => $request->get('descricao'),
                'valor' => $request->get('valor'),
                'meta_id' => $id,
            ]);

            DB::commit();

            return redirect()->route('admin.modulo-de-metas.metas.show', $id)->with('success', 'Atividade cadastrada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.modulo-de-metas.metas.show', $id)->withErrors($ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'descricao' => 'required',
            'valor' => 'required',
        ]);

        $atividade = Atividade::findOrFail($id);
        DB::beginTransaction();

        try {
            $atividade->descricao = $request->get('descricao');
            $atividade->valor = $request->get('valor');
            $atividade->save();
            DB::commit();

            return redirect()->route('admin.modulo-de-metas.metas.index', $atividade->meta_id)->with('success', 'Atividade editada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.modulo-de-metas.metas.show', $atividade->meta_id)->withErrors($ex->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Atividade::findOrFail($id);
        DB::beginTransaction();
        try {
            $meta_id = $item->meta_id;
            $item->delete();

            DB::commit();

            return redirect()->route('admin.modulo-de-metas.metas.show', $meta_id)->with('success', 'Atividade deletada com sucesso');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->route('admin.modulo-de-metas.metas.show', $item->meta_id)->withErrors($exception->getMessage());
        }
    }
}
