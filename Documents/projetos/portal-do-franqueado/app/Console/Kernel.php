<?php

namespace App\Console;

use App\Console\Commands\RemoveTrial;
use App\Console\Commands\SetTrial;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\LimparCuponsVencidos;
use App\Console\Commands\TodosViramOsVideosCommand;
use App\Console\Commands\CorrecaoEmailsClienteOculto;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        LimparCuponsVencidos::class,
        TodosViramOsVideosCommand::class,
        CorrecaoEmailsClienteOculto::class,
        SetTrial::class,
        RemoveTrial::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('queue:work --tries=3')
            ->sendOutputTo('/tmp/schedule-log.log')
            ->everyMinute();
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
