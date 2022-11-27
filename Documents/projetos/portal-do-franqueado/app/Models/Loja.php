<?php

namespace App\Models;

use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\AvaliadorOculto\Formulario;

/**
 * App\Models\Loja
 *
 * @property int $id
 * @property string $nome
 * @property string $cep
 * @property string $uf
 * @property string $cidade
 * @property string $bairro
 * @property string $endereco
 * @property string $numero
 * @property string $complemento
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property float|null $valor_minimo_pedido
 * @property int|null $praca_id
 * @property bool $fazer_pedido
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cupom[] $cupons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AvaliadorOculto\Formulario[] $formularios
 * @property-read mixed $data_limite
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pedido[] $pedidos
 * @property-read \App\Models\Praca|null $praca
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereBairro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereCep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereCidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereComplemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereEndereco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereFazerPedido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja wherePracaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereUf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Loja whereValorMinimoPedido($value)
 * @mixin \Eloquent
 */
class Loja extends Model
{
    protected $fillable = [
        'nome',
        'cep',
        'uf',
        'cidade',
        'bairro',
        'endereco',
        'numero',
        'complemento',
        'valor_minimo_pedido',
        'praca_id',
        'fazer_pedido',
    ];

    public static function regrasValidacaoCriar()
    {
        return [
            'nome' => 'required',
            'praca_id' => 'required|exists:pracas,id',
            'cep' => 'required',
            'uf' => 'required',
            'cidade' => 'required',
            'bairro' => 'required',
            'numero' => 'required',
            'endereco' => 'required',
            'valor_minimo_pedido' => 'required',
        ];
    }

    public function getDataLimiteAttribute()
    {
        return Carbon::parse($this->praca->data_limite_pedido);
    }

    public function praca()
    {
        return $this->belongsTo(Praca::class, 'praca_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_lojas', 'loja_id', 'user_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'loja_id', 'id');
    }

    public function cupons()
    {
        return $this->hasMany(Cupom::class);
    }

    public static function _anexarCuponsUsados(&$lojas, $inicio, $fim)
    {
        foreach ($lojas as $loja) {
            $loja->cupons_resgatados = $loja->cupons()->where(DB::raw('date(cupons.created_at)'), '>=', $inicio)->where(DB::raw('date(cupons.created_at)'), '<=', $fim)->get();
        }
    }

    /**
     * @param Carbon $inicio
     * @param Carbon $fim
     * @return mixed
     */
    public static function cuponsPorSemanaPorLoja(Carbon $inicio, Carbon $fim)
    {
        if ($inicio->gt($fim)) {
            list($inicio, $fim) = [$fim, $inicio];
        }

        $lojas = self::whereHas('cupons', function ($q) use ($inicio, $fim) {
            $q->where(DB::raw('date(cupons.created_at)'), '>=', $inicio->format('Y-m-d'))->where(DB::raw('date(cupons.created_at)'), '<=', $fim->format('Y-m-d'));
        })->get()->sortByDesc(function ($item) {
            return $item->cupons()->count();
        });
        self::_anexarCuponsUsados($lojas, $inicio, $fim);

        return $lojas;
    }

    public function pedidoDentroDoPrazo()
    {
        return Carbon::parse($this->praca->data_limite_pedido)->gt(Carbon::now()->addMinutes(15));
    }

    public function PedidoForaDoPrazo()
    {
        return ! $this->pedidoDentroDoPrazo();
    }

    public function formularios()
    {
        return $this->belongsToMany(Formulario::class, 'avaliador_oculto_lojas_formularios', 'loja_id', 'formulario_id');
    }

    public static function lojasComFormulariosAtivos()
    {
        return self::with(['formularios' => function ($q) {
            $q->where('status', Formulario::STATUS_ATIVO);
        }])->whereHas('formularios', function ($q) {
            $q->where('status', Formulario::STATUS_ATIVO);
        })->orderBy('nome')->get();
    }

    /**
     * @param Formulario $formulario
     * @param AvaliadorOculto\User $user
     */
    public function scorePorFormulario(Formulario $formulario, \App\Models\AvaliadorOculto\User $user = null)
    {
        $this->quem_respondeu = $formulario->users()->wherePivot('finalizou', true)->wherePivot('loja_id', $this->id)->get();
        $this->aproveitamento = 0;
        $this->pontuacao_total = 0;
        $this->perguntas = \App\Models\AvaliadorOculto\Pergunta::whereFormularioId($formulario->id)->orderBy('id')->get();
        $this->pontuacao_maxima = $this->perguntas->sum('peso') * $this->perguntas->first()->resposta($this->id)->where(function ($q) use ($user) {
            if (! is_null($user)) {
                $q->whereUserId($user->id);
            }
        })->count();
        $this->peso_total = $formulario->perguntas->sum('peso');
        $this->perguntas->each(function ($pergunta) use ($user) {
            $pergunta->porcentagem_pergunta = ($pergunta->peso / $this->peso_total) * 100;
            $pergunta->respostas = $pergunta->resposta($this->id)->where(function ($q) use ($pergunta, $user) {
                if (! is_null($user)) {
                    $q->whereUserId($user->id);
                }
            })->get();
            $pergunta->sim = 0;
            $pergunta->nao = 0;
            $pergunta->porcentagem_sim = 0;
            $pergunta->porcentagem_nao = 0;
            $pergunta->respostas->each(function ($resposta) use ($pergunta) {
                if (get_class($resposta) == \App\Models\AvaliadorOculto\Resposta::class) {
                    if ($resposta->resposta == \App\Models\AvaliadorOculto\Resposta::SIM) {
                        $pergunta->sim++;
                        $this->pontuacao_total += $pergunta->peso;
                    } else {
                        $pergunta->nao++;
                        $this->pontuacao_total -= $pergunta->peso_negativo;
                    }
                }
            });
            if ($pergunta->respostas->count() > 0) {
                $pergunta->porcentagem_sim = $pergunta->sim * 100 / $pergunta->respostas->count();
                $pergunta->porcentagem_nao = $pergunta->nao * 100 / $pergunta->respostas->count();
            }
        });

        if ($this->pontuacao_total > 0) {
            $this->aproveitamento = $this->pontuacao_total * 100 / $this->pontuacao_maxima;
        }
    }
}
