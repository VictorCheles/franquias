<?php

namespace App\Models\Localidade;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Localidade\Estado.
 *
 * @property int $id
 * @property string $nome
 * @property string $sigla
 * @property int $regiao_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Localidade\Municipio[] $municipios
 * @property-read \App\Models\Localidade\Regiao $regiao
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Estado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Estado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Estado whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Estado whereRegiaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Estado whereSigla($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Estado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Estado extends Model
{
    protected $fillable = ['nome', 'sigla', 'regiao_id'];

    public function regiao()
    {
        return $this->belongsTo(Regiao::class);
    }

    public function municipios()
    {
        return $this->hasMany(Municipio::class);
    }
}
