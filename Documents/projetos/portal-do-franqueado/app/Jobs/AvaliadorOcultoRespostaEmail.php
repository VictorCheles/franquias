<?php

namespace App\Jobs;

use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class AvaliadorOcultoRespostaEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $user;
    public $formulario;
    public $loja;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $formulario)
    {
        $this->user = $user;
        $this->formulario = $formulario;
        $loja = $this->loja = \App\Models\Loja::find($this->formulario->pivot->loja_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'user' => $this->user,
            'form' => $this->formulario,
            'loja' => $this->loja,
        ];


        Mail::send('emails.resposta-cliente-oculto', $data, function(Message $message) {
            $mails_to = explode(',', env('EMAIL_CLIENTE_OCULTO'));
            $subject = "[Cliente Oculto - " . env('APP_NAME') . "] - Resposta de Cliente oculto";
            $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
            $message->replyTo(env('EMAIL_NAO_RESPONDER'));
            $message->to($mails_to)->subject($subject);
        });
    }
}
