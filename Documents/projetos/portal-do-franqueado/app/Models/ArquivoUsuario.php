<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ArquivoUsuario.
 *
 * @property int $id
 * @property int $arquivo_id
 * @property int $user_id
 * @property int $downloads
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArquivoUsuario whereArquivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArquivoUsuario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArquivoUsuario whereDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArquivoUsuario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArquivoUsuario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArquivoUsuario whereUserId($value)
 * @mixin \Eloquent
 */
class ArquivoUsuario extends Model
{
    protected $fillable = ['arquivo_id', 'user_id', 'downloads'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
