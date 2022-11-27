<?php

namespace App\Models\Localidade;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Localidade\Regiao.
 *
 * @property int $id
 * @property string $nome
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Localidade\Estado[] $estados
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Regiao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Regiao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Regiao whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Localidade\Regiao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Regiao extends Model
{
    protected $table = 'regioes';
    protected $fillable = ['nome'];

    public function estados()
    {
        return $this->hasMany(Estado::class);
    }
}
