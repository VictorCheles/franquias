<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resposta.
 *
 * @property int $id
 * @property string $resposta
 * @property int $pergunta_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $porcentagem_de_votos
 * @property-read mixed $total_de_votos
 * @property-read \App\Models\Pergunta $pergunta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resposta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resposta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resposta wherePerguntaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resposta whereResposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resposta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Resposta extends Model
{
    protected $fillable = ['resposta', 'pergunta_id'];

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class, 'pergunta_id', 'id');
    }

    public function getTotalDeVotosAttribute()
    {
        return $this->pergunta->respostasUsuarios()->where('resposta_id', $this->id)->count();
    }

    public function getPorcentagemDeVotosAttribute()
    {
        $total = $this->pergunta->respostasUsuarios()->count();
        $votos = $this->total_de_votos;
        if ($total == 0) {
            return 0;
        }

        return number_format(($votos * 100) / $total, 2, ',', '.');
    }
}
