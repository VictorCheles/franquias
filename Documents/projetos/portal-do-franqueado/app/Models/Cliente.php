<?php

namespace App\Models;

use App\Models\Localidade\Municipio;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cliente.
 *
 * @property string $nome
 * @property string $email
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $municipio_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cupom[] $cupons
 * @property-read \App\Models\Localidade\Municipio|null $municipio
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cliente whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cliente whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cliente whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cliente whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cliente extends Model
{
    protected $primaryKey = 'email';
    public $incrementing = false;

    protected $fillable = [
        'nome',
        'email',
        'municipio_id',
    ];

    public function cupons()
    {
        return $this->hasMany(Cupom::class, 'cliente_email', 'email');
    }

    public function cupons_usados()
    {
        return $this->cupons()->whereStatus(Cupom::STATUS_USADO_BOOLEAN);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }
}
