<?php

namespace App\Models\Localidade;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Localidade\Municipio.
 *
 * @property int $id
 * @property string $nome
 * @property int $estado_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Localidade\Estado $estado
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Municipio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Municipio whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Municipio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Municipio whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Municipio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Municipio extends Model
{
    protected $fillable = ['nome', 'estado_id'];

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}
