<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ComunicadoDestinatarios.
 *
 * @property int $id
 * @property int $user_id
 * @property int $comunicado_id
 * @property bool $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Comunicado $comunicado
 * @property-read mixed $status_formatted
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ComunicadoDestinatarios whereComunicadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ComunicadoDestinatarios whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ComunicadoDestinatarios whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ComunicadoDestinatarios whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ComunicadoDestinatarios whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ComunicadoDestinatarios whereUserId($value)
 * @mixin \Eloquent
 */
class ComunicadoDestinatarios extends Model
{
    protected $fillable = ['user_id', 'comunicado_id', 'status'];

    public static $mapStatusStyled = [
        true => '<span class="label label-success">Sim</span>',
        false => '<span class="label label-danger">Não</span>',
    ];

    public function getStatusFormattedAttribute()
    {
        return self::$mapStatusStyled[$this->status];
    }

    public function comunicado()
    {
        return $this->belongsTo(Comunicado::class, 'comunicado_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
