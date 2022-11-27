<?php

namespace App\Jobs;

use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Auth\CanResetPassword;
use Mail;

class ResetPassword extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @property CanResetPassword $to
     */
    protected $to = null;

    /**
     * @property string $token
     */
    protected $token = null;

    /**
     * @property string $view
     */
    protected $view;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CanResetPassword $user, $token, $view)
    {
        $this->to = $user;
        $this->token = $token;
        $this->view = $view;
    }

    public function handle()
    {
        $data = [
            'user' => $this->to,
            'token' => $this->token
        ];

        Mail::send('auth.emails.password', $data, function(Message $message) use ($data) {
            $subject = '[Recuperação de senha - ' . env('APP_NAME') . '] - Recuperar senha';
            $message->from(env('EMAIL_FROM'), env('EMAIL_FROM_NAME'));
            $message->replyTo(env('EMAIL_NAO_RESPONDER'));
            $message->to($data['user']->getEmailForPasswordReset())->subject($subject);
        });
    }
}
