<?php

namespace App\Models;

use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * App\Models\Cupom
 *
 * @property int $id
 * @property string|null $code
 * @property string $cliente_email
 * @property int $promocao_id
 * @property bool $status
 * @property int|null $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $validade_cupom
 * @property string|null $codigo_original
 * @property int|null $loja_id
 * @property-read \App\Models\Cliente $cliente
 * @property-read mixed $status_formatted
 * @property-read \App\Models\Loja|null $loja
 * @property-read \App\Models\Promocao $promocao
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereClienteEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereCodigoOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereLojaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom wherePromocaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cupom whereValidadeCupom($value)
 * @mixin \Eloquent
 */
class Cupom extends Model
{
    const STATUS_VALIDO = 1;
    const STATUS_USADO = 2;
    const STATUS_VENCIDO = 3;

    const STATUS_USADO_BOOLEAN = false;

    public static $mapsStatus = [
        self::STATUS_VALIDO => 'Válido',
        self::STATUS_USADO => 'Usado',
        self::STATUS_VENCIDO => 'Vencido',
    ];
    public static $mapsStatusFormatted = [
        self::STATUS_VALIDO => '<span class="label label-success">Válido</span>',
        self::STATUS_USADO => '<span class="label label-default">Usado</span>',
        self::STATUS_VENCIDO => '<span class="label label-danger">Vencido</span>',
    ];

    protected $table = 'cupons';
    protected $fillable = [
        'id',
        'code',
        'cliente_email',
        'validade_cupom',
        'status',
        'user_id',
        'loja_id',
        'promocao_id',
        'codigo_original',
    ];
    protected $dates = ['validade_cupom'];
    public $incrementing = false;

    public function getStatusFormattedAttribute()
    {
        return self::$mapsStatusFormatted[$this->status()];
    }

    public static function findByCode($code)
    {
        $cupom = self::where('code', '=', $code)->get();
        if ($cupom->count() > 0) {
            return $cupom->first();
        } else {
            return false;
        }
    }

    public static function findOrFailByCode($code)
    {
        $cupom = self::where('code', '=', $code)->get();
        if ($cupom->count() > 0) {
            return $cupom->first();
        } else {
            $ex = new ModelNotFoundException();
            $ex->setModel(self::class);
            throw $ex;
        }
    }

    public function status()
    {
        $validade = Carbon::parse(date('Y-m-d', strtotime($this->validade_cupom)));
        if ($this->status and Carbon::now()->gt($validade)) {
            return self::STATUS_VENCIDO;
        } elseif (! $this->status) {
            return self::STATUS_USADO;
        } else {
            return self::STATUS_VALIDO;
        }
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_email', 'email');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function promocao()
    {
        return $this->belongsTo(Promocao::class, 'promocao_id', 'id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id', 'id');
    }

    /**
     * @param Carbon $inicio
     * @param Carbon $fim
     * @return array
     */
    public static function porSemanaPorStatus(Carbon $inicio, Carbon $fim)
    {
        if ($inicio->gt($fim)) {
            list($inicio, $fim) = [$fim, $inicio];
        }
        $cupons = self::
            where(DB::raw('date(updated_at)'), '>=', $inicio->format('Y-m-d'))->
            where(DB::raw('date(updated_at)'), '<=', $fim->format('Y-m-d'))->get();
        $porStatus = [
            'total' => 0,
            self::STATUS_VALIDO => 0,
            self::STATUS_USADO => 0,
            self::STATUS_VENCIDO => 0,
        ];
        $cupons->each(function ($item, $key) use (&$porStatus) {
            $porStatus['total']++;
            $porStatus[$item->status()]++;
        });

        return $porStatus;
    }

    public static function cuponsPorDiaCaixa(Request $request, $forceToday = false)
    {
        $result = self::select(DB::raw('date(cupons.updated_at) as data, promocoes.nome as promocao, count(cupons.id) as total'))
            ->join('promocoes', 'cupons.promocao_id', '=', 'promocoes.id')
            ->groupBy(DB::raw('data'))
            ->groupBy(DB::raw('promocao'))
            ->orderBy(DB::raw('data'), 'desc')
            ->orderBy(DB::raw('total'), 'desc')
            ->whereIn('loja_id', Auth()->user()->lojas->pluck('id')->toArray())
            ->where('cupons.status', self::STATUS_USADO_BOOLEAN);
        if ($forceToday) {
            $result->where(DB::raw('date(cupons.updated_at)'), Carbon::now()->format('Y-m-d'));
        } elseif ($request->input('filter.data')) {
            $datas = $request->input('filter.data');
            $datas = array_filter($datas, function ($v) {
                return $v !== '';
            });
            if (count($datas) > 1) {
                $inicio = Carbon::parse(reset($datas));
                $fim = Carbon::parse(end($datas));
                if ($inicio->gt($fim)) {
                    list($inicio, $fim) = [$fim, $inicio];
                }
                $result->where(DB::raw('date(cupons.updated_at)'), '>=', $inicio->__toString());
                $result->where(DB::raw('date(cupons.updated_at)'), '<=', $fim->__toString());
            } else {
                $data = Carbon::parse(reset($datas));
                $result->where(DB::raw('date(cupons.updated_at)'), $data->__toString());
            }
        }

        return $result->get();
    }

    public static function cuponsPorDiaPorLoja(Request $request, $forceToday = false)
    {
        $result = self::select(DB::raw('date(cupons.updated_at) as data, lojas.nome as franquia ,promocoes.nome as promocao, count(cupons.id) as total'))
            ->join('promocoes', 'cupons.promocao_id', '=', 'promocoes.id')
            //->join('users', 'cupons.user_id', '=', 'users.id')
            ->join('lojas', 'cupons.loja_id', '=', 'lojas.id')
            ->groupBy(DB::raw('data'))
            ->groupBy(DB::raw('promocao'))
            ->groupBy(DB::raw('franquia'))
            ->orderBy(DB::raw('data'), 'desc')
            ->orderBy(DB::raw('total'), 'desc');
        if ($forceToday) {
            $result->where(DB::raw('date(cupons.updated_at)'), Carbon::now()->format('Y-m-d'));
        } elseif ($request->input('filter.data')) {
            $datas = $request->input('filter.data');
            list($inicio, $fim) = explode(' - ', $datas);
            $inicio = Carbon::createFromFormat('d/m/Y', $inicio);
            $fim = Carbon::createFromFormat('d/m/Y', $fim);
            $result->where(DB::raw('date(cupons.updated_at)'), '>=', $inicio->__toString());
            $result->where(DB::raw('date(cupons.updated_at)'), '<=', $fim->__toString());
        }

        if ($request->input('filter.franquia')) {
            $result->where('lojas.id', $request->input('filter.franquia'));
        }

        return $result->get();
    }
}
