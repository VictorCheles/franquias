<?php

namespace App\Models\Historiae;

use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use WithAuthenticatable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['model', 'payload_after', 'payload_before', 'created', 'user_id'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'payload_after' => 'array',
        'payload_before' => 'array',
    ];

    /**
     * Get the name of the model that was changed.
     */
    public function getLoggableModelAttribute()
    {
        return $this->getOriginal('model');
    }

    /**
     * Get the primary key of the model that was changed.
     */
    public function getLoggableIdAttribute()
    {
        return isset($this->payload_before['id']) ? $this->payload_before['id'] : null;
    }

    /**
     * Get the verbose name of the model that was changed.
     */
    public function getModelAttribute($value)
    {
        return config("historiae.labels.{$value}", $value);
    }

    /*
     * Get the user that changed the records.
     */
    public function user()
    {
        return $this->belongsTo(static::$authenticatable);
    }

    /**
     * Get all of the owning loggable models.
     */
    public function loggable()
    {
        return $this->morphTo(null, 'loggable_model', 'loggable_id');
    }
}
