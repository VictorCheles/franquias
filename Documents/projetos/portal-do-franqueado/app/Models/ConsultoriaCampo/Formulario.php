<?php

namespace App\Models\ConsultoriaCampo;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConsultoriaCampo\Formulario.
 *
 * @property int $id
 * @property string $descricao
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\Topico[] $topicos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultoriaCampo\Visita[] $visitas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Formulario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Formulario whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Formulario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ConsultoriaCampo\Formulario whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Formulario extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Formulario $model) {
            if ($model->visitas->count() > 0) {
                throw new \Exception('Não é possível excluir formulários que já foram utilizados');
            }
            $topicos = $model->topicos;
            $model->topicos()->detach();
            $topicos->each(function (Topico $t) {
                $t->delete();
            });
        });
    }

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
    protected $table = 'formularios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descricao',
    ];
    /**
     * The attributes that should be casted as dates.
     *
     * @var array
     */
    protected $dates = [

    ];

    public function topicos()
    {
        return $this->belongsToMany(Topico::class);
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }
}
