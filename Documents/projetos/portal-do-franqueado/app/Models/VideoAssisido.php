<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VideoAssisido.
 *
 * @property int $id
 * @property int $video_id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @property-read \App\Models\Video $video
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VideoAssisido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VideoAssisido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VideoAssisido whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VideoAssisido whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VideoAssisido whereVideoId($value)
 * @mixin \Eloquent
 */
class VideoAssisido extends Model
{
    protected $table = 'video_assisido';
    protected $fillable = ['video_id', 'user_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }
}
