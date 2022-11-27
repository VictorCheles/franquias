<?php

namespace App\Models\Comunicado;

use App\User;
use App\Models\Comunicado;
use Illuminate\Database\Eloquent\Model;

class Questionamento extends Model
{
    public $fillable = ['comunicado_id', 'user_id', 'questionamento_id', 'texto', 'anexos'];

    public $casts = [
        'anexos' => 'json',
    ];

    public function respostas()
    {
        return $this->hasMany(self::class)->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comunicado()
    {
        return $this->belongsTo(Comunicado::class, 'comunicado_id', 'id');
    }
}
