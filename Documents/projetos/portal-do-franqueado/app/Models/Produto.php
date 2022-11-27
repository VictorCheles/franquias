<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Produto.
 *
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property float $preco
 * @property string|null $imagem
 * @property bool $disponivel
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $categoria_id
 * @property float $peso
 * @property-read \App\Models\CategoriaProduto $categoria
 * @property-read mixed $disponivel_categoria_formatted
 * @property-read mixed $disponivel_formatted
 * @property-read mixed $img
 * @property-read mixed $preco_formatted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto disponivel()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto whereDisponivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto wherePreco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Produto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Produto extends Model
{
    protected $fillable = ['nome', 'descricao', 'preco', 'imagem', 'disponivel', 'categoria_id', 'peso'];

    public static $mapDisponibilidade = [
        true => 'Sim',
        false => 'Não',
    ];

    public static $mapDisponibilidadeFormatted = [
        true => '<span class="label label-success">Sim</span>',
        false => '<span class="label label-danger">Não</span>',
    ];

    public function scopeDisponivel($q)
    {
        $q->whereDisponivel(true)->whereHas('categoria', function ($q) {
            $q->whereDisponivel(true);
        });
    }

    public function getImgAttribute()
    {
        if (! is_null($this->imagem) and File::exists('uploads/produto/' . $this->imagem)) {
            return asset('uploads/produto/' . $this->imagem);
        } else {
            return 'https://placehold.it/500x500?text=Imagem não encontrada';
        }
    }

    public function getPrecoFormattedAttribute()
    {
        return maskMoney($this->preco);
    }

    public function getDisponivelFormattedAttribute()
    {
        return self::$mapDisponibilidadeFormatted[$this->disponivel];
    }

    public function getDisponivelCategoriaFormattedAttribute()
    {
        if ($this->categoria->disponivel) {
            return self::$mapDisponibilidadeFormatted[$this->disponivel];
        } else {
            return '<span class="label label-danger">Não <small>(categoria desabilitada)</small></span>';
        }
    }

    public function categoria()
    {
        return $this->hasOne(CategoriaProduto::class, 'id', 'categoria_id');
    }
}
