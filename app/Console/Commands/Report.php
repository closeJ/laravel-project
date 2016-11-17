<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:report';

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
        // $start_time = date('Y-m-d', strtotime('-1 day')).' 00:00:00';
        // $end_time = date('Y-m-d', strtotime('-1 day')).' 23:59:59';
        // (empty($start_date)) ? $start_time : $start_date;
        // (empty($end_date)) ? $end_time : $end_date;
        // if (empty($start_date) && empty($end_date)) {
        //     $time = '2016-09-10 00:00:00';
        //     $time_d = '2016-09-10 23:59:59';
        // } else {
        //     $time = $start_date;
        //     $time_d = $end_date;
        // }
        $time = '2016-09-10 00:00:00';
        $time_d = '2016-09-10 23:59:59';
        $job = (new \App\Jobs\GameReportJob($time,$time_d));
        dispatch($job);
    }
}
