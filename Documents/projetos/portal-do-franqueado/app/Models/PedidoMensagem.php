<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PedidoMensagem.
 *
 * @property int $id
 * @property string $mensagem
 * @property int $pedido_id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Pedido $pedido
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PedidoMensagem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PedidoMensagem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PedidoMensagem whereMensagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PedidoMensagem wherePedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PedidoMensagem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PedidoMensagem whereUserId($value)
 * @mixin \Eloquent
 */
class PedidoMensagem extends Model
{
    protected $table = 'pedido_mensagens';
    protected $fillable = ['mensagem', 'pedido_id', 'user_id'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
