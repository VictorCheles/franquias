<?php

namespace App\Jobs;

use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class RelatorioConsultoria extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $to = null;
    public $visita = null;
    public $final = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $visita)
    {
        $this->to = $to;
        $this->visita = $visita;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mails_to = [];
        collect(explode(',', env('EMAIL_CONSULTORIA_DE_CAMPO')))->each(function ($email) use (&$mails_to) {
            $mails_to[] = $email;
        });

        if($this->to->count() > 0){
            $this->to->each(function ($t) use (&$mails_to) {
                $mails_to[] = $t->email;
            });
        }

        $data = [
            'item' => $this->visita,
        ];

        Mail::send('portal-franqueado.admin.consultoria-campo.visitas.relatorios.parcial', $data, function (Message $message) use ($mails_to) {
            $subject = "[Consultoria de campo - " . env('APP_NAME') . "] - Relatório final da visita";
            $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
            $message->replyTo(env('EMAIL_NAO_RESPONDER'));
            $message->to($mails_to)->subject($subject);
        });
    }
}
