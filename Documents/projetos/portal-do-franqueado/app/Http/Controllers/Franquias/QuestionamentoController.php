<?php

namespace App\Http\Controllers\Franquias;

use DB;
use App\User;
use App\Models\Comunicado;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\RespostaComunicadoEmail;
use App\Jobs\RespostaComunicado2Email;
use App\Models\Comunicado\Questionamento;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuestionamentoController extends Controller
{
    public function createFranqueado(Request $request)
    {
        $this->validate($request, [
            'questionamento' => 'required',
            'comunicado_id' => 'required',
            'anexos.*' => 'file',
        ]);

        DB::beginTransaction();
        try {
            $comunicado = Comunicado::findOrFail($request->get('comunicado_id'));
            if (!$comunicado->aberto_para_questionamento) {
                throw new \Exception('Este comunicado não está mais recebendo respostas');
            }
            $anexos = [];
            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $arquivo) {
                    if ($arquivo) {
                        $ext = $arquivo->getClientOriginalExtension();
                        $nome = str_slug(str_replace('.' . $ext, '', $arquivo->getClientOriginalName()) . '-' . microtime()) . '.' . $ext;
                        $anexos[] = $nome;
                        $arquivo->move('uploads/comunicados/respostas', $nome);
                    }
                }
            }

            $questionamento = Questionamento::create([
                'comunicado_id' => $request->get('comunicado_id'),
                'texto' => $request->get('questionamento'),
                'user_id' => Auth()->user()->id,
                'anexos' => $anexos,
            ]);

            $comunicado->setor->responsaveis->each(function (User $user) use ($comunicado) {
                Notificacao::create([
                    'destinatario' => $user->id,
                    'mensagem' => 'Uma resposta foi feita em um comunicado',
                    'tipo' => Notificacao::TIPO_COMUNICADO,
                    'atributos' => [
                        'comunicado_id' => $comunicado->id,
                        'comunicado_titulo' => $comunicado->titulo,
                    ],
                ]);
            });

            $this->dispatch(new RespostaComunicadoEmail($comunicado, $questionamento));

            DB::commit();

            return redirect()->to(url('comunicados/ler/' . $comunicado->id))->with('success', 'Resposta enviada com sucesso');
        } catch (ModelNotFoundException $ex) {
            return redirect()->to(url('/'))->withErrors('O comunicado não existe');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->to(url('comunicados/ler/' . $comunicado->id))->withErrors($ex->getMessage());
        }
    }

    public function createAdmin(Request $request)
    {
        $this->validate($request, [
            'questionamento' => 'required',
            'comunicado_id' => 'required',
            'anexos.*' => 'file',
        ]);

        DB::beginTransaction();
        try {
            $comunicado = Comunicado::findOrFail($request->get('comunicado_id'));
            $questionamento_to_reply = Questionamento::findOrFail($request->get('questionamento_id'));
            if (!$comunicado->aberto_para_questionamento) {
                throw new \Exception('Este comunicado não está mais recebendo respostas');
            }
            $anexos = [];
            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $arquivo) {
                    if ($arquivo) {
                        $ext = $arquivo->getClientOriginalExtension();
                        $nome = str_slug(str_replace('.' . $ext, '', $arquivo->getClientOriginalName()) . '-' . microtime()) . '.' . $ext;
                        $anexos[] = $nome;
                        $arquivo->move('uploads/comunicados/respostas', $nome);
                    }
                }
            }

            $questionamento = Questionamento::create([
                'comunicado_id' => $request->get('comunicado_id'),
                'texto' => $request->get('questionamento'),
                'user_id' => Auth()->user()->id,
                'anexos' => $anexos,
                'questionamento_id' => $questionamento_to_reply->id,
            ]);

            Notificacao::create([
                'destinatario' => $questionamento_to_reply->user->id,
                'mensagem' => 'Uma resposta foi feita em um comunicado',
                'tipo' => Notificacao::TIPO_COMUNICADO,
                'atributos' => [
                    'comunicado_id' => $comunicado->id,
                    'comunicado_titulo' => $comunicado->titulo,
                ],
            ]);

            $this->dispatch(new RespostaComunicado2Email($comunicado, $questionamento, $questionamento_to_reply));

            DB::commit();

            return redirect()->to(url('comunicados/ler/' . $comunicado->id))->with('success', 'Resposta enviada com sucesso');
        } catch (ModelNotFoundException $ex) {
            return redirect()->to(url('/'))->withErrors('O comunicado não existe');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->to(url('comunicados/ler/' . $comunicado->id))->withErrors($ex->getMessage());
        }
    }
}
