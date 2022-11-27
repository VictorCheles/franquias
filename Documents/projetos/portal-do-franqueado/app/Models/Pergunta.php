<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pergunta.
 *
 * @property int $id
 * @property string $pergunta
 * @property int $enquete_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Enquete $enquete
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resposta[] $respostas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UsuarioResposta[] $respostasUsuarios
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pergunta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pergunta whereEnqueteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pergunta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pergunta wherePergunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pergunta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pergunta extends Model
{
    protected $fillable = ['pergunta', 'enquete_id'];

    public function enquete()
    {
        return $this->belongsTo(Enquete::class, 'enquente_id', 'id');
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class, 'pergunta_id', 'id');
    }

    public function respostasUsuarios()
    {
        return $this->hasMany(UsuarioResposta::class, 'pergunta_id', 'id');
    }
}
