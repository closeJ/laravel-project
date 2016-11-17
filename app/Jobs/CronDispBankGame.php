<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class CronDispBankGame extends Job implements ShouldQueue
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
    //用來記錄全部類型的實際不重複帳號數
    public function handle()
    {
        //$date = '2015-10-01';
        $timeSql = '';
        $timeSql .= " `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'";
        //all game
        $game = config('game.gameName');
        //每種來源記一筆資料
        $source = array(0, 1, 2, 100, 999); //0 web，1 下載, 2 web+下載+apk，100 排除掛機, 999 = apk
        foreach ($source as $key => $value)
        {
            if ($value == '100') {

                $firstTable = '';
                $SQL = 'SELECT COUNT(`ID`) as `playerCount` FROM (';
                foreach ($game as $gameIndex => $gameValue) {
                    $dbTable = '`CGRECORD`.`GPOINT_BANK' . $gameIndex . '`';

                    $gameSql = "SELECT distinct `ID`
                                FROM " . $dbTable . "
                                WHERE " . $timeSql;

                    if ($firstTable == '') {
                        $SQL .= '(' . $gameSql . ')';

                        $firstTable = 'done';
                    } else {
                        $SQL .= ' UNION (' . $gameSql . ')';
                    }

                }
                $SQL .= ') `allgame`';
                // echo $SQL . '<BR>';
            } else {
                if ($value == 0) {
                    $sqlSource = " AND `SOURCE` IN (0,2)";
                } else if ($value == 1) {
                    $sqlSource = " AND `SOURCE`='1'";
                } else if ($value == 2) {
                    $sqlSource = "";
                }

                $firstTable = '';
                $SQL = 'SELECT COUNT(`ID`) as `playerCount` FROM (';
                foreach ($game as $gameIndex => $gameValue) {
                    if ($value == 999) {
                        $sqlSource = " AND SOURCE='" . $gameIndex . "'";
                    }
                    $dbTable = '`CGRECORD`.`GPOINT_BANK' . $gameIndex . '`';

                    $gameSql = "SELECT distinct `ID`
                                FROM " . $dbTable . "
                                WHERE " . $timeSql . $sqlSource;

                    if ($firstTable == '') {
                        $SQL .= '(' . $gameSql . ')';

                        $firstTable = 'done';
                    } else {
                        $SQL .= ' UNION (' . $gameSql . ')';
                    }

                }
                $SQL .= ') `allgame`';
            }

            $rowSQL = DB::select($SQL)[0];

            if ($rowSQL->playerCount == '') {
                $playerCount = 0;
            } else {
                $playerCount = $rowSQL->playerCount;
            }

            $replaceSQL = "
            REPLACE INTO  `CGRECORD`.`DISP_BANKGAME` (
            `GAMENO` ,
            `DATE` ,
            `PLAYERCNT` ,
            `CNT` ,
            `INCOME` ,
            `F_INCOME` ,
            `EXPENDITURE` ,
            `SOURCE`
            )
            VALUES (
            '0',  '" . $this->date . "',  '" . $playerCount . "',  '0',  '0',  '0',  '0',  '" . $value . "'
            )";

            DB::insert($replaceSQL);
            echo '</br>' . $replaceSQL . '</br>';
        }
    }
}
