<?php

namespace App\Http\Controllers\AvaliadorOculto;

use DB;
use Validator;
use Carbon\Carbon;
use App\ACL\Recurso;
use App\Models\Loja;
use Illuminate\Http\Request;
use App\Jobs\AvaliadorOcultoEmail;
use App\Http\Controllers\Controller;
use App\Models\AvaliadorOculto\User;
use App\Models\AvaliadorOculto\Resposta;
use App\Models\AvaliadorOculto\Formulario;
use App\Models\AvaliadorOculto\RespostaSubjetiva;

class UserController extends Controller
{
    const VIEWS_PATH = 'avaliador-oculto.admin.users.';

    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_AVALIADOR_OCULTO_USUARIOS_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_AVALIADOR_OCULTO_USUARIOS_CRIAR)->only(['create', 'store']);
    }

    public function index(Request $request)
    {
        $lista = User::where(function ($q) use ($request) {
            if ($nome = $request->input('filter.nome')) {
                $nome = str_slug($nome, ' ');
                $q->where('nome', 'ilike', "%{$nome}%");
            }

            if ($cidade = $request->input('filter.cidade')) {
                $cidade = str_slug($cidade, ' ');
                $q->where('cidade', 'ilike', "%{$cidade}%");
            }

            if ($uf = $request->input('filter.uf')) {
                $q->where('uf', $uf);
            }

            if ($data_cadastro = $request->input('filter.data_cadastro')) {
                list($first, $last) = explode(' - ', $data_cadastro);
                $first = Carbon::createFromFormat('d/m/Y', $first);
                $last = Carbon::createFromFormat('d/m/Y', $last);
                $q->whereBetween('created_at', [$first, $last]);
            }
        });

        if ($request->input('filter.quem_respondeu')) {
            $lista->has('formulariosFinalizados', '>', 0);
        }

        $lista = $lista->orderBy('created_at', 'desc')->paginate(10);

        return view(self::VIEWS_PATH . 'listar', compact('lista'));
    }

    public function create()
    {
        $lojas = Loja::lojasComFormulariosAtivos();
        $lojas = $lojas->pluck('nome', 'id');

        return view(self::VIEWS_PATH . 'criar', compact('lojas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'image',
            'nome' => 'required',
            'cpf' => 'required|cpf',
            'email' => 'required|email',
            'data_nascimento' => 'date_format:d/m/Y',
            'password' => 'min:6|confirmed',
            'formulario.*' => 'required',
            'data_visita' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('avaliadoroculto.users.create')->withErrors($validator)->withInput();
        }

        $raw_password = $request->get('password');
        if (empty($raw_password)) {
            $raw_password = str_random(5);
        }
        $password = bcrypt($raw_password);

        DB::beginTransaction();

        try {
            $attributes = ['password' => $password];
            foreach (User::ALL_ATTRIBUTES as $attribute) {
                if ($request->hasFile('foto') and $request->file('foto')->isValid()) {
                    $foto = makeFileName($request, 'foto');
                    $request->file('foto')->move('uploads/cliente_oculto', $foto);
                    $attributes['foto'] = $foto;
                }

                if ($request->input($attribute)) {
                    if ($attribute == 'password') {
                    } elseif ($attributes == 'email') {
                        $attributes[$attribute] = trim(strtolower($attribute));
                    } elseif ($attribute == 'data_nascimento') {
                        $attributes[$attribute] = Carbon::createFromFormat('d/m/Y', $request->get($attribute))->format('Y-m-d');
                    } else {
                        $attributes[$attribute] = $request->get($attribute);
                    }
                }
            }
            $user = User::create($attributes);

            foreach ($request->get('formulario') as $loja_id => $formulario_id) {
                $user->formularios()->attach($formulario_id, [
                    'loja_id' => $loja_id,
                    'data_visita' => $request->get('data_visita'),
                ]);
            }

            $this->dispatch(new AvaliadorOcultoEmail($user, $raw_password));

            DB::commit();

            return redirect()->route('avaliadoroculto.users.index')->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('avaliadoroculto.users.create')->withErrors($ex->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        try {
            $lojas = Loja::lojasComFormulariosAtivos();
            $lojas = $lojas->pluck('nome', 'id');
            $item = User::findOrFail($id);

            return view(self::VIEWS_PATH . 'editar', compact('item', 'lojas'));
        } catch (\Exception $ex) {
            return redirect()->route('avaliadoroculto.users.index')->withErrors($ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = User::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect()->route('avaliadoroculto.users.index')->withErrors($ex->getMessage());
        }

        $validator = Validator::make($request->all(), [
            'foto' => 'image',
            'nome' => 'required',
            'cpf' => 'required|cpf',
            'email' => 'required|email',
            'data_nascimento' => 'date_format:d/m/Y',
            'password' => 'min:6|confirmed',
        ]);

        if (User::where('email', $item->email)->where('id', '!=', $item->id)->count() > 0) {
            return redirect()->route('avaliadoroculto.users.edit', $item->id)->withErrors('Email já está em uso')->withInput();
        }

        if ($validator->fails()) {
            return redirect()->route('avaliadoroculto.users.edit', $item->id)->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $item->nome = $request->get('nome');
            $item->cpf = $request->get('cpf');
            $item->email = trim(strtolower($request->get('email')));
            if ($request->file('foto')) {
                if ($request->hasFile('foto') and $request->file('foto')->isValid()) {
                    @unlink('uploads/cliente_oculto/' . $item->foto);
                    $item->foto = makeFileName($request, 'foto');
                    $request->file('foto')->move('uploads/cliente_oculto', $item->foto);
                }
            }
            if ($data_nascimento = $request->get('data_nascimento')) {
                $item->data_nascimento = Carbon::createFromFormat('d/m/Y', $data_nascimento)->format('Y-m-d');
            }
            if ($password = $request->get('password')) {
                $item->password = bcrypt($password);
            }
            if ($rg = $request->get('rg')) {
                $item->rg = $rg;
            }
            if ($escolaridade = $request->get('escolaridade')) {
                $item->escolaridade = $escolaridade;
            }
            if ($cidade = $request->get('cidade')) {
                $item->cidade = $cidade;
            }
            if ($telefone = $request->get('telefone')) {
                $item->telefone = $telefone;
            }
            if ($uf = $request->get('uf')) {
                $item->uf = $uf;
            }

            $item->save();

            DB::commit();

            return redirect()->route('avaliadoroculto.users.index')->with('success', 'Usuário editado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('avaliadoroculto.users.edit', $item->id)->withErrors($validator)->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $item = User::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect()->route('avaliadoroculto.users.index')->withErrors($ex->getMessage());
        }

        DB::beginTransaction();
        try {
            $item->formularios()->detach();
            Resposta::where('user_id', $item->id)->delete();
            RespostaSubjetiva::where('user_id', $item->id)->delete();
            $item->delete();
            DB::commit();

            return redirect()->route('avaliadoroculto.users.index')->with('success', 'Avaliador removido com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('avaliadoroculto.users.index')->withErrors($ex->getMessage());
        }
    }

    public function formularios($id)
    {
        try {
            $user = User::findOrFail($id);
            $lojas = Loja::lojasComFormulariosAtivos();
            $lojas = $lojas->pluck('nome', 'id');

            return view(self::VIEWS_PATH . 'formularios', compact('user', 'lojas'));
        } catch (\Exception $exception) {
            return redirect()->route('avaliadoroculto.users.index')->withErrors($exception->getMessage());
        }
    }

    public function formulariosPost(Request $request, $id)
    {
        try {
            $item = User::findOrFail($id);
        } catch (\Exception $exception) {
            return redirect()->route('avaliadoroculto.users.index')->withErrors($exception->getMessage());
        }

        $validator = Validator::make($request->all(), [
            'formulario' => 'required',
            'data_visita' => 'required',
            'voucher' => 'required|image',
            'loja' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('avaliadoroculto.users.formularios', $item->id)
                ->withErrors($validator)->withInput();
        }

        $count = $item->formularios()
            ->wherePivot('loja_id', $request->get('loja'))
            ->wherePivot('formulario_id', $request->get('formulario'))
            ->count();

        if ($count > 0) {
            session()->flash(
                'error',
                sprintf(
                    'Formulario %s da loja %s ja foi respondido por %s',
                    Formulario::find($request->get('formulario'))->nome,
                    Loja::find($request->get('loja'))->nome,
                    $item->nome
                )
            );

            return redirect()->route('avaliadoroculto.users.index');
        } else {
            try {
                DB::beginTransaction();

                $voucher = makeFileName($request, 'voucher');
                $request->file('voucher')->move('uploads/cliente_oculto_comprovantes', $voucher);

                $item->formularios()->attach($request->get('formulario'), [
                    'loja_id' => $request->get('loja'),
                    'data_visita' => $request->get('data_visita'),
                    'foto_comprovante' => $voucher,
                ]);

                DB::commit();
                return redirect()->route('avaliadoroculto.users.index')
                    ->with('success', 'Formularios adicionados com sucesso');
            } catch (\Exception $exception) {
                DB::rollBack();
                return redirect()->route('avaliadoroculto.users.index')->with('warning', $exception->getMessage());
            }
        }
    }

    public function emailChamada(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $raw_password = str_random(5);
            $user->password = bcrypt($raw_password);
            $user->save();
            $this->dispatch(new AvaliadorOcultoEmail($user, $raw_password));
            DB::commit();

            return redirect()->route('avaliadoroculto.users.index')->with('success', 'Email enviado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('avaliadoroculto.users.index')->withErrors($ex->getMessage());
        }
    }
}
