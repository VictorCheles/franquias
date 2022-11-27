<?php

namespace App\Models\Mensagens;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    const FOLDER_INBOX = 1;
    const FOLDER_SENT = 2;
    const FOLDER_DRAFT = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mensagens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'text', 'folder', 'attachments',
        'to_id', 'from_id', 'message_response_id', 'read_in',
    ];

    /**
     * The attributes that should be casted as dates.
     *
     * @var array
     */
    protected $dates = [
        'read_in',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'attachments' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}
