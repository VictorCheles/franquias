<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ClienteLojaEstabelecimento.
 *
 * @property int $id
 * @property string $nome
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClienteLoja[] $clientes
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLojaEstabelecimento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLojaEstabelecimento whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLojaEstabelecimento whereUserId($value)
 * @mixin \Eloquent
 */
class ClienteLojaEstabelecimento extends Model
{
    public $table = 'clientes_loja_estabelecimentos';
    public $timestamps = false;
    public $fillable = ['nome', 'user_id'];

    /**
     * quem cadastrou o estabelecimento.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function clientes()
    {
        return $this->hasMany(ClienteLoja::class, 'estabelecimento_id', 'id');
    }
}
