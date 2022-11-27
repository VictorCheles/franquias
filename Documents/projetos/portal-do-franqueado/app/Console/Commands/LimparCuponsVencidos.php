<?php

namespace App\Console\Commands;

use DB;
use App\Models\Cupom;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class LimparCuponsVencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cupom:limpar {force?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpar os cupons vencidos: use "force" para forçar SIM';

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
    public function limpar(Collection $lista)
    {
        $lista->each(function (Cupom $item) {
            $item->code = null;
            $item->timestamps = false;
            $item->save();
        });
    }

    public function handle()
    {
        $this->comment(PHP_EOL . 'Iniciando contagem...' . PHP_EOL);
        $cupons = collect();
        Cupom::where('status', true)->whereNotNull(DB::raw('code'))->get()->each(function (Cupom $item) use (&$cupons) {
            if ($item->status() == Cupom::STATUS_VENCIDO) {
                $cupons->push($item);
            }
        });

        if ($cupons->count() > 0) {
            if ($this->argument('force')) {
                $this->comment(PHP_EOL . 'Iniciando limpeza...' . PHP_EOL);
                $this->limpar($cupons);
                $this->comment(PHP_EOL . 'Limpeza concluída... ' . PHP_EOL);
            } else {
                if ($this->confirm(sprintf('Existem %d cupons para limpar, deseja continuar? [y|N]', $cupons->count()))) {
                    $this->comment(PHP_EOL . 'Iniciando limpeza...' . PHP_EOL);
                    $this->limpar($cupons);
                    $this->comment(PHP_EOL . 'Limpeza concluída... ' . PHP_EOL);
                } else {
                    $this->comment(PHP_EOL . 'Ação cancelada...' . PHP_EOL);
                }
            }
        } else {
            $this->comment(PHP_EOL . 'Não existem cupons para limpar...' . PHP_EOL);
        }
    }
}
