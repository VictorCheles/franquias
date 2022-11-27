<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vitrine.
 *
 * @property int $id
 * @property string $titulo
 * @property string|null $link
 * @property bool $status
 * @property string $imagem
 * @property int $ordem
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $img
 * @property-read mixed $status_styled
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vitrine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vitrine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vitrine whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vitrine whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vitrine whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vitrine whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vitrine whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vitrine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vitrine extends Model
{
    protected $fillable = ['titulo', 'link', 'status', 'imagem', 'ordem'];

    public static $mapStatus = [
        true => 'Ativa',
        false => 'Inativa',
    ];

    public static $mapStatusStyled = [
        true => '<span class="label label-success">Ativa</span>',
        false => '<span class="label label-danger">Inativa</span>',
    ];

    public function getImgAttribute()
    {
        if (File::exists('uploads/vitrine/' . $this->imagem)) {
            return asset('uploads/vitrine/' . $this->imagem);
        }

        return 'https://placehold.it/1110x206?text=Imagem não encontrada';
    }

    public function getStatusStyledAttribute()
    {
        return self::$mapStatusStyled[$this->status];
    }
}
