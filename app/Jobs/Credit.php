<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\CompanyData;

class Credit extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start_date = $this->date.' 00:00:00';
        $end_date = $this->date.' 23:59:59';
        $trade_data = CompanyData::where('trader','!=','')->whereBetween('updated_at',[$start_date,$end_date])->get();
    }
}
