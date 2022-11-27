<?php

namespace App\Jobs;

use App\User;
use App\Models\Comunicado;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class RespostaComunicadoEmail extends Job implements ShouldQueue
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
     * Create a new job instance.
     * @param Comunicado $comunicado
     * @param Comunicado\Questionamento $questionamento
     * @return void
     */
    public function __construct(Comunicado $comunicado, Comunicado\Questionamento $questionamento)
    {
        $this->comunicado = $comunicado;
        $this->questionamento = $questionamento;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->comunicado->setor->responsaveis->count() > 0) {
            $this->comunicado->setor->responsaveis->each(function (User $user) {
                $data = ['comunicado' => $this->comunicado, 'questionamento' => $this->questionamento];
                Mail::send('emails.comunicado-questionamento', $data, function(Message $message) use ($user) {
                    $subject = '[Comunicados - ' . env('APP_NAME') . '] - Questionamentos';
                    $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
                    $message->replyTo(env('EMAIL_NAO_RESPONDER'));
                    $message->to($user->email)->subject($subject);
                });
            });
        }
    }
}
