<?php

namespace App;

use DB;
use Auth;
use File;
use App\ACL\Grupo;
use App\ACL\Recurso;
use App\Models\Loja;
use App\Models\Cupom;
use App\Models\Setor;
use App\Models\Video;
use App\Models\Enquete;
use App\Models\Comunicado;
use App\Models\Notificacao;
use App\Models\Solicitacao;
use App\Models\UsuarioResposta;
use App\Models\Mensagens\Mensagem;
use App\Models\ConsultoriaCampo\Visita;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User.
 *
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property int $nivel_acesso
 * @property int $status
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $grupo_id
 * @property string|null $foto
 * @property bool $aceite
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cupom[] $cupons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Enquete[] $enquetes
 * @property-read mixed $nivel_acesso_formatted
 * @property-read mixed $nome_formal
 * @property-read mixed $primeiro_nome
 * @property-read mixed $status_formatted
 * @property-read mixed $thumbnail
 * @property-read \App\ACL\Grupo $grupoACL
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Loja[] $lojas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mensagens\Mensagem[] $mensagensEnviadas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mensagens\Mensagem[] $mensagensRecebidas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notificacao[] $notificacoes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Setor[] $setores
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Solicitacao[] $solicitacoes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\Visita[] $visitas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User admin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User ativo()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User semMim()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAceite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNivelAcesso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    const ACESSO_CAIXA = 1;
    const ACESSO_GERENTE = 2;
    const ACESSO_ADMIN = 99;

    const STATUS_ATIVO = 1;
    const STATUS_SOLICITADO = 2;
    const STATUS_BLOQUEADO = 3;

    public static $mapStatus = [
        self::STATUS_ATIVO => 'Ativo',
        self::STATUS_SOLICITADO => 'Cadastro solicitado',
        self::STATUS_BLOQUEADO => 'Acesso Bloqueado',
    ];

    public static $mapStatusStyled = [
        self::STATUS_ATIVO => '<span class="label label-success">Ativo</span>',
        self::STATUS_SOLICITADO => '<span class="label label-warning">Cadastro solicitado</span>',
        self::STATUS_BLOQUEADO => '<span class="label label-danger">Acesso Bloqueado</span>',
    ];

    public static $mapAcesso = [
        self::ACESSO_CAIXA => 'Franqueado',
        self::ACESSO_ADMIN => 'Franqueadora',
    ];

    public static $mapAcessoStyled = [
        self::ACESSO_CAIXA => '<span class="label label-default">Franqueado</span>',
        self::ACESSO_ADMIN => '<span class="label label-info">Franqueadora</span>',
    ];

    public static $regrasValidacaoCriar = [
        'nome' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:6|confirmed',
        'nivel_acesso' => 'required',
        'loja_id' => 'required',
        'status' => 'required',
        'grupo_id' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'nivel_acesso',
        'status',
        'grupo_id',
        'foto',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function scopeSemMim($q)
    {
        return $q->where('id', '!=', Auth::user()->id);
    }

    public function scopeAtivo($q)
    {
        return $q->whereStatus(self::STATUS_ATIVO);
    }

    public function scopeAdmin($q)
    {
        return $q->whereNivelAcesso(self::ACESSO_ADMIN);
    }

    public function getThumbnailAttribute()
    {
        if ($this->foto and File::exists('uploads/usuario/' . $this->foto)) {
            return asset('uploads/usuario/' . $this->foto);
        }

        return 'https://placehold.it/354x472';
    }

    public function getNomeFormalAttribute()
    {
        $nomes = explode(' ', $this->nome);

        return reset($nomes) == end($nomes) ? reset($nomes) : reset($nomes) . ' ' . end($nomes);
    }

    public function getPrimeiroNomeAttribute()
    {
        $nomes = explode(' ', $this->nome);

        return reset($nomes);
    }

    public function getNivelAcessoFormattedAttribute()
    {
        return self::$mapAcessoStyled[$this->nivel_acesso];
    }

    public function getStatusFormattedAttribute()
    {
        return self::$mapStatusStyled[$this->status];
    }

    public function isAdmin()
    {
        return $this->nivel_acesso === self::ACESSO_ADMIN;
    }

    public function grupoACL()
    {
        return $this->hasOne(Grupo::class, 'id', 'grupo_id');
    }

    public function hasRoles($roles = [])
    {
        if (session('recursos')->pluck('id')->contains(Recurso::SUPER_ADMIN)) {
            return true;
        }

        foreach ($roles as $role) {
            if (! session('recursos')->pluck('id')->contains($role)) {
                return false;
            }
        }

        return true;
    }

    public function hasAnyRole($roles = [])
    {
        if (session('recursos')->pluck('id')->contains(Recurso::SUPER_ADMIN)) {
            return true;
        }

        foreach ($roles as $role) {
            if (session('recursos')->pluck('id')->contains($role)) {
                return true;
            }
        }

        return false;
    }

    public function lojas()
    {
        return $this->belongsToMany(Loja::class, 'users_lojas', 'user_id', 'loja_id');
    }

    public function cupons()
    {
        return $this->hasMany(Cupom::class, 'user_id', 'id');
    }

    public function comunicados()
    {
        return Comunicado::with('destinatarios')->whereHas('destinatarios', function ($q) {
            $q->whereUserId($this->id);
        });
    }

    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'user_id', 'id');
    }

    public function notificacoesComunicado($status = false)
    {
        return $this->notificacoes()->where('tipo', Notificacao::TIPO_COMUNICADO)->where('status', $status);
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class, 'destinatario', 'id');
    }

    public function notificacoesSolicitacao($status = false)
    {
        return $this->notificacoes()->where('tipo', Notificacao::TIPO_SOLICITACAO)->where('status', $status);
    }

    public function notificacoesMensagens()
    {
        return $this->mensagensRecebidas()->whereNull('read_in')->where('folder', Mensagem::FOLDER_SENT);
    }

    public function mensagensRecebidas()
    {
        return $this->hasMany(Mensagem::class, 'to_id')->whereFolder(Mensagem::FOLDER_SENT);
    }

    public function videosNaoAssistidos()
    {
        return Video::orderby('created_at', 'desc')->whereNotIn('id', $this->videos()->lists('id')->toArray())->get();
    }

    public function videos()
    {
        return Video::select('videos.*')->join('video_assisido', 'video_assisido.video_id', '=', 'videos.id')->where('video_assisido.user_id', $this->id);
    }

    public function enquetes()
    {
        return $this->belongsToMany(Enquete::class, 'enquetes_destinatarios', 'user_id', 'enquete_id')->orderBy('enquete_id', 'desc');
    }

    public function enquetesRespondidas()
    {
        return UsuarioResposta::select(DB::raw('distinct enquete_id'))->where('user_id', $this->id)->get()->pluck('enquete_id')->toArray();
    }

    public function enqueteRespostas($enquete_id)
    {
        return UsuarioResposta::where('enquete_id', $enquete_id)->where('user_id', $this->id);
    }

    public function setores()
    {
        return $this->belongsToMany(Setor::class, 'setor_responsaveis', 'user_id', 'setor_id');
    }

    public function mensagensEnviadas()
    {
        return $this->hasMany(Mensagem::class, 'from_id');
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }

    public function visitas_pendentes()
    {
        return $this->visitas()->whereStatus(Visita::STATUS_INICIADA);
    }

    /**
     * RELATORIOS.
     */
    public static function relatorioAproveitamentoComunicados()
    {
        $users = self::ativo()->get()->each(function ($user) {
            $user->comunicados_lidos = \App\Models\ComunicadoDestinatarios::whereUserId($user->id)->whereStatus(true)->count();
            $user->comunicados_nao_lidos = \App\Models\ComunicadoDestinatarios::whereUserId($user->id)->whereStatus(false)->count();
            $user->total = $user->comunicados_lidos + $user->comunicados_nao_lidos;
            $total = $user->comunicados_lidos + $user->comunicados_nao_lidos;
            if ($total == 0) {
                $user->porcentagem = 0;
            } else {
                $user->porcentagem = ($user->comunicados_lidos / $total) * 100;
            }
        });

        return $users->sortByDesc('total')->sortByDesc('porcentagem');
    }
}
