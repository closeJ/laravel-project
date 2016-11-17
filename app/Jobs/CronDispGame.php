<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class CronDispGame extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $game;
    protected $date;
    protected $source;
    public function __construct($date,$game,$source)
    {
        $this->game = $game;
        $this->date = $date;
        $this->source = $source;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $runGames = [];
        $pvcGames = config('game.gameName');
        $srh = '';
        if ($this->game == 'all') {
            foreach ($pvcGames as $gameNo => $x) {
                $runGames[] = $gameNo;
            }
        } elseif (array_key_exists($this->game, $pvcGames)) {
            $runGames[] = $gameNo;
        } else {
            echo '※ ERROR：parameter $gameNo goes wrong!!!';
            exit;
        }

        foreach ($runGames as $gameNo) {
           $dbTable = 'CGRECORD.GPOINT_BANK' . $gameNo;

            $qDistId = "SELECT COUNT( DISTINCT(ID) ) AS distId
                    FROM $dbTable
                    WHERE TIME BETWEEN '$this->date 00:00:00' AND '$this->date 23:59:59'";

            if ($this->source == '0') {
                $qDistId .= ' AND (SOURCE = "0" or SOURCE = "2")';
            } else if ($this->source == '999') {
               $qDistId .= ' AND SOURCE = ' . $gameNo;
            } else {
                $qDistId .= ' AND SOURCE = ' . $this->source;
            }
            //echo $qDistId . '<br>';
            $row = DB::select($qDistId)[0];
            // 不重複帳號
            $insDistId = is_null($row->distId) ? 0 : $row->distId;

                if ($this->source == '0') {
                    $query = "
                        SELECT
                            COUNT(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) AS playRound,
                            SUM( INCOME ) AS totalIncome,
                            SUM( F_INCOME ) AS totalFIncome,
                            SUM( EXPENDITURE ) AS totalExpenditure
                        FROM
                            $dbTable
                        WHERE  (( SOURCE = '0') or ( SOURCE = '2'))
                            AND TIME BETWEEN '$this->date 00:00:00' AND '$this->date 23:59:59'";
                } else {
                    $query = "
                        SELECT
                            COUNT(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) AS playRound,
                            SUM( INCOME ) AS totalIncome,
                            SUM( F_INCOME ) AS totalFIncome,
                            SUM( EXPENDITURE ) AS totalExpenditure
                        FROM
                            $dbTable
                        WHERE 1
                            AND TIME BETWEEN '$this->date 00:00:00' AND '$this->date 23:59:59'
                        ";
                    if ($this->source == '999') {
                        $query .= ' AND SOURCE = ' . $gameNo;
                    } else {
                        $query .= ' AND SOURCE = ' . $this->source;
                    }
                }
            //echo 'query = ' . $query . '<br>';
            //echo '<!--query = </br>'.$query.'<p>-->';
            $row = DB::select($query)[0];
            // 玩的局數
            $insPlayRound = is_null($row->playRound) ? 0 : $row->playRound;
            // 總投注
            $insIncome = is_null($row->totalIncome) ? 0 : $row->totalIncome;
            // 贈幣總投注
            $insFIncome = is_null($row->totalFIncome) ? 0 : $row->totalFIncome;
            // 總彩金(玩家贏的錢)
            $insExpenditure = is_null($row->totalExpenditure) ? 0 : $row->totalExpenditure;

            $newsource = 0;

            switch ($this->source) {
                case 2:
                    $this->source = '0';
                    break;
            }

            $replace = "REPLACE INTO CGRECORD.DISP_BANKGAME
                        SET
                        GAMENO = $gameNo,
                        DATE = '$this->date',
                        PLAYERCNT = $insDistId,
                        CNT = $insPlayRound,
                        INCOME = $insIncome,
                        F_INCOME = $insFIncome,
                        EXPENDITURE = $insExpenditure,
                        SOURCE = $this->source";

            //echo 'replace = </br>' . $replace . '<p>';
            DB::insert($replace);

            // neon party 1036
            if ($gameNo == '1036') {
                if ($this->source == 0) {
                    $srh = " AND SOURCE IN (0,2)";
                } else if ($this->source == '999') {
                    $srh .= " AND SOURCE='" . $gameNo . "'";
                } else {
                    $srh = " AND SOURCE='" . $this->source . "'";
                }

                $replace_add = '';

                if ($gameNo == 1036) {
                    //最高在線人數 -- 無法區分WEB或DOWN版 只有TOTAL
                    // $SQL_MAX = "SELECT MAX(AREAG + AREAP + AREAT) AS MAX_USER_COUNT
                    //             FROM `CGRECORD`.`USER_COUNT_INGAME`
                    //             WHERE `GAMENO`='36'
                    //             AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'";
                    // $row_MAX = DB::select($SQL_MAX)[0];
                     $replace_add .= ", MAX_USER_COUNT = 0";

                    //進BONUS模式次數
                    $SQL_BONUS = "SELECT count(*) as BONUS_CNT
                                  FROM " . $dbTable . "
                                  WHERE `ITEM` >= 10
                                  AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                                  " . $srh;
                    $row_BONUS = DB::select($SQL_BONUS)[0];
                    $replace_add .= ", BONUS_CNT = '" . $row_BONUS->BONUS_CNT . "'";

                    //進BONUS模式總彩金
                    $SQL_BONUS_EXPENDITURE = "SELECT SUM(`EXPENDITURE`) AS BONUS_EXPENDITURE
                    FROM " . $dbTable . "
                    WHERE `INCOME` = 0 AND `EXPENDITURE` > 0
                    AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $srh;
                    $row_BONUS_EXPENDITURE = DB::select($SQL_BONUS_EXPENDITURE)[0];
                    $replace_add .= ", BONUS_EXPENDITURE = '" . $row_BONUS_EXPENDITURE->BONUS_EXPENDITURE . "'";

                    //進BigWin模式次數
                    $SQL_BIG = "SELECT count(`NO`) as BIG_CNT,SUM(`EXPENDITURE`) AS BIG_EXPENDITURE
                    FROM " . $dbTable . "
                    WHERE (`ITEM` < 3 OR (`ITEM` >= 10 AND `ITEM` < 13)) AND `INCOME` > 0 AND `EXPENDITURE`/`INCOME`>= 8  AND `EXPENDITURE`/`INCOME` < 20
                    AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $srh;
                    $row_BIG = DB::select($SQL_BIG)[0];
                    $BIG_CNT = $row_BIG->BIG_CNT;
                    $BIG_EXPENDITURE = $row_BIG->BIG_EXPENDITURE;
                    //echo $SQL_BIG . '<br>';
                    //echo 'ORI BIG_CNT = ' . $BIG_CNT . '; BIG_EXPENDITURE = ' . $BIG_EXPENDITURE . '<br>';
                    //BigWin還要加上FREE SPIN的部分
                    $bigFreeSpin = "SELECT `ID`,`NO`,`INCOME` FROM " . $dbTable . "
                    WHERE `ITEM` >= 10
                    AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $srh;
                    //echo $bigFreeSpin . '<br>';
                    $row_bigFreeSpin = DB::select($bigFreeSpin);
                    if (count($row_bigFreeSpin) > 0) {
                        foreach ($row_bigFreeSpin as $bKey => $bValue) {
                            $bigFreeSpinEx = "SELECT `EXPENDITURE` FROM " . $dbTable . " WHERE `ID`='" . $bValue['ID'] . "' AND `ITEM`='0' AND `INCOME`='0'
                AND `F_INCOME`='0' AND `NO` between " . ($bValue['NO'] + 1) . " AND " . ($bValue['NO'] + 6);
                            //echo $bigFreeSpinEx . '<br>';
                            $row_bigFreeSpinEx = DB::select($bigFreeSpinEx);
                            if (count($row_bigFreeSpinEx) > 0) {
                                $OriExpenditure = 0;
                                foreach ($row_bigFreeSpinEx as $bigExKey => $bigExValue) {
                                    $OriExpenditure = $OriExpenditure + $bigExValue['EXPENDITURE'];
                                }
                                if (($OriExpenditure / $bValue['INCOME']) >= 8 && ($OriExpenditure / $bValue['INCOME']) < 20) {
                                    $BIG_CNT = $BIG_CNT + 1;
                                    $BIG_EXPENDITURE = $BIG_EXPENDITURE + $OriExpenditure;
                                    //echo 'EXPENDITURE = ' . $OriExpenditure . '; INCOME = ' . $bValue['INCOME'] . '<br>';
                                    //echo 'BIG_CNT = ' . $BIG_CNT . '; BIG_EXPENDITURE = ' . $BIG_EXPENDITURE . '<br>';
                                }
                            }
                        }
                    }
                    $replace_add .= ", BIG_CNT = '" . $BIG_CNT . "'
                    , BIG_EXPENDITURE = '" . $BIG_EXPENDITURE . "'";

                    //進MEGAWin模式次數
                    $SQL_MEGA = "SELECT count(`NO`) as MEGA_CNT,SUM(`EXPENDITURE`) AS MEGA_EXPENDITURE
                    FROM " . $dbTable . "
                    WHERE (`ITEM` < 3 OR (`ITEM` >= 10 AND `ITEM` < 13)) AND `INCOME` > 0 AND `EXPENDITURE`/`INCOME` >= 20
                    AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $srh;
                    $row_MEGA = DB::select($SQL_MEGA)[0];
                    $MEGA_CNT = $row_MEGA->MEGA_CNT;
                    $MEGA_EXPENDITURE = $row_MEGA->MEGA_EXPENDITURE;
                    //echo $SQL_MEGA . '<br>';
                    //echo 'ORI MEGA_CNT = ' . $MEGA_CNT . '; MEGA_EXPENDITURE = ' . $MEGA_EXPENDITURE . '<br>';
                    //MEGAWIN還要加上FREE SPIN的部分
                    $megaFreeSpin = "SELECT `ID`,`NO`,`INCOME` FROM " . $dbTable . "
                    WHERE `ITEM` >= 10
                    AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $srh;
                    //echo $megaFreeSpin . '<br>';
                    $row_megaFreeSpin = DB::select($megaFreeSpin);
                    if (count($row_megaFreeSpin) > 0) {
                        foreach ($row_megaFreeSpin as $mgKey => $mgValue) {
                            $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM " . $dbTable . " WHERE `ID`='" . $mgValue['ID'] . "' AND `ITEM`='0' AND `INCOME`='0'
                AND `F_INCOME`='0' AND `NO` between " . ($mgValue['NO'] + 1) . " AND " . ($mgValue['NO'] + 6);
                            //echo $megaFreeSpinEx . '<br>';
                            $row_megaFreeSpinEx = DB::select($megaFreeSpinEx);
                            if (count($row_megaFreeSpinEx) > 0) {
                                $OriExpenditure = 0;
                                foreach ($row_megaFreeSpinEx as $exKey => $exValue) {
                                    $OriExpenditure = $OriExpenditure + $exValue['EXPENDITURE'];
                                }
                                if ($OriExpenditure / $mgValue['INCOME'] >= 20) {
                                    $MEGA_CNT = $MEGA_CNT + 1;
                                    $MEGA_EXPENDITURE = $MEGA_EXPENDITURE + $OriExpenditure;
                                    //echo 'EXPENDITURE = ' . $OriExpenditure . '; INCOME = ' . $mgValue['INCOME'] . '<br>';
                                    //echo 'MEGA_CNT = ' . $MEGA_CNT . '; MEGA_EXPENDITURE = ' . $MEGA_EXPENDITURE . '<br>';
                                }
                            }
                        }
                    }
                    $replace_add .= ", MEGA_CNT = '" . $MEGA_CNT . "'
                    , MEGA_EXPENDITURE = '" . $MEGA_EXPENDITURE . "'";

                    //送出連線彩的次數,總彩金
                    $SQL_JP = " SELECT count(*) AS JP_CNT, SUM(EXPENDITURE) AS JP_EXPENDITURE
                                FROM " . $dbTable . "
                                WHERE ((ITEM >= 3 AND ITEM < 10) OR (ITEM >= 13 AND ITEM <= 19))
                                AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                                " . $srh;
                    $row_JP = DB::select($SQL_JP)[0];
                    $replace_add .= ", JP_CNT = '" . $row_JP->JP_CNT . "', JP_EXPENDITURE = '" . $row_JP->JP_EXPENDITURE . "'";

                    //不含連線彩的投注額彩金
                    $SQL_NOJP = "SELECT SUM(EXPENDITURE) AS NOJP_EXPENDITURE
                                FROM " . $dbTable . "
                                WHERE  (`ITEM` < 3 OR (`ITEM` >= 10 AND `ITEM` < 13))
                                AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                                " . $srh;
                    // echo $SQL_NOJP.'<br>';
                    $row_NOJP = DB::select($SQL_NOJP)[0];
                    $replace_add .= ", NOJP_EXPENDITURE = '" . $row_NOJP->NOJP_EXPENDITURE . "'";
                }
                //replace
                $ins = "REPLACE INTO `CGBACKEND`.`sologame_" . $gameNo . "_datas`
                            SET
                            DATE = '" . $this->date . "',
                            SOURCE = '" . $this->source . "',
                            PLAYERCNT = $insDistId,
                            CNT = $insPlayRound,
                            INCOME = $insIncome,
                            F_INCOME = $insFIncome,
                            EXPENDITURE = $insExpenditure
                            " . $replace_add . "
                            ,create_date = now()
                            ";
                DB::insert($ins);
            }
        }
    }
}
