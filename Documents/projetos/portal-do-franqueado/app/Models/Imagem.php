<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Imagem.
 *
 * @property int $id
 * @property string $descricao
 * @property string $url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $thumbnail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Imagem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Imagem whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Imagem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Imagem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Imagem whereUrl($value)
 * @mixin \Eloquent
 */
class Imagem extends Model
{
    public $fillable = ['url', 'descricao'];

    public function getThumbnailAttribute()
    {
        if (File::exists('uploads/imagem/' . $this->url)) {
            return asset('uploads/imagem/' . $this->url);
        }

        return 'https://placehold.it/1000x300?text=' . urlencode('Imagem não encontrada');
    }
}
