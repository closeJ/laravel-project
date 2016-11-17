<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class gameControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:gameControl';

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
        //
		//$yesterday = date('Y-m-d', strtotime('-1 day'));
        $job = (new \App\Jobs\gameControl());
        dispatch($job);
    }
}
