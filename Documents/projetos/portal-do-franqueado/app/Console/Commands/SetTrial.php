<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class SetTrial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial:set {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicia o modo trial do sistema';

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
        $stream = fopen($this->laravel->storagePath() . '/framework/trial', 'w+');
        $date = Carbon::today()->addDays($this->argument('days'));
        fwrite($stream, $date->toDateString());
        fclose($stream);
        $this->comment('Modo trial ativo até ' . $date->format('d/m/Y'));
    }
}
