<?php

namespace App\Jobs;

use App\Models\AvaliadorOculto\User;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class AvaliadorOcultoEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user = null;
    protected $raw_password = null;

    /**
     * AvaliadorOcultoEmail constructor.
     * @param User $user
     * @param $raw_password
     *
     * Create a new job instance.
     */
    public function __construct(User $user, $raw_password)
    {
        $this->user = $user;
        $this->raw_password = $raw_password;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $visitas = $this->user->formularios()->wherePivot('finalizou', false)->get();
        $data = ['user' => $this->user, 'raw_password' => $this->raw_password, 'visitas' => $visitas];

        Mail::send('emails.novo-cliente-oculto', $data, function (Message $message) use ($visitas) {
            $subject = "[Cliente Oculto - " . env('APP_NAME') . "] - Seja bem vindo cliente oculto";
            $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
            $message->replyTo(env('EMAIL_NAO_RESPONDER'));
            $message->to($this->user->email)->subject($subject);
        });
    }
}
