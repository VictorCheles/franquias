<?php

namespace App\Models\AvaliadorOculto;

use Carbon\Carbon;
use App\Models\Banco;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\AvaliadorOculto\User.
 *
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property int $nivel_acesso
 * @property int $status
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $grupo_id
 * @property string|null $foto
 * @property bool $aceite
 * @property-read \App\Models\Banco $banco
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AvaliadorOculto\Formulario[] $formularios
 * @property-read mixed $aceite_formatted
 * @property-read mixed $data_nascimento
 * @property-read mixed $escolaridade_formatted
 * @property-read mixed $idade
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereAceite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereNivelAcesso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AvaliadorOculto\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /**
     * CPF
     * RG
     * Nome completo
     * Data nascimento
     * Escolaridade
     * Banco
     * Agencia
     * Conta Corrente.
     */
    const MAPA_UFS = [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins',
    ];

    const MAPA_ACEITE = [
        0 => 'Não respondeu',
        1 => 'Aceitou',
        2 => 'Não aceitou',
    ];

    const MAPA_ESCOLARIDADE = [
        1 => 'Fundamental incompleto',
        2 => 'Fundamental completo',
        3 => 'Médio incompleto',
        4 => 'Médio completo',
        5 => 'Superior incompleto',
        6 => 'Superior completo',
        7 => 'Pós Graduado',
    ];

    const ALL_ATTRIBUTES = [
        'nome', 'cpf', 'rg', 'data_nascimento', 'escolaridade', 'banco_id', 'agencia', 'conta_corrente', 'cidade',
        'email', 'password', 'aceite', 'data_aceite', 'foto',
        'telefone', 'uf',
    ];

    protected $table = 'avaliador_oculto_users';

    protected $fillable = [
        'nome', 'cpf', 'rg', 'data_nascimento', 'escolaridade', 'banco_id', 'agencia', 'conta_corrente', 'cidade',
        'email', 'password', 'aceite', 'data_aceite', 'foto', 'telefone', 'uf',
    ];

    protected $dates = [
        'data_aceite',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getFotoAttribute($value)
    {
        if (! empty($value)) {
            return asset('uploads/cliente_oculto/' . $value);
        }

        return $value;
    }

    public function getIdadeAttribute()
    {
        if ($this->data_nascimento) {
            return Carbon::now()->diffInYears($this->data_nascimento);
        }

        return '';
    }

    public function getDataNascimentoAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getEscolaridadeFormattedAttribute()
    {
        if ($this->escolaridade) {
            return self::MAPA_ESCOLARIDADE[$this->escolaridade];
        }

        return '';
    }

    public function getAceiteFormattedAttribute()
    {
        return self::MAPA_ACEITE[$this->aceite];
    }

    public function banco()
    {
        return $this->hasOne(Banco::class, 'id', 'banco_id');
    }

    public function formularios()
    {
        return $this->belongsToMany(Formulario::class, 'avaliador_oculto_usuarios_formularios', 'user_id', 'formulario_id')->withPivot('loja_id', 'observacoes', 'foto_comprovante', 'foto_loja', 'foto_consumo', 'finalizou', 'data_termino', 'respondido_em', 'data_visita');
    }

    public function formulariosFinalizados()
    {
        return $this->formularios()->wherePivot('finalizou', true);
    }

    public function respondeuAlgo()
    {
        return $this->formulariosFinalizados()->count() > 0 ? true : false;
    }
}
