<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AvaliadorOculto\User;

class CorrecaoEmailsClienteOculto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanitize:cliente_oculto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove espaços e deixa emails do cliente oculto todos em minúsculo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::where('email', 'ilike', '% %')->get()->each(function ($user) {
            $user->email = preg_replace('/\s+/', '', $user->email);
            $user->save();
        });

        User::all()->each(function ($user) {
            $user->email = mb_strtolower($user->email);
            $user->save();
        });
    }
}
