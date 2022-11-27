<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pasta.
 *
 * @property int $id
 * @property string $nome
 * @property int $setor_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Arquivo[] $arquivos
 * @property-read mixed $setor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pasta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pasta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pasta whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pasta whereSetorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pasta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pasta extends Model
{
    const SETOR_MARKETING = 1;
    const SETOR_OPERACIONAL = 2;
    const SETOR_MANUAIS = 3;

    public static $setores = [
        self::SETOR_MARKETING => 'Marketing',
        self::SETOR_OPERACIONAL => 'Operacional',
        self::SETOR_MANUAIS => 'Manuais',
    ];

    protected $fillable = ['nome', 'setor_id'];

    public function getSetorAttribute()
    {
        return self::$setores[$this->setor_id];
    }

    public function arquivos()
    {
        return $this->hasMany(Arquivo::class, 'pasta_id', 'id');
    }
}
