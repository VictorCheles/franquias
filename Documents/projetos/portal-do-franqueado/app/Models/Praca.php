<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Praca.
 *
 * @property int $id
 * @property string $nome
 * @property string $data_limite_pedido
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\EventoCalendario $evento_calendario
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Loja[] $lojas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Praca whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Praca whereDataLimitePedido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Praca whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Praca whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Praca whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Praca extends Model
{
    protected $fillable = ['nome', 'data_limite_pedido'];

    public function criarEventoCalendario()
    {
        EventoCalendario::create([
            'inicio' => Carbon::parse($this->data_limite_pedido)->format('d/m/Y'),
            'fim' => Carbon::parse($this->data_limite_pedido)->format('d/m/Y'),
            'titulo' => 'Data limite pedido praça ' . $this->nome,
            'relacao' => self::class,
            'relacao_id' => $this->id,
        ]);
    }

    public function lojas()
    {
        return $this->hasMany(Loja::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Loja::class);
    }

    public function evento_calendario()
    {
        return $this->hasOne(EventoCalendario::class, 'relacao_id', 'id');
    }
}
