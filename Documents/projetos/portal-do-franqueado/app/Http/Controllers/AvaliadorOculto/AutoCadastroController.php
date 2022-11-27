<?php

namespace App\Http\Controllers\AvaliadorOculto;

use DB;
use Crypt;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AvaliadorOculto\User;

class AutoCadastroController extends Controller
{
    const VIEWS_PATH = 'avaliador-oculto.admin.users.auto-cadastro.';

    private function autoCadastroForm(Request $request, $token)
    {
        return view(self::VIEWS_PATH . 'criar', compact('token'));
    }

    public function getAutoCadastro(Request $request, $token = null)
    {
        try {
            $token_date = Crypt::decrypt($token);
            $expire_date = Carbon::parse($token_date);
            if ($expire_date->lt(Carbon::now())) {
                throw new \Exception('Link expirou');
            } else {
                return $this->autoCadastroForm($request, $token);
            }
        } catch (\Exception $ex) {
            return view(self::VIEWS_PATH . 'link-expirou');
        }
    }

    public function postAutoCadastro(Request $request, $token = null)
    {
        try {
            $token_date = Crypt::decrypt($token);
            $expire_date = Carbon::parse($token_date);
            if ($expire_date->lt(Carbon::now())) {
                throw new \Exception('Link expirou');
            }
        } catch (\Exception $ex) {
            return view(self::VIEWS_PATH . 'link-expirou');
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'email' => 'required|unique:avaliador_oculto_users,email',
            'cpf' => 'required|cpf|unique:avaliador_oculto_users,cpf',
            'rg' => 'required',
            'data_nascimento' => 'required|date_format:d/m/Y',
            'escolaridade' => 'required',
            'telefone' => 'required',
            'cidade' => 'required',
            'uf' => 'required',
        ], ['cpf' => 'CPF Inválido']);

        if ($validator->fails()) {
            return redirect()->route('auto.cadastro', $token)->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            User::create([
                'nome' => $request->get('nome'),
                'email' => trim(strtolower($request->get('email'))),
                'cpf' => $request->get('cpf'),
                'rg' => $request->get('rg'),
                'data_nascimento' => Carbon::createFromFormat('d/m/Y', $request->get('data_nascimento'))->format('Y-m-d'),
                'escolaridade' => $request->get('escolaridade'),
                'banco_id' => $request->get('banco_id'),
                'agencia' => $request->get('agencia'),
                'conta_corrente' => $request->get('conta_corrente'),
                'telefone' => $request->get('telefone'),
                'cidade' => $request->get('cidade'),
                'uf' => $request->get('uf'),
                'password' => bcrypt(str_random(5)),
            ]);

            DB::commit();

            return redirect()->route('cadastro.finalizado');
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            DB::rollBack();
        }
    }

    public function getCadastroFinalizado()
    {
        return view(self::VIEWS_PATH . 'cadastro-finalizado');
    }
}
