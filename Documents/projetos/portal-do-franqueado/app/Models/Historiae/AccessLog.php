<?php

namespace App\Models\Historiae;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Historiae\AccessLog.
 *
 * @property int $id
 * @property string $domain
 * @property string $ip
 * @property string $url
 * @property string $status
 * @property string $method
 * @property int|null $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Historiae\AccessLog whereUserId($value)
 * @mixin \Eloquent
 */
class AccessLog extends Model
{
    use WithAuthenticatable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ip', 'domain', 'url', 'status', 'method', 'user_id'];

    /*
     * Get the user linked to the access.
     */
    public function user()
    {
        return $this->belongsTo(static::$authenticatable);
    }
}
