<?php

namespace App\Models\ConsultoriaCampo;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConsultoriaCampo\Notificacao.
 *
 * @property int $id
 * @property string $descricao
 * @property float $valor_un
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $nome_valor_formatted
 * @property-read mixed $valor_formatted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Notificacao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Notificacao whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Notificacao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Notificacao whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Notificacao whereValorUn($value)
 * @mixin \Eloquent
 */
class Notificacao extends Model
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
    protected $table = 'notificacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descricao', 'valor_un',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'valor_formatted',
        'nome_valor_formatted',
    ];

    public function getValorFormattedAttribute($value)
    {
        return maskMoney($this->attributes['valor_un']);
    }

    public function getNomeValorFormattedAttribute()
    {
        return $this->attributes['descricao'] . ' | ' . $this->valor_formatted;
    }
}
