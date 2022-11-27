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

class VerificacaoPedido extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @property Collection|null
     */
    public $to = null;

    /**
     * @property Pedido
     */
    public $pedido = null;

    /**
     * Create a new job instance.
     *
     * VerificacaoPedido constructor.
     * @param Collection $to
     * @param Pedido $pedido
     * return void
     */
    public function __construct(Collection $to, Pedido $pedido)
    {
        $this->to = $to;
        $this->pedido = $pedido;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->to->count() > 0) {
            $this->to->each(function (User $user) {
                $data = ['item' => $this->pedido];
                Mail::send('emails.verificacao-pedido', $data, function(Message $message) use ($user) {
                    $subject = '[Fornecimento - ' . env('APP_NAME') . '] - Verificação de pedidos';
                    $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
                    $message->replyTo(env('EMAIL_NAO_RESPONDER'));
                    $message->to($user->email)->subject($subject);
                });
            });
        }
    }
}
