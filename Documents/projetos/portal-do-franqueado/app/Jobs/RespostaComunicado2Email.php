<?php

namespace App\Jobs;

use App\Models\Comunicado;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class RespostaComunicado2Email extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Comunicado
     */
    protected $comunicado = null;

    /**
     * @var Comunicado\Questionamento
     */
    protected $questionamento = null;

    /**
     * @var Comunicado\Questionamento
     */
    protected $questionamento_to_reply = null;

    /**
     * Create a new job instance.
     * @param Comunicado $comunicado
     * @param Comunicado\Questionamento $questionamento
     * @param Comunicado\Questionamento $questionamento_to_reply
     * @return void
     */
    public function __construct(Comunicado $comunicado, Comunicado\Questionamento $questionamento, Comunicado\Questionamento $questionamento_to_reply)
    {
        $this->comunicado = $comunicado;
        $this->questionamento = $questionamento;
        $this->questionamento_to_reply = $questionamento_to_reply;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->questionamento_to_reply->user;
        $data = ['comunicado' => $this->comunicado, 'questionamento' => $this->questionamento];

        Mail::send('emails.comunicado-questionamento-resposta', $data, function (Message $message) use ($user) {
            $subject = '[Comunicados - ' . env('APP_NAME') . '] - Questionamentos';
            $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
            $message->replyTo(env('EMAIL_NAO_RESPONDER'));
            $message->to($user->email)->subject($subject);
        });
    }
}
