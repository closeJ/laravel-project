<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SoloGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:sologame {game?} {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $game = $this->argument('game');
        $date = $this->option('date');
        if (!empty($date)) { //手動執行
            $gameNo = empty($game) ? 'all' : $game;
            switch ($gameNo)
            {
                case 'all':
                    $job = (new \App\Jobs\CronDispBankGame($date));
                    $job2 = (new \App\Jobs\CronDispGame($date,$gameNo,0));
                    $job2_1 = (new \App\Jobs\CronDispGame($date,$gameNo,1));
                    $job2_2 = (new \App\Jobs\CronDispGame($date,$gameNo,100));
                    $job2_3 = (new \App\Jobs\CronDispGame($date,$gameNo,999));
                    $job3 = (new \App\Jobs\CronDetail1040($date));
                    $job4 = (new \App\Jobs\CronDetail1045($date));
                    $job5 = (new \App\Jobs\CronDetail1047($date));
                    $job6 = (new \App\Jobs\CronDetail1049($date));
                    break;
                case '1040':
                    $job = (new \App\Jobs\CronDetail1040($date));
                    break;
                case '1047':
                    $job = (new \App\Jobs\CronDetail1047($date));
                    break;
                case '1036':
                    $job = (new \App\Jobs\CronMega1036($date));
                    break;
                case '1045':
                    $job = (new \App\Jobs\CronDetail1045($date));
                    break;
                case '1049':
                    $job = (new \App\Jobs\CronDetail1049($date));
                    break;
            }
        }

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $job =  (new \App\Jobs\CronDispBankGame($yesterday));
        $job2 = (new \App\Jobs\CronDispGame($yesterday,'all',0));
        $job2_1 = (new \App\Jobs\CronDispGame($yesterday,'all',1));
        $job2_2 = (new \App\Jobs\CronDispGame($yesterday,'all',100));
        $job2_3 = (new \App\Jobs\CronDispGame($yesterday,'all',999));
        // $job3 = (new \App\Jobs\CronDetail1036($yesterday));
        // $job4 = (new \App\Jobs\CronDetail1040($yesterday));
        // $job5 = (new \App\Jobs\CronDetail1045($yesterday));
        // $job6 = (new \App\Jobs\CronDetail1047($yesterday));
        // $job7 = (new \App\Jobs\CronDetail1049($yesterday));
        dispatch($job);
        dispatch($job2);
        dispatch($job2_1);
        dispatch($job2_2);
        dispatch($job2_3);
        // dispatch($job3);
        // dispatch($job4);
        // dispatch($job5);
        // dispatch($job6);
        // dispatch($job7);

        // if(app()->runningInConsole()){
        //     $game = $this->argument('game');
        //     $date = $this->option('date');
        //     $this->info($game);
        //     $this->line($date);
        // } else {
        //     $game = $this->argument('game');
        //     $date = $this->option('date');
        //     dd($date);
        // }
    }
}
