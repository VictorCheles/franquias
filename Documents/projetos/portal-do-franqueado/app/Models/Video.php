<?php

namespace App\Models;

use VideoEmbed\VideoEmbed;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video
 *
 * @property int $id
 * @property string $titulo
 * @property string $descricao
 * @property string $url
 * @property int $tag_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $embed
 * @property-read mixed $thumbnail
 * @property-read mixed $video_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\VideoAssisido[] $quemAssistiu
 * @property-read \App\Models\Tag $tag
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereUrl($value)
 * @mixin \Eloquent
 */
class Video extends Model
{
    protected $fillable = ['titulo', 'descricao', 'url', 'tag_id'];

    public function quemAssistiu()
    {
        return $this->hasMany(VideoAssisido::class, 'video_id', 'id');
    }

    public function getEmbedAttribute()
    {
        return VideoEmbed::render($this->url, ['width' => '100%', 'height' => 350]);
    }

    public function getVideoIdAttribute()
    {
        return VideoEmbed::render($this->url, ['return_id' => true]);
    }

    public function getThumbnailAttribute()
    {
        return VideoEmbed::render($this->url, ['return_thumbnail' => true]);
    }

    public function tag()
    {
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }
}
