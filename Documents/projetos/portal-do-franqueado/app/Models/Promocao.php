<?php

namespace App\Models;

use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Promocao.
 *
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property string $regulamento
 * @property string $texto_mobile
 * @property \Carbon\Carbon $inicio
 * @property \Carbon\Carbon $fim
 * @property int $ordem
 * @property int $max_cupons_usados
 * @property int $cupons_usados
 * @property int $cupons_criados
 * @property int $user_id
 * @property int|null $modificador_por
 * @property string $imagem
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $validade_cupom
 * @property int $forcar_termino
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cupom[] $cupons
 * @property-read mixed $cupons_usados_porcentagem
 * @property-read mixed $cupons_usados_porcentagem_formatted
 * @property-read mixed $cupons_vencidos
 * @property-read mixed $max_cupons_usados_formatted
 * @property-read mixed $status
 * @property-read mixed $status_formatted
 * @property-read mixed $texto_validade
 * @property-read mixed $validade_do_cupom
 * @property-read \App\User|null $modificadorPor
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereCuponsCriados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereCuponsUsados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereFim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereForcarTermino($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereMaxCuponsUsados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereModificadorPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereRegulamento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereTextoMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Promocao whereValidadeCupom($value)
 * @mixin \Eloquent
 */
class Promocao extends Model
{
    protected $table = 'promocoes';
    protected $fillable = [
        'nome',
        'descricao',
        'regulamento',
        'inicio',
        'fim',
        'ordem',
        'validade_cupom',
        'max_cupons_usados',
        'cupons_usados',
        'user_id',
        'texto_mobile',
        'modificador_por',
        'cupons_criados',
        'imagem',
        'forcar_termino',
        'url_externa',
    ];
    protected $dates = [
        'inicio',
        'fim',
    ];

    public static $map_forcar_termino = [
        false => 'Não',
        true => 'Sim',
    ];

    protected $casts = [
        'forcar_termino' => 'integer',
    ];

    public static function regrasValidacaoCriar()
    {
        return [
            'nome' => 'required|max:255',
            'descricao' => 'required',
            'regulamento' => 'required',
            'inicio' => 'required|date|before:fim',
            'fim' => 'required|date',
            'validade_cupom' => 'required|integer',
            'texto_mobile' => 'required|max:255',
            'max_cupons_usados' => 'required|integer',
            'ordem' => 'required|integer',
            'imagem' => 'required|image',
            'forcar_termino' => 'required|boolean',
            'url_externa' => 'max:255'
        ];
    }

    public function getTextoValidadeAttribute()
    {
        return 'Promoção válida de ' . $this->inicio->format('d/m/Y') . ' à ' . $this->fim->format('d/m/Y');
    }

    public function getCuponsUsadosPorcentagemAttribute()
    {
        return $this->cupons_criados == 0 ? 0 : ($this->cupons_usados * 100) / $this->cupons_criados;
    }

    public function getCuponsUsadosPorcentagemFormattedAttribute()
    {
        return number_format($this->cupons_usados_porcentagem, 2, ',', '.') . '%';
    }

    public function getValidadeDoCupomAttribute()
    {
        if ($this->validade_cupom == 0) {
            $dias = Carbon::parse($this->fim->format('Y-m-d'))->diffInDays(Carbon::parse(Carbon::now()->format('Y-m-d')));

            return Carbon::now()->addDays($dias)->format('Y-m-d');
        } else {
            return Carbon::now()->addDays($this->validade_cupom)->format('Y-m-d');
        }
    }

    public function getStatusAttribute()
    {
        if ($this->forcar_termino) {
            return false;
        }

        if ($this->fim->lt(Carbon::now()) or $this->inicio->gt(Carbon::now())) {
            return false;
        } elseif ($this->max_cupons_usados == 0) {
            return true;
        } elseif ($this->cupons_usados != 0 and $this->cupons_usados >= $this->max_cupons_usados) {
            return false;
        } else {
            return true;
        }
    }

    public function getStatusFormattedAttribute()
    {
        return $this->getStatusAttribute() ? '<span class="label label-success">Ativa</span>' : '<span class="label label-danger">Inativa</span>';
    }

    public function getMaxCuponsUsadosFormattedAttribute()
    {
        if ($this->max_cupons_usados == 0) {
            return 'Infinitos';
        }

        return $this->max_cupons_usados;
    }

    public function getCuponsVencidosAttribute()
    {
        $vencidos = collect();
        $this->cupons()->get()->each(function ($item, $key) use ($vencidos) {
            if ($item->status() == Cupom::STATUS_VENCIDO) {
                $vencidos->push($item);
            }
        });

        return $vencidos;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function modificadorPor()
    {
        return $this->belongsTo(User::class, 'modificador_por', 'id');
    }

    public function cupons()
    {
        return $this->hasMany(Cupom::class, 'promocao_id', 'id');
    }

    public static function ativasPrimeiro()
    {
        $lista = self::orderBy('ordem', 'asc')
            ->where('inicio', '<=', date('Y-m-d'))
            ->where('forcar_termino', false)
            ->where('fim', '>', date('Y-m-d'))->get();
        $listaOk = collect();
        $listaFinalizada = collect();
        $lista->each(function ($item, $key) use ($listaOk, $listaFinalizada) {
            if ($item->status) {
                $listaOk->push($item);
            } else {
                $listaFinalizada->push($item);
            }
        });

        return $listaOk->merge($listaFinalizada);
    }

    public static function ativas()
    {
        $lista = self::orderBy('ordem', 'asc')->where('fim', '>', date('Y-m-d'))->get();
        $listaOk = collect();
        $lista->each(function ($item, $key) use ($listaOk) {
            if ($item->status) {
                $listaOk->push($item);
            }
        });

        return $listaOk;
    }

    public static function promocoesComMaisCupons(\Illuminate\Http\Request $request, Collection $promocoes)
    {
        $q = self::whereHas('cupons', function ($q) use ($request) {
            if ($periodo = $request->input('filter.periodo')) {
                list($inicio, $fim) = explode(' - ', $periodo);
                $inicio = Carbon::createFromFormat('d/m/Y', $inicio);
                $fim = Carbon::createFromFormat('d/m/Y', $fim);
                $q->where(DB::raw('date(created_at)'), '>=', $inicio);
                $q->where(DB::raw('date(created_at)'), '<=', $fim);
            }
        })->withCount('cupons')->orderBy('cupons_count', 'desc');

        if ($periodo = $request->input('filter.periodo')) {
            list($inicio, $fim) = explode(' - ', $periodo);
            $inicio = Carbon::createFromFormat('d/m/Y', $inicio);
            $fim = Carbon::createFromFormat('d/m/Y', $fim);
            $q->where(DB::raw('date(created_at)'), '>=', $inicio);
            $q->where(DB::raw('date(created_at)'), '<=', $fim);
        }

        if ($promocoes->count() > 0) {
            $q->whereIn('id', $promocoes);
        } else {
            $q->take(10);
        }

        $lista = $q->get();

        foreach ($lista as $p) {
            $cupons_array = [
                Cupom::STATUS_USADO => 0,
                Cupom::STATUS_VALIDO => 0,
                Cupom::STATUS_VENCIDO => 0,
                'total' => 0,
            ];
            foreach ($p->cupons()->get() as $cupom) {
                $cupons_array[$cupom->status()]++;
                $cupons_array['total']++;
            }

            $p->cupons_por_categoria = collect($cupons_array);
        }

        return $lista;
    }
}
