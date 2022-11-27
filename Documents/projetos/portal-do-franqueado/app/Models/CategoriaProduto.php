<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CategoriaProduto
 *
 * @property int $id
 * @property string $nome
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property bool $disponivel
 * @property-read mixed $disponivel_formatted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoriaProduto disponivel()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoriaProduto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoriaProduto whereDisponivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoriaProduto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoriaProduto whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoriaProduto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoriaProduto extends Model
{
    protected $fillable = ['nome', 'disponivel'];

    public static $mapDisponibilidade = [
        true => 'Sim',
        false => 'Não',
    ];

    public static $mapDisponibilidadeFormatted = [
        true => '<span class="label label-success">Sim</span>',
        false => '<span class="label label-danger">Não</span>',
    ];

    public function scopeDisponivel($q)
    {
        $q->whereDisponivel(true);
    }

    public function getDisponivelFormattedAttribute()
    {
        return self::$mapDisponibilidadeFormatted[$this->disponivel];
    }
}
