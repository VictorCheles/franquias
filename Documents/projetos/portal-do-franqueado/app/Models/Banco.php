<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Banco.
 *
 * @property int $id
 * @property string $codigo
 * @property string $nome
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banco whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banco whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banco whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banco whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banco whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Banco extends Model
{
}
