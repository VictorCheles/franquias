<?php

namespace App\Models\ConsultoriaCampo;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConsultoriaCampo\AcaoCorretiva.
 *
 * @property int $id
 * @property int $visita_id
 * @property string $descricao
 * @property \Carbon\Carbon $data_correcao
 * @property int $status
 * @property int $pergunta_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $status_formatted
 * @property-read \App\Models\ConsultoriaCampo\Pergunta $pergunta
 * @property-read \App\Models\ConsultoriaCampo\Visita $visita
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\AcaoCorretiva whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\AcaoCorretiva whereDataCorrecao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\AcaoCorretiva whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\AcaoCorretiva whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\AcaoCorretiva wherePerguntaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\AcaoCorretiva whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\AcaoCorretiva whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\AcaoCorretiva whereVisitaId($value)
 * @mixin \Eloquent
 */
class AcaoCorretiva extends Model
{
    const STATUS_PENDENTE = 1;
    const STATUS_EM_ANDAMENTO = 2;
    const STATUS_FINALIZADA = 3;

    public static $mapStatus = [
        self::STATUS_PENDENTE => 'Pendente',
        self::STATUS_EM_ANDAMENTO => 'Em andamento',
        self::STATUS_FINALIZADA => 'Finalizada',
    ];

    public static $mapStatusFormatted = [
        self::STATUS_PENDENTE => '<span class="label label-danger">Pendente</span>',
        self::STATUS_EM_ANDAMENTO => '<span class="label label-warning">Em andamento</span>',
        self::STATUS_FINALIZADA => '<span class="label label-success">Finalizada</span>',
    ];

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
    protected $table = 'acoes_corretivas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visita_id', 'descricao', 'data_correcao',
        'status', 'pergunta_id',
    ];
    /**
     * The attributes that should be casted as dates.
     *
     * @var array
     */
    protected $dates = [
        'data_correcao',
    ];

    public function getStatusFormattedAttribute()
    {
        return self::$mapStatusFormatted[$this->status];
    }

    public function visita()
    {
        return $this->belongsTo(Visita::class);
    }

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }
}
