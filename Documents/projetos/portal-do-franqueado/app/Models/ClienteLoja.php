<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ClienteLoja.
 *
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property string $telefone
 * @property int $estabelecimento_id
 * @property int $loja_id
 * @property int $user_id
 * @property-read \App\Models\ClienteLojaEstabelecimento $estabelecimento
 * @property-read \App\Models\Loja $loja
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLoja whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLoja whereEstabelecimentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLoja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLoja whereLojaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLoja whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLoja whereTelefone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClienteLoja whereUserId($value)
 * @mixin \Eloquent
 */
class ClienteLoja extends Model
{
    public $table = 'clientes_loja';
    public $timestamps = false;
    public $fillable = ['nome', 'email', 'telefone', 'loja_id', 'estabelecimento_id', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estabelecimento()
    {
        return $this->belongsTo(ClienteLojaEstabelecimento::class, 'estabelecimento_id', 'id');
    }

    /**
     * Quem cadastrou o cliente.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
