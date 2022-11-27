<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UsuarioResposta.
 *
 * @property int $id
 * @property int $user_id
 * @property int $enquete_id
 * @property int $pergunta_id
 * @property int $resposta_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Pergunta $pergunta
 * @property-read \App\Models\Resposta $resposta
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioResposta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioResposta whereEnqueteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioResposta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioResposta wherePerguntaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioResposta whereRespostaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioResposta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioResposta whereUserId($value)
 * @mixin \Eloquent
 */
class UsuarioResposta extends Model
{
    protected $fillable = ['user_id', 'pergunta_id', 'resposta_id', 'enquete_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function pergunta()
    {
        return $this->hasOne(Pergunta::class, 'id', 'pergunta_id');
    }

    public function resposta()
    {
        return $this->hasOne(Resposta::class, 'id', 'resposta_id');
    }
}
