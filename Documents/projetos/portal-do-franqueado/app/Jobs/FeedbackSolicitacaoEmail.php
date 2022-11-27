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

class FeedbackSolicitacaoEmail extends Job implements ShouldQueue
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
     * @property User $feeder
     */
    protected $feeder = null;

    /**
     * Create a new job instance.
     * @param Collection $users
     * @param Solicitacao $solicitacao
     * @param User $feeder
     */
    public function __construct(Collection $users, Solicitacao $solicitacao, User $feeder)
    {
        $this->to = $users;
        $this->solicitacao = $solicitacao;
        $this->feeder = $feeder;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $to = $this->to;
        $feeder = $this->feeder;
        $solicitacao = $this->solicitacao;
        $data = ['solicitacao' => $this->solicitacao, 'feeder' => $this->feeder];
        Mail::send('emails.feedback-solicitacao', $data, function (Message $message) use ($to, $feeder, $solicitacao) {
            $mails_to = [];
            $to->each(function (User $user) use (&$mails_to) {
                $mails_to[] = $user->email;
            });

            $subject = '[Feedback Solicitação - ' . env('APP_NAME') . "] - {$feeder->nome} fez um feedback na solicitação {$solicitacao->titulo}";
            $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
            $message->replyTo(env('EMAIL_NAO_RESPONDER'));
            $message->to($mails_to)->subject($subject);
        });
    }
}
