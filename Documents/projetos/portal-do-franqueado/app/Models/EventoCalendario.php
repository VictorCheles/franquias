<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EventoCalendario
 *
 * @property int $id
 * @property string $titulo
 * @property \Carbon\Carbon|null $inicio
 * @property \Carbon\Carbon|null $fim
 * @property string $relacao
 * @property int $relacao_id
 * @property-write mixed $periodo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventoCalendario whereFim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventoCalendario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventoCalendario whereInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventoCalendario whereRelacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventoCalendario whereRelacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventoCalendario whereTitulo($value)
 * @mixin \Eloquent
 */
class EventoCalendario extends Model
{
    public $table = 'evento_calendario';
    public $timestamps = false;
    public $fillable = ['titulo', 'inicio', 'fim', 'relacao', 'relacao_id'];
    public $dates = ['inicio', 'fim'];

    public function getPerido()
    {
        return $this->inicio->format('d/m/Y') . ' - ' . $this->fim->format('d/m/Y');
    }

    public function setInicioAttribute($value)
    {
        $this->attributes['inicio'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setFimAttribute($value)
    {
        $this->attributes['fim'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setPeriodoAttribute($value)
    {
        list($first, $last) = explode(' - ', $value);
        $this->inicio = $first;
        $this->fim = $last;
    }

    private function abstractRelation($relation)
    {
        return $this->belongsTo($relation, 'relacao_id', 'id')->whereHas('evento_calendario', function ($q) use ($relation) {
            $q->where('relacao', $relation);
        });
    }

    public function comunicado()
    {
        return $this->abstractRelation(Comunicado::class);
    }

    public function pracas()
    {
        return $this->abstractRelation(Praca::class);
    }
}
