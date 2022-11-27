<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    const TIPO_COMUNICADO = 1;
    const TIPO_PEDIDO = 2;
    const TIPO_SOLICITACAO = 3;

    protected $table = 'notificacoes';
    protected $fillable = ['destinatario', 'mensagem', 'status', 'tipo', 'atributos'];

    protected $casts = [
        'atributos' => 'json',
    ];

    public static $mapStatusStyled = [
        true => '<span class="label label-success">Sim</span>',
        false => '<span class="label label-danger">Não</span>',
    ];

    public function getStatusFormattedAttribute()
    {
        return self::$mapStatusStyled[$this->status];
    }
}
