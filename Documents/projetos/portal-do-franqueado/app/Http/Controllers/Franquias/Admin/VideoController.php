<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Validator;
use App\ACL\Recurso;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\VideoAssisido;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::PUB_CANAL_FRANQUEADO)->only(['dashboard', 'show']);

        $this->middleware('acl:' . Recurso::ADM_VIDEOS_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_VIDEOS_CRIAR)->only(['create']);
        $this->middleware('acl:' . Recurso::ADM_VIDEOS_EDITAR)->only(['edit']);
        $this->middleware('acl:' . Recurso::ADM_VIDEOS_DELETAR)->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lista = Video::where(function ($q) use ($request) {
            if ($request->input('filter.titulo')) {
                $q->where('titulo', 'ilike', "%{$request->input('filter.titulo')}%");
            }

            if ($request->input('filter.tag')) {
                $q->where('tag_id', $request->input('filter.tag'));
            }
        })->orderBy('created_at', 'desc')->paginate(10);

        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Novo Vídeo',
                'url' => route('admin.video.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_VIDEOS_CRIAR,
            ],
        ]);

        return view('portal-franqueado.admin.video.listar', compact('lista', 'quick_actions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para a lista',
                'url' => route('admin.video.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_VIDEOS_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.video.criar', compact('quick_actions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required',
            'descricao' => 'required',
            'url' => 'required|url',
            'tag' => 'required|exists:tags,id',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.video.create'))->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            Video::create([
                'titulo' => $request->get('titulo'),
                'descricao' => $request->get('descricao'),
                'url' => $request->get('url'),
                'tag_id' => $request->get('tag'),
            ]);

            DB::commit();

            return redirect(route('admin.video.index'))->with('success', 'Vídeo adicionado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.video.create'))->withErrors($ex->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $item = Video::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect(url('videos/dashboard'))->withErrors('Vídeo não encontrado');
        }

        return view('portal-franqueado.videos.ver', compact('tituloPagina', 'subTituloPagina', 'item'));
    }

    public function ajaxVerVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_id' => 'required|exists:videos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false]);
        }

        DB::beginTransaction();
        try {
            VideoAssisido::firstOrCreate([
                'video_id' => $request->get('video_id'),
                'user_id' => Auth()->user()->id,
            ]);
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json(['success' => false]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $item = Video::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect(route('admin.video.index'))->withErrors('Vitrine não encontrada');
        }

        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Novo Vídeo',
                'url' => route('admin.video.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_VIDEOS_CRIAR,
            ],
            'index' => [
                'title' => 'Voltar para a lista',
                'url' => route('admin.video.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_VIDEOS_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.video.editar', compact('quick_actions', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $item = Video::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect(route('admin.video.index'))->withErrors('Vitrine não encontrada');
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'required',
            'descricao' => 'required',
            'url' => 'required|url',
            'tag_id' => 'required|exists:tags,id',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.video.edit', $item->id))->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $item->titulo = $request->get('titulo');
            $item->descricao = $request->get('descricao');
            $item->url = $request->get('url');
            $item->tag_id = $request->get('tag_id');
            $item->save();

            DB::commit();

            return redirect(route('admin.video.index'))->with('success', 'Vídeo editado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.video.edit', $item->id))->withErrors($ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $video = Video::findOrFail($id);
            VideoAssisido::where('video_id', $id)->delete();
            $video->delete();
            DB::commit();

            return redirect(route('admin.video.index'))->with('success', 'Vídeo deletado com sucesso!!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.video.index'))->withErrors($ex->getMessage());
        }
    }

    public function dashboard(Request $request)
    {
        $lista = Video::with('tag')->where(function ($q) use ($request) {
            if ($request->get('q')) {
                $q->orWhereHas('tag', function ($q) use ($request) {
                    $q->where('titulo', 'ilike', "%{$request->get('q')}%");
                });
                $q->orWhere('titulo', 'ilike', "%{$request->get('q')}%");
                $q->orWhere('descricao', 'ilike', "%{$request->get('q')}%");
            }
        })->orderBy('created_at', 'desc')->paginate(4);
        $template = 'dashboard';
        if (($request->has('page') and $request->get('page') > 1) or $request->has('q')) {
            $template = 'dashboard-2';
        }

        return view('portal-franqueado.videos.' . $template, compact('tituloPagina', 'subTituloPagina', 'lista'));
    }
}
