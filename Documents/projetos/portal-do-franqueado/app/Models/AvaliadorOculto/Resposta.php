<?php

namespace App\Models\AvaliadorOculto;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AvaliadorOculto\Resposta.
 *
 * @property int $id
 * @property string $resposta
 * @property int $pergunta_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $resposta_formatted
 * @property-read \App\Models\AvaliadorOculto\Pergunta $pergunta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Resposta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Resposta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Resposta wherePerguntaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Resposta whereResposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Resposta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Resposta extends Model
{
    const SIM = 1;
    const NAO = 2;

    const MAP_RESPOSTAS = [
        self::SIM => 'Sim',
        self::NAO => 'Não',
    ];

    const MAP_RESPOSTAS_FORMATTED = [
        self::SIM => '<span class="label label-success">Sim</span>',
        self::NAO => '<span class="label label-danger">Não</span>',
    ];

    protected $table = 'avaliador_oculto_respostas';
    public $fillable = ['resposta', 'pergunta_id', 'user_id', 'loja_id', 'fotos'];

    protected $casts = [
        'fotos' => 'json',
    ];

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class, 'pergunta_id', 'id');
    }

    public function getRespostaFormattedAttribute()
    {
        return self::MAP_RESPOSTAS_FORMATTED[$this->resposta];
    }
}
