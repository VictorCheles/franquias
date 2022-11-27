<?php

namespace App\ACL;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ACL\Grupo.
 *
 * @property int $id
 * @property string $nome
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ACL\Recurso[] $recursos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ACL\Grupo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ACL\Grupo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ACL\Grupo whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ACL\Grupo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Grupo extends Model
{
    protected $fillable = ['nome'];

    public function recursos()
    {
        return $this->belongsToMany(Recurso::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'grupo_id', 'id');
    }
}
