<?php

namespace App\Models\ConsultoriaCampo;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConsultoriaCampo\Pergunta.
 *
 * @property int $id
 * @property string $pergunta
 * @property int $pontuacao
 * @property int $topico_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\AcaoCorretiva[] $acoes_corretivas
 * @property-read \App\Models\ConsultoriaCampo\Topico $topico
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Pergunta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Pergunta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Pergunta wherePergunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Pergunta wherePontuacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Pergunta whereTopicoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Pergunta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pergunta extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Pergunta $model) {
            $model->acoes_corretivas()->delete();
        });
    }

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
    protected $table = 'perguntas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topico_id', 'pergunta', 'pontuacao',
    ];

    public function topico()
    {
        return $this->belongsTo(Topico::class);
    }

    public function acoes_corretivas()
    {
        return $this->hasMany(AcaoCorretiva::class);
    }
}
