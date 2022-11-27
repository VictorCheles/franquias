<?php

namespace App\Models\AvaliadorOculto;

use App\Models\Loja;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AvaliadorOculto\Formulario.
 *
 * @property-read mixed $status_string
 * @property-read mixed $status_string_formatted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Loja[] $lojas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AvaliadorOculto\Pergunta[] $perguntas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AvaliadorOculto\User[] $users
 * @mixin \Eloquent
 */
class Formulario extends Model
{
    const STATUS_ATIVO = 1;
    const STATUS_INATIVO = 2;

    const MAPA_STATUS = [
        self::STATUS_ATIVO => 'Ativo',
        self::STATUS_INATIVO => 'Inativo',
    ];

    const MAPA_STATUS_FORMATTED = [
        self::STATUS_ATIVO => '<span class="label label-success">Ativo</span>',
        self::STATUS_INATIVO => '<span class="label label-danger">Inativo</span>',
    ];

    protected $table = 'avaliador_oculto_formularios';
    public $fillable = ['nome', 'status'];

    public static function getFormulariosFromLoja($loja_id)
    {
        return self::whereHas('lojas', function ($q) use ($loja_id) {
            $q->where('loja_id', $loja_id);
        })->where('status', self::STATUS_ATIVO)->get();
    }

    public function getStatusStringAttribute()
    {
        return self::MAPA_STATUS[$this->status];
    }

    public function getStatusStringFormattedAttribute()
    {
        return self::MAPA_STATUS_FORMATTED[$this->status];
    }

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class, 'formulario_id', 'id')->orderBy('id');
    }

    public function lojas()
    {
        return $this->belongsToMany(Loja::class, 'avaliador_oculto_lojas_formularios', 'formulario_id', 'loja_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'avaliador_oculto_usuarios_formularios', 'formulario_id', 'user_id')->withPivot('loja_id', 'finalizou', 'respondido_em');
    }
}
