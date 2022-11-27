<?php

namespace App\Models\ConsultoriaCampo;

use App\User;
use App\Models\Loja;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConsultoriaCampo\Visita.
 *
 * @property int $id
 * @property int $formulario_id
 * @property int $loja_id
 * @property int $user_id
 * @property \Carbon\Carbon $data
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $relato_final
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\AcaoCorretiva[] $acoes_corretivas
 * @property-read \App\Models\ConsultoriaCampo\Formulario $formulario
 * @property-read mixed $score
 * @property-read mixed $score_formatted
 * @property-read mixed $status_formatted
 * @property-read \App\Models\Loja $loja
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\Notificacao[] $notificacoes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\Resposta[] $respostas
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereFormularioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereLojaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereRelatoFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Visita whereUserId($value)
 * @mixin \Eloquent
 */
class Visita extends Model
{
    const STATUS_AGENDADA = 1;
    const STATUS_INICIADA = 2;
    const STATUS_FINALIZADA = 3;
    const STATUS_VALIDADA = 4;

    public static $mapStatus = [
        self::STATUS_AGENDADA => 'Agendada',
        self::STATUS_INICIADA => 'Em andamento / Interrompida',
        self::STATUS_FINALIZADA => 'Finalizada',
        self::STATUS_VALIDADA => 'Validada',
    ];

    public static $mapStatusFormatted = [
        self::STATUS_AGENDADA => 'Agendada',
        self::STATUS_INICIADA => '<span class="label label-info">Em andamento / Interrompida</span>',
        self::STATUS_FINALIZADA => '<span class="label label-warning">Finalizada</span>',
        self::STATUS_VALIDADA => '<span class="label label-success">Validada</span>',
    ];

    /**
     * The schema associated with the model.
     *
     * @var string
     */
    protected $connection = 'consultoria_campo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'formulario_id', 'loja_id', 'user_id',
        'data', 'status', 'relato_final',
    ];

    /**
     * The attributes that should be casted as dates.
     *
     * @var array
     */
    protected $dates = [
        'data',
    ];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }

    public function respostasPorTopico($topico_id)
    {
        return isset($this->respostas->groupBy('pergunta.topico_id')[$topico_id]) ? $this->respostas->groupBy('pergunta.topico_id')[$topico_id] : null;
    }

    public function pontuacaoPorTopico($topico_id)
    {
        $pontuacao = 0;
        $respostas = $this->respostasPorTopico($topico_id);
        if (! is_null($respostas)) {
            $respostas->each(function ($q) use (&$pontuacao) {
                if ($q->resposta) {
                    $pontuacao++;
                }
            });
        }

        return $pontuacao;
    }

    public function porcentagemPorTopico($topico_id)
    {
        if ($this->respostasPorTopico($topico_id)) {
            return ($this->pontuacaoPorTopico($topico_id) / $this->respostasPorTopico($topico_id)->count()) * 100;
        } else {
            return 0;
        }
    }

    public function respostaPergunta($pergunta)
    {
        return $this->respostas->filter(function ($resposta) use ($pergunta) {
            return $resposta->pergunta_id == $pergunta->id;
        })->first();
    }

    public function notificacoes()
    {
        return $this->belongsToMany(Notificacao::class)->withPivot('quantidade', 'valor_un');
    }

    public function acoes_corretivas()
    {
        return $this->hasMany(AcaoCorretiva::class);
    }

    public function getScoreAttribute()
    {
        $pontuacao = $this->respostas->filter(function ($r) {
            return $r->resposta == true;
        })->count();
        $total = $this->respostas->count();

        return $total > 0 ? ($pontuacao / $total) * 100 : 0;
    }

    public function getScoreFormattedAttribute()
    {
        $label = 'label-';
        if ($this->score <= 30) {
            $label .= 'danger';
        } elseif ($this->score > 30 && $this->score < 70) {
            $label .= 'warning';
        } else {
            $label .= 'success';
        }

        return '<span class="label '. $label .'">' . number_format($this->score, 2, ',', '.')  . '%' . '</span>';
    }

    public function getStatusFormattedAttribute()
    {
        return self::$mapStatusFormatted[$this->status];
    }
}
