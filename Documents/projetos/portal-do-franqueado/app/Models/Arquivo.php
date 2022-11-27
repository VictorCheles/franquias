<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Arquivo.
 *
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property string $arquivo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $pasta_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ArquivoUsuario[] $downloads
 * @property-read mixed $arquivo_src
 * @property-read mixed $extensao
 * @property-read \App\Models\Pasta|null $pasta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereArquivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo wherePastaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Arquivo extends Model
{
    protected $fillable = ['nome', 'descricao', 'arquivo', 'pasta_id'];

    public function getExtensaoAttribute()
    {
        return File::extension('uploads/arquivos/' . $this->arquivo);
    }

    public function getArquivoSrcAttribute()
    {
        return asset('uploads/arquivos/' . $this->arquivo);
    }

    public function pasta()
    {
        return $this->belongsTo(Pasta::class, 'pasta_id', 'id');
    }

    public function downloads()
    {
        return $this->hasMany(ArquivoUsuario::class, 'arquivo_id', 'id');
    }
}
