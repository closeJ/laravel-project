<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\GameControlService;

class gameControl extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
		echo "start<br>";
		$count_time = microtime(true);		
		$param['nowTime'] = date('Y-m-d H:i');
        $param['nextTime'] = date('Y-m-d H:i', time() + 60);
		$gameNo = [36,40,45,47,49];
		$request=array();
		//$runType=[36=>["Line","Set"],40=>["Line","Set"],45=>["Line","Set"],47=>["Line","Set"],49=>["Line","Set"]];
		$GCS=NEW GameControlService($request);
		foreach ($gameNo as $val){
			$GCS->Line($val,$param);
			$GCS->Set($val,$param);
		}
		echo "end";
		
    }
	
}
