<?php

namespace App\Console\Commands;

use DB;
use App\User;
use App\Models\Video;
use App\Models\VideoAssisido;
use Illuminate\Console\Command;

class TodosViramOsVideosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:todo_mundo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seta todos os vídeos como vistos para todos os usuários';

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
        DB::beginTransaction();
        try {
            $this->comment(PHP_EOL . 'limpando vídeos assistidos...' . PHP_EOL);
            VideoAssisido::truncate();
            $this->comment(PHP_EOL . 'limpeza concluída' . PHP_EOL);
            $users = User::all();
            $videos = Video::all();
            $this->comment(PHP_EOL . 'Marcando todos os vídeos como assitidos...' . PHP_EOL);
            $users->each(function (User $user) use ($videos) {
                $videos->each(function (Video $video) use ($user) {
                    VideoAssisido::create([
                        'user_id' => $user->id,
                        'video_id' => $video->id,
                    ]);
                });
            });
            DB::commit();
            $this->comment(PHP_EOL . 'Tudo certo!!' . PHP_EOL);
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->comment(PHP_EOL . 'rapaz... deu bosta viu... ' . PHP_EOL . 'ERROR => ' . $ex->getMessage() . PHP_EOL);
        }
    }
}
