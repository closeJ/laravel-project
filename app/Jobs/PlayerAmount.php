<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use App\PlayerCount;

class PlayerAmount extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $date;
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
        $games = config('game.gameName');
        $gameData = [];
        $total = 0;
        $datas = [];
        foreach($games as $key => $game) {
            $table = 'CGRECORD.GPOINT_BANK'.$key;
            $gameData = DB::table($table)->whereBetween('TIME',[$start_date,$end_date])->distinct('ID')->count('ID');
            $total += $gameData;
            if(empty($average)) {
                $average = 0;
            } else {
                $average = round(($gameData/$total),2);
            }
            $datas[] = collect([$this->date,$key,$gameData,$average])->all();
        }
        if (count($datas) > 0) {
           foreach ($datas as $data) {
               $playerCount = new PlayerCount;
               $playerCount->date = $data[0];
               $playerCount->gameno = $data[1];
               $playerCount->user_count = $data[2];
               $playerCount->user_count_per = $data[3];
               $playerCount->save();
           }
        }
        dd("執行成功");
    }
}
