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

class NovoPedidoEmail extends Job implements ShouldQueue
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
        $pedido = $this->pedido;
        if ($this->to->count() > 0) {
            $this->to->each(function (User $user) use ($pedido) {

                $data = ['item' => $pedido];
                Mail::send('emails.pedido', $data, function (Message $message) use ($user) {
                    $subject = '[Novo Pedido - ' . env('APP_NAME') . '] - Novo pedido cadastrado';
                    $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
                    $message->replyTo(env('EMAIL_NAO_RESPONDER'));
                    $message->to($user->email)->subject($subject);
                });

            });
        }
    }
}
