<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\Accessibility;
use DB;
use Carbon\Carbon;

class GameRecord extends Job implements ShouldQueue
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
        $delete = "DELETE FROM `CGBACKEND`.`game_records`
                    WHERE `date` = '{$this->date}'";
        DB::delete($delete);

    $pvcGames_ori = config('game.gameName');
    /* 迴圈各個遊戲 */
    foreach ( $pvcGames_ori as $insGame => $value ) {
        $dbTable = 'CGRECORD.GPOINT_BANK'.$insGame;
        $query = "SELECT `ID` AS id,
                  COUNT(`ID`) AS playCount,
                  SUM(`EXPENDITURE`) AS expenditure,
                  SUM(`INCOME`) AS income,
                  SUM(`INCOME`) - SUM(`EXPENDITURE`) AS win
                  FROM $dbTable
                  WHERE `TIME`
                  BETWEEN '{$this->date} 00:00:00' AND '{$this->date} 23:59:59'
                  GROUP BY id
                  ORDER BY win
                  ";
        //echo '$query = </br>'.$query.'<p>';

        $result = DB::select($query);

        /* 迴圈每一個 id */
            foreach ($result as $row) {
                $accessibility = new Accessibility;
                $username = $accessibility->getPlayerId($row->id);
                //帳號
                $insId = $username;
                //暱稱
                $nickname = $username;
                $expenditure = $row->expenditure;
                // 總投注金額
                $income = $row->income;
                // 輸/贏(NetWin值)
                $insWin = $row->win;
                // 投注次數
                $playCount = $row->playCount;
                // 期望值
                if ( $income <= 0 ) {
                    $insExpection = '0';
                } else {
                    $insExpection = ( round( $expenditure / $income, 4 ) * 100 );
                }
                $yesterday = Carbon::now();
                // 進 database
                $insert = "INSERT INTO `CGBACKEND`.`game_records`
                                 SET `type` = $insGame,
                                         `username` = '$insId',
                                         `nickname` = '$nickname',
                                         `net_win` = '$insWin',
                                         `income` = '$income',
                                         `income_count` = '$playCount',
                                         `expected_value` = '$insExpection',
                                         `date` = '{$this->date}',
                                         `created_at` = '$yesterday',
                                         `updated_at` = '$yesterday'";
                echo '$insert ='.$insert.'<p>';
                DB::insert($insert);

            } /* 迴圈每一個 id */
        } /* 迴圈各個遊戲 */
    }
}
