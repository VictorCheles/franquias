<?php

namespace App\Models\Metas;

use Carbon\Carbon;
use App\Models\Loja;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Metas\Meta.
 *
 * @property int $id
 * @property string $titulo
 * @property \Carbon\Carbon $inicio
 * @property \Carbon\Carbon $fim
 * @property float $valor
 * @property int $metrica
 * @property int $loja_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Metas\Atividade[] $atividades
 * @property mixed $periodo
 * @property-read \App\Models\Loja $loja
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta dentroDoPeriodo()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereFim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereLojaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereMetrica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Meta whereValor($value)
 * @mixin \Eloquent
 */
class Meta extends Model
{
    //TODO: Implementar as métricas corretas
    const METRICA_DINHEIRO = 1;
    const METRICA_UNIDADE = 2;

    public static $mapMetricas = [
        self::METRICA_DINHEIRO => 'Dinheiro (R$)',
        self::METRICA_UNIDADE => 'Unidades',
    ];

    /**
     * The schema associated with the model.
     *
     * @var string
     */
    protected $connection = 'metas';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'metas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo', 'inicio', 'fim',
        'valor', 'metrica', 'loja_id',
    ];
    /**
     * The attributes that should be casted as dates.
     *
     * @var array
     */
    protected $dates = [
        'inicio', 'fim',
    ];

    protected $appends = ['periodo'];

    public function getPeriodoAttribute()
    {
        return $this->inicio->format('d/m/Y') . ' - ' . $this->fim->format('d/m/Y');
    }

    public function setInicioAttribute($value)
    {
        $this->attributes['inicio'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setFimAttribute($value)
    {
        $this->attributes['fim'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setPeriodoAttribute($value)
    {
        list($first, $last) = explode(' - ', $value);
        $this->inicio = $first;
        $this->fim = $last;
    }

    public function scopeDentroDoPeriodo($q)
    {
        return $q->where('inicio', '<=', Carbon::now()->format('Y-m-d'))
            ->where('fim', '>=', Carbon::now()->format('Y-m-d'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function atividades()
    {
        return $this->hasMany(Atividade::class);
    }
}
