<?php

namespace App\Models\ConsultoriaCampo;

use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    /**
     * The schema associated with the model.
     *
     * @var string
     */
    protected $connection = 'consultoria_campo';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'respostas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visita_id', 'resposta', 'pontuacao',
        'pergunta_id', 'fotos',
    ];
    /**
     * The attributes that should be casted as dates.
     *
     * @var array
     */
    protected $dates = [
        'data_correcao',
    ];

    protected $casts = [
        'resposta' => 'boolean',
        'fotos' => 'json',
    ];

    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }

    public function getRespostaFormattedAttribute()
    {
        if (is_null($this->resposta)) {
            return '<span class="label label-info">Não Avaliado</span>';
        }

        return $this->resposta ? '<span class="label label-success">Sim</span>' : '<span class="label label-danger">Não</span>';
    }
}
