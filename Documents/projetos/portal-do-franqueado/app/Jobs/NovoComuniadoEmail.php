<?php

namespace App\Jobs;

use App\User;
use App\Models\Comunicado;
use Illuminate\Mail\Message;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class NovoComuniadoEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @property Collection $users
     */
    protected $to = [];

    /**
     * @property Comunicado $comunicado
     */
    protected $comunicado = null;

    /**
     * Create a new job instance.
     */
    public function __construct(Collection $users, Comunicado $comunicado)
    {
        $this->to = $users;
        $this->comunicado = $comunicado;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $comunicado = $this->comunicado;
        if ($this->to->count() > 0) {
            $this->to->each(function (User $user) use ($comunicado) {
                $data = ['comunicado' => $comunicado];
                Mail::send('emails.novo-comunicado', $data, function(Message $message) use ($user){
                    $subject = '[Comunicados - ' . env('APP_NAME') . '] - Novo comunicado cadastrado';
                    $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
                    $message->replyTo(env('EMAIL_NAO_RESPONDER'));
                    $message->to($user->email)->subject($subject);
                });
            });
        }
    }
}
