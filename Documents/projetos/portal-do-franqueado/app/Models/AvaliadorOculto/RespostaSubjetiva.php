<?php

namespace App\Models\AvaliadorOculto;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AvaliadorOculto\RespostaSubjetiva
 *
 * @property-read mixed $resposta_formatted
 * @property-read \App\Models\AvaliadorOculto\Pergunta $pergunta
 * @mixin \Eloquent
 */
class RespostaSubjetiva extends Model
{
    protected $table = 'avaliador_oculto_respostas_subjetivas';
    public $fillable = ['resposta', 'pergunta_id', 'user_id', 'loja_id'];

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class, 'pergunta_id', 'id');
    }

    public function getRespostaFormattedAttribute()
    {
        return $this->resposta;
    }
}
