<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SolicitacaoHistorico.
 *
 * @property int $id
 * @property string|null $observacoes
 * @property int $solicitacao_id
 * @property int $status_anterior
 * @property int $status_atual
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $status_anterior_formatted
 * @property-read mixed $status_atual_formatted
 * @property-read \App\Models\Solicitacao $solicitacao
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SolicitacaoHistorico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SolicitacaoHistorico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SolicitacaoHistorico whereObservacoes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SolicitacaoHistorico whereSolicitacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SolicitacaoHistorico whereStatusAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SolicitacaoHistorico whereStatusAtual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SolicitacaoHistorico whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SolicitacaoHistorico whereUserId($value)
 * @mixin \Eloquent
 */
class SolicitacaoHistorico extends Model
{
    protected $table = 'solicitacao_historico';
    protected $fillable = ['solicitacao_id', 'observacoes','status_anterior', 'status_atual', 'user_id'];

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacao::class, 'solicitacao_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getStatusAnteriorFormattedAttribute()
    {
        return Solicitacao::$mapStatusFormatted[$this->status_anterior];
    }

    public function getStatusAtualFormattedAttribute()
    {
        return Solicitacao::$mapStatusFormatted[$this->status_atual];
    }
}
