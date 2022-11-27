<?php

namespace App\Http\Controllers\Backend;

use DB;
use Hash;
use App\User;
use Validator;
use App\ACL\Recurso;
use Illuminate\Http\Request;
use App\Models\Personificacao;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function __construct()
    {
        if (str_is('*' . env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', url()->current())) {
            $this->middleware('acl:' . Recurso::ADM_USUARIOS_LISTAR)->only(['listar']);
            $this->middleware('acl:' . Recurso::ADM_USUARIOS_EDITAR)->only(['editar']);
            $this->middleware('acl:' . Recurso::ADM_USUARIOS_CRIAR)->only(['criar']);
            $this->middleware('acl:' . Recurso::ADM_PERSONIFICACAO)->only(['personificacao']);
        }
    }

    public function listar(Request $request)
    {
        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Novo usuário',
                'url' => url('/backend/usuarios/criar'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_USUARIOS_CRIAR,
            ],
        ]);

        $lista = User::where(function ($q) use ($request) {
            if ($request->input('filter.nome')) {
                $q->where('nome', 'like', "%{$request->input('filter.nome')}%");
            }
            if ($request->input('filter.email')) {
                $q->where('email', 'like', "%{$request->input('filter.email')}%");
            }
            if ($request->input('filter.nivel_acesso')) {
                $q->where('nivel_acesso', '=', $request->input('filter.nivel_acesso'));
            }
            if ($request->input('filter.status')) {
                $q->where('status', '=', $request->input('filter.status'));
            }
            if ($request->input('filter.loja_id')) {
                $q->whereHas('lojas', function ($q) use ($request) {
                    $q->where('id', $request->input('filter.loja_id'));
                });
            }
            if ($grupo = $request->input('filter.grupo_id')) {
                $q->whereGrupoId($grupo);
            }
        })->orderBy('status')->orderBy('nivel_acesso', 'desc')->orderBy('nome', 'asc')->paginate(10);

        return view('backend.usuarios.listar', compact('lista', 'quick_actions'));
    }

    public function processarCriar(Request $request)
    {
        $validator = Validator::make($request->all(), User::$regrasValidacaoCriar);
        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            if ($request->file('foto')) {
                if ($request->hasFile('foto') and $request->file('foto')->isValid()) {
                    $foto = makeFileName($request, 'foto');
                    $request->file('foto')->move('uploads/usuario', $foto);
                }
            }

            $user = User::create([
                'nome' => $request->get('nome'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'status' => $request->get('status'),
                'nivel_acesso' => $request->get('nivel_acesso'),
                'grupo_id' => $request->get('grupo_id'),
                'foto' => $foto,
            ]);

            $user->lojas()->sync($request->get('loja_id'));

            DB::commit();

            return redirect('/backend/usuarios/listar')->with('success', 'Usuário criado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect('/backend/usuarios/listar')->withErrors($ex->getMessage());
        }
    }

    public function criar()
    {
        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para a lista',
                'url' => url('/backend/usuarios/listar'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_USUARIOS_LISTAR,
            ],
        ]);

        return view('backend.usuarios.criar', compact('quick_actions'));
    }

    public function processarEditar(Request $request, $id)
    {
        $regras = User::$regrasValidacaoCriar;
        $regras['email'] = 'required|unique:users,email,' . $id;
        unset($regras['loja_id']);
        if (! $request->has('password')) {
            unset($regras['password']);
        }

        $validator = Validator::make($request->all(), $regras);
        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $item = User::findOrFail($id);
            $item->nome = $request->get('nome');
            $item->email = $request->get('email');
            $item->nivel_acesso = $request->get('nivel_acesso');
            $item->status = $request->get('status');
            if ($request->has('loja_id')) {
                $item->lojas()->sync($request->get('loja_id'));
            }

            if ($request->has('grupo_id')) {
                $item->grupo_id = $request->get('grupo_id');
            }
            if ($request->has('password')) {
                $item->password = bcrypt($request->get('password'));
            }
            if ($request->file('foto')) {
                if ($request->hasFile('foto') and $request->file('foto')->isValid()) {
                    @unlink('uploads/usuario/' . $item->foto);
                    $item->foto = makeFileName($request, 'foto');
                    $request->file('foto')->move('uploads/usuario', $item->foto);
                }
            }
            $item->save();

            DB::commit();

            return redirect('/backend/usuarios/listar')->with('success', 'Dados atualizados com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url()->current())->withErrors($ex->getMessage());
        }
    }

    public function editar(Request $request, $id)
    {
        try {
            $quick_actions = $this->quickActionButtons([
                'index' => [
                    'title' => 'Voltar para a lista',
                    'url' => url('/backend/usuarios/listar'),
                    'icon' => 'fa fa-arrow-left',
                    'resource' => \App\ACL\Recurso::ADM_USUARIOS_LISTAR,
                ],
                'create' => [
                    'title' => 'Novo usuário',
                    'url' => url('/backend/usuarios/criar'),
                    'icon' => 'fa fa-plus',
                    'resource' => \App\ACL\Recurso::ADM_USUARIOS_CRIAR,
                ],
            ]);

            $item = User::findOrFail($id);

            return view('backend.usuarios.editar', compact('quick_actions', 'item'));
        } catch (\Exception $ex) {
            return redirect('/backend/usuarios/listar')->withErrors('Usuário não encontrado');
        }
    }

    public function meusDados()
    {
        try {
            $item = Auth()->user();

            return view('backend.usuarios.editar-meus-dados', compact('item'));
        } catch (\Exception $ex) {
            return redirect('/backend/usuarios/listar')->withErrors('Usuário não encontrado');
        }
    }

    public function processarMeusDados(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'min:6|confirmed',
            'original_password' => 'required',
            'foto' => 'image',
        ]);

        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator);
        }

        if (! Hash::check($request->get('original_password'), Auth()->user()->getAuthPassword())) {
            return redirect(url()->current())->withErrors('Senha atual não confere');
        }

        try {
            $user = Auth()->user();
            if ($request->file('foto')) {
                if ($request->hasFile('foto') and $request->file('foto')->isValid()) {
                    @unlink('uploads/usuario/' . $user->foto);
                    $foto = makeFileName($request, 'foto');
                    $request->file('foto')->move('uploads/usuario', $foto);
                    $user->foto = $foto;
                }
            }
            if ($pass = $request->get('password')) {
                $user->password = bcrypt($pass);
            }

            $user->save();

            return redirect(url()->current())->with('success', 'Senha atualizada com sucesso!');
        } catch (\Exception $ex) {
            return redirect(url()->current())->withErrors($ex->getMessage());
        }
    }

    public function personificacao(Request $request, $personagem_id)
    {
        try {
            $personagem = User::findOrFail($personagem_id);
        } catch (ModelNotFoundException $ex) {
            return redirect(url('backend/usuarios/listar'))->withErrors($ex->getMessage());
        }

        if ($personagem->status != User::STATUS_ATIVO) {
            return redirect(url('backend/usuarios/listar'))->withErrors('O usuário não está ativo');
        }

        $ator = Auth()->user();

        $personificacao = Personificacao::create([
            'ator_id' => $ator->id,
            'personagem_id' => $personagem->id,
        ]);

        Auth()->logout();
        \Session::flush();
        Auth()->login($personagem);
        \Session::put('personificacao', $personificacao);
        \Session::put('recursos', $personagem->grupoACL->recursos);
        \Session::save();

        return redirect('/')->with('success', 'Agora você é [' . $personagem->nome . ']');
    }

    public function despersonificacao(Request $request)
    {
        $personificacao = $request->session()->get('personificacao');
        Auth()->logout();
        \Session::flush();
        Auth()->login($personificacao->ator);
        \Session::put('recursos', $personificacao->ator->grupoACL->recursos);
        \Session::save();

        return redirect('/');
    }
}
