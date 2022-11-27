<?php

namespace App\Jobs;

use App\User;
use App\Models\Solicitacao;
use Illuminate\Mail\Message;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class NovaSolicitacaoEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @property Collection $to
     */
    protected $to = null;

    /**
     * @property Solicitacao $solicitacao
     */
    protected $solicitacao = null;

    /**
     * @property User $solicitante
     */
    protected $solicitante = null;

    /**
     * Create a new job instance.
     * @param Collection $users
     * @param Solicitacao $solicitacao
     */
    public function __construct(Collection $users, Solicitacao $solicitacao)
    {
        $this->to = $users;
        $this->solicitacao = $solicitacao;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {

        $data = ['solicitacao' => $this->solicitacao];
        $to = $this->to;
        $solicitacao = $this->solicitacao;

        Mail::send('emails.nova-solicitacao', $data, function(Message $message) use ($to, $solicitacao) {
            $mails_to = [];
            $to->each(function (User $user) use (&$mails_to) {
                $mails_to[] = $user->email;
            });

            $subject = '[Nova Solicitação - ' . env('APP_NAME') . "] - {$solicitacao->user->nome} fez uma nova solicitação";
            $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
            $message->replyTo(env('EMAIL_NAO_RESPONDER'));
            $message->to($mails_to)->subject($subject);
        });
    }
}
