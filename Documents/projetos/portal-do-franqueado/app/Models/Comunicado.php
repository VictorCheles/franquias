<?php

namespace App\Models;

use File;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comunicado\Questionamento;

class Comunicado extends Model
{
    const TIPO_INTERNO = 1;
    const TIPO_FRANQUEADO = 2;

    protected $fillable = [
        'titulo', 'descricao', 'imagem', 'videos', 'setor_id',
        'tipo_id', 'galeria', 'inicio', 'fim',
        'inicio_importancia', 'fim_importancia',
        'imagem_id', 'anexos', 'aberto_para_questionamento',
    ];

    protected $dates = [
        'inicio', 'fim',
        'inicio_importancia', 'fim_importancia',
    ];

    protected $casts = [
        'galeria' => 'json',
        'anexos' => 'json',
        'aberto_para_questionamento' => 'boolean',
    ];

    protected $appends = ['img'];

    public static $tipos = [
        self::TIPO_INTERNO => 'Interno',
        self::TIPO_FRANQUEADO => 'Franqueado',
    ];

    /**
     * SCOPES.
     */
    public function scopeImportante($q)
    {
        $q->where('inicio_importancia', '!=', null)
            ->where('fim_importancia', '!=', null)
            ->where('inicio_importancia', '<=', Carbon::now())
            ->where('fim_importancia', '>=', Carbon::now());
    }

    /**
     * GET / SET.
     */
    public function setInicioAttribute($value)
    {
        $this->attributes['inicio'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setFimAttribute($value)
    {
        $this->attributes['fim'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setPeriodoAcaoAttribute($value)
    {
        list($first, $last) = explode(' - ', $value);
        $this->inicio = $first;
        $this->fim = $last;
    }

    public function setInicioImportanciaAttribute($value)
    {
        $this->attributes['inicio_importancia'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setFimImportanciaAttribute($value)
    {
        $this->attributes['fim_importancia'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function setPeriodoImportanciaAttribute($value)
    {
        list($first, $last) = explode(' - ', $value);
        $this->inicio_importancia = $first;
        $this->fim_importancia = $last;
    }

    public function getPeriodoAcaoAttribute()
    {
        if ($this->inicio and $this->fim) {
            return Carbon::parse($this->inicio)->format('d/m/Y') . ' - ' . Carbon::parse($this->fim)->format('d/m/Y');
        }

        return;
    }

    public function getPeriodoImportanciaAttribute()
    {
        if ($this->inicio_importancia and $this->fim_importancia) {
            return Carbon::parse($this->inicio_importancia)->format('d/m/Y') . ' - ' . Carbon::parse($this->fim_importancia)->format('d/m/Y');
        }

        return;
    }

    public function getIsImportantAttribute()
    {
        if ($this->inicio_importancia and $this->fim_importancia) {
            return (Carbon::now()->gte($this->inicio_importancia) and Carbon::now()->lte($this->fim_importancia));
        }

        return false;
    }

    public function getImgAttribute()
    {
        if ($this->imagem_id) {
            return $this->imagem_padrao->thumbnail;
        } elseif (File::exists('uploads/comunicados/' . $this->imagem)) {
            return asset('uploads/comunicados/' . $this->imagem);
        } else {
            return 'https://placehold.it/800x600?text=' . urlencode('Imagem não encontrada');
        }
    }

    public function getDescricaoResumidaAttribute()
    {
        return str_limit(strip_tags($this->descricao), 450);
    }

    public function getTipoFormattedAttribute()
    {
        if (Auth()->user()->isAdmin() and $this->tipo_id) {
            return self::$tipos[$this->tipo_id];
        }
    }

    public function destinatarios()
    {
        return $this->hasMany(ComunicadoDestinatarios::class, 'comunicado_id', 'id')->with('user')->orderBy('status', 'desc');
    }

    public function setor()
    {
        return $this->hasOne(Setor::class, 'id', 'setor_id');
    }

    public function enquete()
    {
        return $this->hasOne(Enquete::class, 'id', 'enquete_id');
    }

    public function evento_calendario()
    {
        return $this->hasOne(EventoCalendario::class, 'relacao_id', 'id');
    }

    public function imagem_padrao()
    {
        return $this->hasOne(Imagem::class, 'id', 'imagem_id');
    }

    public function questionamentos()
    {
        return $this->hasMany(Questionamento::class, 'comunicado_id', 'id')
            ->whereNull('questionamento_id')
            ->orderBy('created_at', 'desc')
            ->with('user', 'respostas');
    }
}
