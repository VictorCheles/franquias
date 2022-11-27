<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    const STATUS_NOVA = 1;
    const STATUS_EM_ANDAMENTO = 2;
    const STATUS_FINALIZADA = 3;
    const STATUS_NEGADA = 4;

    const PRAZO_NAO_DEFINIDO = 1;
    const PRAZO_CONCLUIDA = 2;
    const PRAZO_ATE_24 = 3;
    const PRAZO_2_ATE_5 = 4;
    const PRAZO_5_MAIS = 5;

    protected $table = 'solicitacoes';
    protected $fillable = ['titulo', 'descricao', 'status', 'user_id', 'setor_id', 'anexos', 'prazo'];
    protected $casts = ['anexos' => 'json'];
    protected $dates = ['prazo'];

    public static $mapStatus = [
        self::STATUS_NOVA => 'Novas',
        self::STATUS_EM_ANDAMENTO => 'Em andamento',
        self::STATUS_FINALIZADA => 'Finalizadas',
        self::STATUS_NEGADA => 'Negadas',
    ];
    public static $mapStatusFormatted = [
        self::STATUS_NOVA => '<span class="badge">Nova</span>',
        self::STATUS_EM_ANDAMENTO => '<span class="badge bg-orange">Em andamento</span>',
        self::STATUS_FINALIZADA => '<span class="badge bg-green">Finalizada</span>',
        self::STATUS_NEGADA => '<span class="badge bg-black">Negada</span>',
    ];

    public static $mapStatusFormattedButton = [
        self::STATUS_NOVA => '<a class="btn btn-info" style="cursor: unset">Nova Solicitação</a>',
        self::STATUS_EM_ANDAMENTO => '<a class="btn btn-warning" style="cursor: unset; width: 98%">Em Andamento</a>',
        self::STATUS_FINALIZADA => '<a class="btn btn-success" style="cursor: unset; width: 98%">Finalizada</a>',
        self::STATUS_NEGADA => '<a class="btn btn-danger" style="cursor: unset; width: 98%">Negada</a>',
    ];

    public static function buttonFilter($status, $counter = 0)
    {
        $classes = [
            self::STATUS_NOVA => 'btn-info',
            self::STATUS_EM_ANDAMENTO => 'btn-warning',
            self::STATUS_FINALIZADA => 'btn-success',
            self::STATUS_NEGADA => 'btn-danger',
        ];

        return '<a class="btn ' . $classes[$status] . '" href="' . url()->current() . '?' . http_build_query(['filter' => ['status' => $status]]) . '">' . $counter . ' ' . self::$mapStatus[$status] . '</a>';
    }

    public static $mapPrazo = [
        self::PRAZO_NAO_DEFINIDO => 'Sem prazo definido',
        self::PRAZO_CONCLUIDA => 'Concluída',
        self::PRAZO_ATE_24 => 'até 24h',
        self::PRAZO_2_ATE_5 => 'de 2 até 5 dias',
        self::PRAZO_5_MAIS => '+ de 5 dias',
    ];

    public static $mapPrazoFormatted = [
        self::PRAZO_NAO_DEFINIDO => 'Sem prazo definido',
        self::PRAZO_CONCLUIDA => '<label class="label label-success">Concluída</label>',
        self::PRAZO_ATE_24 => '<label class="label label-danger">até 24h</label>',
        self::PRAZO_2_ATE_5 => '<label class="label label-warning">de 2 até 5 dias</label>',
        self::PRAZO_5_MAIS => '+ de 5 dias',
    ];

    public static function rulesCreate()
    {
        return [
            'setor_id' => 'required|exists:setores,id',
            'titulo' => 'required',
            'descricao' => 'required',
        ];
    }

    public static function rulesUpdate()
    {
        return [
            'observacoes' => 'required',
        ];
    }

    public function getStatusFormattedAttribute()
    {
        return self::$mapStatusFormatted[$this->status];
    }

    public function getStatusFormattedButtonAttribute()
    {
        return self::$mapStatusFormattedButton[$this->status];
    }

    public function prazoTag()
    {
        if (is_null($this->prazo)) {
            return self::PRAZO_NAO_DEFINIDO;
        } elseif (in_array($this->status, [self::STATUS_FINALIZADA, self::STATUS_NEGADA])) {
            return self::PRAZO_CONCLUIDA;
        } elseif (Carbon::parse($this->prazo->format('Y-m-d'))->diffInDays(Carbon::parse(Carbon::now()->format('Y-m-d'))) <= 1) {
            return self::PRAZO_ATE_24;
        } elseif (Carbon::parse($this->prazo->format('Y-m-d'))->diffInDays(Carbon::parse(Carbon::now()->format('Y-m-d'))) > 1 and Carbon::parse($this->prazo->format('Y-m-d'))->diffInDays(Carbon::parse(Carbon::now()->format('Y-m-d'))) <= 5) {
            return self::PRAZO_2_ATE_5;
        } else {
            return self::PRAZO_5_MAIS;
        }
    }

    public function getTagPrazoAttribute()
    {
        return self::$mapPrazoFormatted[$this->prazoTag()];
    }

    public function getTagAttribute()
    {
        return $this->setor->tag_formatted . $this->created_at->format('Y') . $this->created_at->format('m') . $this->id;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function historico()
    {
        return $this->hasMany(SolicitacaoHistorico::class, 'solicitacao_id', 'id')->orderBy('created_at', 'desc');
    }

    public function setor()
    {
        return $this->hasOne(Setor::class, 'id', 'setor_id');
    }
}
