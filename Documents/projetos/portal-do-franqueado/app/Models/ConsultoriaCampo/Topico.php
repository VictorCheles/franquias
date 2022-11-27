<?php

namespace App\Models\ConsultoriaCampo;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConsultoriaCampo\Topico.
 *
 * @property int $id
 * @property string $descricao
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\Formulario[] $formularios
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\Pergunta[] $perguntas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Topico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Topico whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Topico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Topico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Topico extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Topico $model) {
            $model->formularios()->detach();
            $model->perguntas()->delete();
        });
    }

    /**
     * The schema associated with the model.
     *
     * @var string
     */
    protected $connection = 'consultoria_campo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descricao',
    ];

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class);
    }

    public function formularios()
    {
        return $this->belongsToMany(Formulario::class);
    }
}
