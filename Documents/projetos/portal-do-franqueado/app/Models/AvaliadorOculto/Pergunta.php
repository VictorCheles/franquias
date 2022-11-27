<?php

namespace App\Models\AvaliadorOculto;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AvaliadorOculto\Pergunta
 *
 * @property int $id
 * @property string $pergunta
 * @property int $enquete_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\AvaliadorOculto\Formulario $formulario
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Pergunta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Pergunta whereEnqueteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Pergunta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Pergunta wherePergunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\Pergunta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pergunta extends Model
{
    const TIPO_SIM_NAO = 1;
    const TIPO_SUBJETIVA = 2;

    const MAPA_TIPOS = [
        self::TIPO_SIM_NAO => 'Sim / Não',
        self::TIPO_SUBJETIVA => 'Subjetiva',
    ];

    const MAPA_MODEL_RESPOSTA = [
        self::TIPO_SIM_NAO => Resposta::class,
        self::TIPO_SUBJETIVA => RespostaSubjetiva::class,
    ];

    protected $table = 'avaliador_oculto_perguntas';
    public $fillable = ['pergunta', 'formulario_id', 'tipo', 'peso', 'peso_negativo'];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class, 'formulario_id', 'id');
    }

    /**
     * @param int $loja_id
     * @param int|null $user_id
     * @return mixed
     */
    public function resposta($loja_id, $user_id = null)
    {
        $query = $this->hasMany(self::MAPA_MODEL_RESPOSTA[$this->tipo], 'pergunta_id', 'id')->where('loja_id', $loja_id);
        if (! is_null($user_id)) {
            $query->where('user_id', $user_id);
        }

        return $query;
    }
}
