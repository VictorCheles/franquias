<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Validator;
use App\Models\Tag;
use App\ACL\Recurso;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\VideoAssisido;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_TAGS_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_TAGS_CRIAR)->only(['create']);
        $this->middleware('acl:' . Recurso::ADM_TAGS_EDITAR)->only(['edit']);
        $this->middleware('acl:' . Recurso::ADM_TAGS_DELETAR)->only(['destroy']);
        $this->middleware('acl:' . Recurso::ADM_TAGS_VIDEOS_DELETAR)->only(['destroyCascade']);
        $this->middleware('acl:' . Recurso::ADM_TAGS_VIDEOS_REMANEJAR)->only(['destroyRemanejamento']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Nova Tag',
                'url' => route('admin.tag.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_TAGS_CRIAR,
            ],
        ]);

        $lista = Tag::orderBy('titulo', 'asc')->paginate(10);

        return view('portal-franqueado.admin.tag.listar', compact('lista', 'quick_actions'));
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
                'title' => 'Voltar para lista',
                'url' => route('admin.tag.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_TAGS_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.tag.criar', compact('quick_actions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valitator = Validator::make($request->all(), [
            'titulo' => 'required|max:30',
            'cor' => 'required|max:20',
        ]);

        if ($valitator->fails()) {
            return redirect(route('admin.tag.create'))->withErrors($valitator)->withInput();
        }

        DB::beginTransaction();
        try {
            Tag::create([
                'titulo' => $request->get('titulo'),
                'cor' => $request->get('cor'),
            ]);

            DB::commit();

            return redirect(route('admin.tag.index'))->with('success', 'Tag criada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.tag.create'))->withErrors($ex->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(route('admin.tag.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $item = Tag::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect(route('admin.tag.index'))->withErrors('Tag não encontrada');
        }

        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Nova Tag',
                'url' => route('admin.tag.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_TAGS_CRIAR,
            ],
            'index' => [
                'title' => 'Voltar para a lista',
                'url' => route('admin.tag.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_TAGS_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.tag.editar', compact('', 'item', 'quick_actions'));
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
            $item = Tag::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect(route('admin.tag.index'))->withErrors('Tag não encontrada');
        }

        $valitator = Validator::make($request->all(), [
            'titulo' => 'required|max:30',
            'cor' => 'required|max:20',
        ]);

        if ($valitator->fails()) {
            return redirect(route('admin.tag.edit', $item->id))->withErrors($valitator)->withInput();
        }

        DB::beginTransaction();
        try {
            $item->titulo = $request->get('titulo');
            $item->cor = $request->get('cor');
            $item->save();

            DB::commit();

            return redirect(route('admin.tag.index'))->with('success', 'Tag atualizada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.tag.edit', $item->id))->withErrors($ex->getMessage())->withInput();
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
        try {
            $item = Tag::findOrFail($id);
            if ($item->videos->count() == 0) {
                $item->delete();

                return redirect()->route('admin.tag.index')->with('success', 'Tag excluida com sucesso');
            } else {
                $tituloPagina = 'Tag com Vídeos!';
                $subTituloPagina = $item->titulo_formatted;
                $tags = Tag::where('id', '!=', $item->id)->orderBy('titulo')->lists('titulo', 'id');

                return view('portal-franqueado.admin.tag.deletar-com-videos', compact('tituloPagina', 'subTituloPagina', 'item', 'tags'));
            }
        } catch (\Exception $ex) {
            return redirect()->route('admin.tag.index')->withErrors($ex->getMessage());
        }
    }

    public function destroyCascade($id)
    {
        DB::beginTransaction();
        try {
            $item = Tag::findOrFail($id);
            $item->videos->each(function (Video $video) {
                VideoAssisido::where('video_id', $video->id)->delete();
                $video->delete();
            });
            $item->delete();
            DB::commit();

            return redirect()->route('admin.tag.index')->with('success', 'tag e vídeos deletados com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.tag.index')->withErrors($ex->getMessage());
        }
    }

    public function destroyRemanejamento(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'tag_id' => 'required|exists:tags,id',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->all());
            }
            $item = Tag::findOrFail($id);
            Video::where('tag_id', $item->id)->update(['tag_id' => $request->get('tag_id')]);
            $item->delete();
            DB::commit();

            return redirect()->route('admin.tag.index')->with('success', 'Tag deletada e vídeos remanejados com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.tag.index')->withErrors($ex->getMessage());
        }
    }
}
