<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SocialAccount.
 *
 * @property int $id
 * @property string $cliente_email
 * @property string $provider_user_id
 * @property string $provider
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Cliente $cliente
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialAccount whereClienteEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialAccount whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialAccount whereProviderUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SocialAccount extends Model
{
    protected $fillable = ['cliente_email', 'provider_user_id', 'provider'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_email', 'email');
    }
}
