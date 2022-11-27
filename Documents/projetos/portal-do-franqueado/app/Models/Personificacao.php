<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Personificacao.
 *
 * @property int $id
 * @property int $ator_id
 * @property int $personagem_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $ator
 * @property-read \App\User $personagem
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Personificacao whereAtorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Personificacao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Personificacao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Personificacao wherePersonagemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Personificacao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Personificacao extends Model
{
    public $table = 'personificacoes';
    public $fillable = ['ator_id', 'personagem_id'];

    /**
     * Quem personificou.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ator()
    {
        return $this->belongsTo(User::class, 'ator_id', 'id');
    }

    /**
     * Quem foi personificado.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personagem()
    {
        return $this->belongsTo(User::class, 'personagem_id', 'id');
    }
}
