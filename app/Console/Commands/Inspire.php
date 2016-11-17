<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:inspire {game?} {--start=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(app()->runningInConsole()){
            $game = $this->argument('game');
            $time = $this->option('start');
            $this->info('game:'. $game);
            $this->question('time:'. $time);
        } else {
            $game = $this->argument('game');
            $time = $this->option('start');
            //dd('game:'.$game.'&time:'.$time);
        }
    }
}
