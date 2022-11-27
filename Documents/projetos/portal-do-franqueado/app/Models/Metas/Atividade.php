<?php

namespace App\Models\Metas;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Metas\Atividade.
 *
 * @property int $id
 * @property string|null $descricao
 * @property float $valor
 * @property int $meta_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Metas\Meta $meta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Atividade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Atividade whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Atividade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Atividade whereMetaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Atividade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Metas\Atividade whereValor($value)
 * @mixin \Eloquent
 */
class Atividade extends Model
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
    protected $table = 'atividades';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descricao', 'valor', 'meta_id',
    ];

    protected $casts = [
        'valor' => 'float',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meta()
    {
        return $this->belongsTo(Meta::class);
    }
}
