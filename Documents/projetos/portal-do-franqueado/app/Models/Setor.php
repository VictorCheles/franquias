<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Setor.
 *
 * @property int $id
 * @property string $nome
 * @property string $tag
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property bool $interno
 * @property-read mixed $interno_formatted
 * @property-read mixed $tag_formatted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $responsaveis
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Solicitacao[] $solicitacoes
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setor publico()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setor whereInterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setor whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setor whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Setor extends Model
{
    protected $table = 'setores';
    protected $fillable = [
        'nome', 'tag', 'interno',
    ];

    public static $mapInterno = [
        false => 'Não',
        true => 'Sim',
    ];

    public static $mapInternoFormatted = [
        false => '<span class="label label-danger">Não</span>',
        true => '<span class="label label-success">Sim</span>',
    ];

    public function scopePublico($q)
    {
        return $q->whereInterno(false);
    }

    public function getTagFormattedAttribute()
    {
        return '#' . mb_strtoupper($this->tag);
    }

    public function getInternoFormattedAttribute()
    {
        return self::$mapInternoFormatted[$this->interno];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'responsavel');
    }

    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'setor_id', 'id');
    }

    public function responsaveis()
    {
        return $this->belongsToMany(User::class, 'setor_responsaveis', 'setor_id', 'user_id')->ativo();
    }
}
