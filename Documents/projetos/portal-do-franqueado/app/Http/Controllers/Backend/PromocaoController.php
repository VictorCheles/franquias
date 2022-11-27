<?php

namespace App\Http\Controllers\Backend;

use DB;
use Validator;
use App\Models\Promocao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromocaoController extends Controller
{
    public function listar()
    {
        $tituloPagina = 'Promoções';
        $subTituloPagina = '!';
        $lista = Promocao::orderBy('fim', 'desc')->paginate(10);

        return view('backend.promocoes.listar', compact('tituloPagina', 'subTituloPagina', 'lista'));
    }

    public function processarCriar(Request $request)
    {
        $validator = Validator::make($request->all(), Promocao::regrasValidacaoCriar());
        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $atributos = [
                'user_id' => Auth()->user()->id,
            ];
            $regras = Promocao::regrasValidacaoCriar();
            unset($regras['imagem']);
            foreach ($regras as $k => $v) {
                $atributos[$k] = $request->get($k);
            }

            if ($request->hasFile('imagem') and $request->file('imagem')->isValid()) {
                $atributos['imagem'] = makeFileName($request, 'imagem');
                $request->file('imagem')->move('uploads', $atributos['imagem']);
            } else {
                throw new \Exception('A imagem não é válida');
            }

            Promocao::create($atributos);
            DB::commit();

            return redirect('/backend/promocoes/listar')->with('success', 'Promoção cadastrada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url()->current())->withErrors($ex->getMessage())->withInput();
        }
    }

    public function criar()
    {
        $tituloPagina = 'Promoção';
        $subTituloPagina = 'Adicionar';

        return view('backend.promocoes.criar', compact('tituloPagina', 'subTituloPagina'));
    }

    public function processarEditar(Request $request, $id)
    {
        $regras = Promocao::regrasValidacaoCriar();
        $regras['imagem'] = 'image';
        $validator = Validator::make($request->all(), $regras);
        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator);
        }

        try {
            $item = Promocao::findOrFail($id);
            unset($regras['imagem']);
            foreach ($regras as $k => $v) {
                $item->$k = $request->get($k);
            }
            $item->modificador_por = Auth()->user()->id;

            if ($request->hasFile('imagem') and $request->file('imagem')->isValid()) {
                @unlink('uploads/' . $item->imagem);
                $item->imagem = makeFileName($request, 'imagem');
                $request->file('imagem')->move('uploads', $item->imagem);
            }

            $item->save();

            return redirect('/backend/promocoes/listar')->with('success', 'Dados atualizados com sucesso!');
        } catch (\Exception $ex) {
            return redirect('/backend/promocoes/listar')->withErrors('Erro ao editar promoção ' . $ex->getMessage());
        }
    }

    public function editar(Request $request, $id)
    {
        $tituloPagina = 'Editar Promoção';

        try {
            $item = Promocao::findOrFail($id);
            $subTituloPagina = $item->nome;
            if (! $item->status) {
                $request->session()->flash('warning', 'Esta promoção não é mais válida, deseja mesmo modificar seus dados?');
            }

            return view('backend.promocoes.editar', compact('tituloPagina', 'subTituloPagina', 'item'));
        } catch (\Exception $ex) {
            return redirect('/backend/promocoes/listar')->withErrors('Promoção não encontrada');
        }
    }

    public function processarExcluir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'promocao_id' => 'required|integer|exists:promocoes,id',
        ]);

        if ($validator->fails()) {
            return redirect('/backend/promocoes/listar')->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $promocao = Promocao::findOrFail($request->get('promocao_id'));
            if ($promocao->cupons()->count() != 0) {
                throw new \Exception('Promoção que possui cupons, não pode ser excluída');
            }

            unlink('uploads/' . $promocao->imagem);
            $promocao->delete();
            DB::commit();

            return redirect('/backend/promocoes/listar')->with('success', 'Promoção excluida com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect('/backend/promocoes/listar')->withErrors($ex->getMessage());
        }
    }
}
