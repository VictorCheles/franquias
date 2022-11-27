<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Enquete
 *
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property \Carbon\Carbon $inicio
 * @property \Carbon\Carbon $fim
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Comunicado $comunicado
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $destinatarios
 * @property-read mixed $aberta
 * @property-read mixed $aberta_formatted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pergunta[] $perguntas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enquete whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enquete whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enquete whereFim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enquete whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enquete whereInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enquete whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enquete whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Enquete extends Model
{
    protected $fillable = ['nome', 'descricao', 'inicio', 'fim', 'imagem'];
    protected $dates = ['inicio', 'fim'];

    public static function validationRulesCreate()
    {
        return [
            'enquete.nome' => 'required|max:100',
            'enquete.inicio' => 'required|date',
            'enquete.fim' => 'required|date|after:enquete.inicio',
            'pergunta.*' => 'required|max:255',
            'resposta.*.*' => 'required|max:255',
            'destinatario.*' => 'required|exists:users,id',
        ];
    }

    public function getAbertaAttribute()
    {
        $agora = Carbon::now();
        if ($agora->lt($this->inicio) or $agora->gt($this->fim)) {
            return false;
        }

        return true;
    }

    public function getAbertaFormattedAttribute()
    {
        $agora = Carbon::now();
        if ($agora->lt($this->inicio) or $agora->gt($this->fim)) {
            return '<label class="label label-danger">Fechada</label>';
        }

        return '<label class="label label-success">Aberta</label>';
    }

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class, 'enquete_id', 'id');
    }

    public function destinatarios()
    {
        return $this->belongsToMany(User::class, 'enquetes_destinatarios', 'enquete_id', 'user_id');
    }

    public function comunicado()
    {
        return $this->belongsTo(Comunicado::class, 'id', 'enquete_id');
    }
}
