<?php

namespace App\Jobs;

use App\User;
use App\Models\Pedido;
use Illuminate\Mail\Message;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EdicaoPedidoEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @property User $to
     */
    protected $to = null;

    /**
     * @property Pedido $pedido
     */
    protected $pedido = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $users, Pedido $pedido)
    {
        $this->to = $users;
        $this->pedido = $pedido;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $data = ['item' => $this->pedido];
        $to = $this->to;

        Mail::send('emails.pedido-atualizado', $data, function (Message $message) use ($to) {
            $mails_to = [];
            $to->each(function (User $user) use (&$mails_to) {
                $mails_to[] = $user->email;
            });

            $subject = '[Atualização de Pedido - ' . env('APP_NAME') . '] - Um pedido foi atualizado';
            $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
            $message->replyTo(env('EMAIL_NAO_RESPONDER'));
            $message->to($mails_to)->subject($subject);

        });
    }
}
