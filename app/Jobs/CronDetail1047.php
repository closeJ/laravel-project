<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class CronDetail1047 extends Job implements ShouldQueue
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
         //echo "<br>runGame47() Begin!<br>";

        $source = array(0, 1, 100, 999); //0 web，1 下載，100 排除掛機, 999 = apk

        foreach ($source as $key => $value) {
            $sqlSource = '';
            if ($value == 0) {
                $sqlSource = " AND SOURCE IN (0,2)";
            } else if ($value == 999) {
                $sqlSource = " AND SOURCE = 1047";
            } else {
                $sqlSource = " AND SOURCE='1'";
            }

            //基本數據
            $sqlToday = "
                SELECT
                COUNT(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) AS CNT,
                SUM(`INCOME`) AS INCOME,
                SUM(`F_INCOME`) AS F_INCOME,
                SUM(`EXPENDITURE`) AS EXPENDITURE,
                COUNT( DISTINCT(`ID`) ) AS PLAYERCNT
                FROM `CGRECORD`.`GPOINT_BANK1047`
                WHERE TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'" . $sqlSource;
            $row_sqlToday = DB::select($sqlToday)[0];
            //echo '<br>' . $sqlToday . '<br>';
            // 玩的局數
            $CNT = is_null($row_sqlToday->CNT) ? 0 : $row_sqlToday->CNT;
            // 總投注
            $INCOME = is_null($row_sqlToday->INCOME) ? 0 : $row_sqlToday->INCOME;
            // 贈幣總投注
            $F_INCOME = is_null($row_sqlToday->F_INCOME) ? 0 : $row_sqlToday->F_INCOME;
            // 總彩金(玩家贏的錢)
            $EXPENDITURE = is_null($row_sqlToday->EXPENDITURE) ? 0 : $row_sqlToday->EXPENDITURE;
            // 帳號數
            $PLAYERCNT = is_null($row_sqlToday->PLAYERCNT) ? 0 : $row_sqlToday->PLAYERCNT;

            //最高在線人數 -- 無法區分WEB或DOWN版 只有TOTAL
            // $SQL_MAX = "SELECT MAX(AREAG + AREAP + AREAT) AS MAX_USER_COUNT
            //             FROM `CGRECORD`.`USER_COUNT_INGAME`
            //             WHERE `GAMENO`='45'
            //             AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'";
            // $row_MAX = DB::select($SQL_MAX)[0];
            // $MAX_USER_COUNT = is_null($row_MAX->MAX_USER_COUNT) ? 0 : $row_MAX->MAX_USER_COUNT;
            $MAX_USER_COUNT = 0;

            //進SuperSpin模式次數彩金
            $SQL_BONUS = "SELECT COUNT( CASE WHEN (`INCOME` > 0 OR `F_INCOME` > 0) THEN  `NO` END ) AS  `BONUS_CNT` ,
                          SUM( CASE WHEN `INCOME` = 0 AND `F_INCOME` = 0 AND `EXPENDITURE` > 0 THEN  `EXPENDITURE` END ) AS  `BONUS_EXPENDITURE`
                          FROM CGRECORD.`GPOINT_BANK1047`
                          WHERE `ITEM` = 10 AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
            $row_BONUS = DB::select($SQL_BONUS)[0];
            $BONUS_CNT = is_null($row_BONUS->BONUS_CNT) ? 0 : $row_BONUS->BONUS_CNT;
            $BONUS_EXPENDITURE = is_null($row_BONUS->BONUS_EXPENDITURE) ? 0 : $row_BONUS->BONUS_EXPENDITURE;

            //進WildBurst模式次數彩金
            $SQL_WILD = "SELECT COUNT( CASE WHEN (`INCOME` > 0 OR `F_INCOME` > 0) THEN  `NO` END ) AS  `WILD_CNT` ,
                          SUM(`EXPENDITURE`) AS  `WILD_EXPENDITURE`
                          FROM CGRECORD.`GPOINT_BANK1047`
                          WHERE `ITEM` BETWEEN 30 AND 45 AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
            $row_WILD = DB::select($SQL_WILD)[0];
            $WILD_CNT = is_null($row_WILD->WILD_CNT) ? 0 : $row_WILD->WILD_CNT;
            $WILD_EXPENDITURE = is_null($row_WILD->WILD_EXPENDITURE) ? 0 : $row_WILD->WILD_EXPENDITURE;

            //進BigWin模式次數
            $SQL_BIG = "SELECT count(`NO`) as BIG_CNT,SUM(`EXPENDITURE`) AS BIG_EXPENDITURE
            FROM `CGRECORD`.`GPOINT_BANK1047`
            WHERE `ITEM` NOT IN (20 ,21, 22) AND (`INCOME` > 0 OR `F_INCOME` > 0) AND `EXPENDITURE`/`INCOME`>= 8  AND `EXPENDITURE`/`INCOME` < 20
            AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
            $row_BIG = DB::select($SQL_BIG)[0];
            $BIG_CNT = $row_BIG->BIG_CNT;
            $BIG_EXPENDITURE = is_null($row_BIG->BIG_EXPENDITURE) ? 0 : $row_BIG->BIG_EXPENDITURE;
            //echo $SQL_BIG . '<br>';
            //echo 'ORI BIG_CNT = ' . $BIG_CNT . '; BIG_EXPENDITURE = ' . $BIG_EXPENDITURE . '<br>';
            //BigWin還要加上SuperSpin的部分
            $bigFreeSpin = "SELECT `ID`,`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1047`
            WHERE `ITEM` = 10 AND (`INCOME` > 0 OR `F_INCOME` > 0)
            AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
            //echo $bigFreeSpin . '<br>';
            $row_bigFreeSpin = DB::select($bigFreeSpin);
            if (count($row_bigFreeSpin) > 0) {
                foreach ($row_bigFreeSpin as $bKey => $bValue) {
                    $bigFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1047`
                    WHERE `ID`='" . $bValue['ID'] . "' AND `ITEM`='" . $bValue['ITEM'] . "' AND `INCOME`='0' AND `F_INCOME`='0'
                    AND `NO` = " . ($bValue['NO'] + 1);
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

            //進MEGAWin模式次數
            $SQL_MEGA = "SELECT count(`NO`) as MEGA_CNT,SUM(`EXPENDITURE`) AS MEGA_EXPENDITURE
            FROM `CGRECORD`.`GPOINT_BANK1047`
            WHERE `ITEM` NOT IN (20 ,21, 22) AND (`INCOME` > 0 OR `F_INCOME` > 0) AND `EXPENDITURE`/`INCOME` >= 20
            AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
            $row_MEGA = DB::select($SQL_MEGA)[0];
            $MEGA_CNT = $row_MEGA->MEGA_CNT;
            $MEGA_EXPENDITURE = is_null($row_MEGA->MEGA_EXPENDITURE) ? 0 : $row_MEGA->MEGA_EXPENDITURE;
            //echo $SQL_MEGA . '<br>';
            //echo 'ORI MEGA_CNT = ' . $MEGA_CNT . '; MEGA_EXPENDITURE = ' . $MEGA_EXPENDITURE . '<br>';
            //MEGAWIN還要加上SuperSpin的部分
            $megaFreeSpin = "SELECT `ID`,`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1047`
            WHERE `ITEM` = 10 AND (`INCOME` > 0 OR `F_INCOME` > 0)
            AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
            //echo $megaFreeSpin . '<br>';
            $row_megaFreeSpin = DB::select($megaFreeSpin);
            if (count($row_megaFreeSpin) > 0) {
                foreach ($row_megaFreeSpin as $mgKey => $mgValue) {
                    $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1047`
                    WHERE `ID`='" . $mgValue['ID'] . "' AND `ITEM`='" . $mgValue['ITEM'] . "' AND `INCOME`='0' AND `F_INCOME`='0'
                    AND `NO` = " . ($mgValue['NO'] + 1);
                    //echo $free_times . '--' . $megaFreeSpinEx . '<br>';
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

            //送出連線彩的次數,總彩金
            $SQL_JP = " SELECT count(`NO`) AS JP_CNT,
                        SUM(EXPENDITURE) AS JP_EXPENDITURE,
                        COUNT(CASE WHEN `ITEM` = 20 THEN `NO` END) as `JP1_CNT`,
                        SUM(CASE WHEN `ITEM` = 20 THEN `EXPENDITURE` END) AS JP1_EXPENDITURE,
                        COUNT(CASE WHEN `ITEM` = 21 THEN `NO` END) as `JP2_CNT`,
                        SUM(CASE WHEN `ITEM` = 21 THEN `EXPENDITURE` END) AS JP2_EXPENDITURE,
                        COUNT(CASE WHEN `ITEM` = 22 THEN `NO` END) as `JP3_CNT`,
                        SUM(CASE WHEN `ITEM` = 22 THEN `EXPENDITURE` END) AS JP3_EXPENDITURE
                        FROM `CGRECORD`.`GPOINT_BANK1047`
                        WHERE `ITEM` IN (20,21,22)
                        AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                        " . $sqlSource;
            $row_JP = DB::select($SQL_JP)[0];
            $JP_CNT = is_null($row_JP->JP_CNT) ? 0 : $row_JP->JP_CNT;
            $JP1_CNT = is_null($row_JP->JP1_CNT) ? 0 : $row_JP->JP1_CNT;
            $JP2_CNT = is_null($row_JP->JP2_CNT) ? 0 : $row_JP->JP2_CNT;
            $JP3_CNT = is_null($row_JP->JP3_CNT) ? 0 : $row_JP->JP3_CNT;
            $JP_EXPENDITURE = is_null($row_JP->JP_EXPENDITURE) ? 0 : $row_JP->JP_EXPENDITURE;
            $JP1_EXPENDITURE = is_null($row_JP->JP1_EXPENDITURE) ? 0 : $row_JP->JP1_EXPENDITURE;
            $JP2_EXPENDITURE = is_null($row_JP->JP2_EXPENDITURE) ? 0 : $row_JP->JP2_EXPENDITURE;
            $JP3_EXPENDITURE = is_null($row_JP->JP3_EXPENDITURE) ? 0 : $row_JP->JP3_EXPENDITURE;

            //不含連線彩的投注額彩金
            $SQL_NOJP = "SELECT SUM(EXPENDITURE) AS NOJP_EXPENDITURE
                        FROM `CGRECORD`.`GPOINT_BANK1047`
                        WHERE `ITEM` NOT IN (20 ,21, 22)
                        AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                        " . $sqlSource;
            //echo $SQL_NOJP . '<br>';
            $row_NOJP = DB::select($SQL_NOJP)[0];
            $NOJP_EXPENDITURE = is_null($row_NOJP->NOJP_EXPENDITURE) ? 0 : $row_NOJP->NOJP_EXPENDITURE;

            //MEGAWIN倍數資料
            $sqlToday = "
                SELECT
                COUNT(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 20 AND ROUND(`EXPENDITURE`/`INCOME`) <= 40 THEN `EXPENDITURE` END) as `MEGA20_CNT`,
                SUM(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 20 AND ROUND(`EXPENDITURE`/`INCOME`) <= 40 THEN `EXPENDITURE` END) as `MEGA20_EXPENDITURE`,
                COUNT(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 41 AND ROUND(`EXPENDITURE`/`INCOME`) <= 60 THEN `EXPENDITURE` END) as `MEGA41_CNT`,
                SUM(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 41 AND ROUND(`EXPENDITURE`/`INCOME`) <= 60 THEN `EXPENDITURE` END) as `MEGA41_EXPENDITURE`,
                COUNT(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 61 AND ROUND(`EXPENDITURE`/`INCOME`) <= 80 THEN `EXPENDITURE` END) as `MEGA61_CNT`,
                SUM(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 61 AND ROUND(`EXPENDITURE`/`INCOME`) <= 80 THEN `EXPENDITURE` END) as `MEGA61_EXPENDITURE`,
                COUNT(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 81 AND ROUND(`EXPENDITURE`/`INCOME`) <= 100 THEN `EXPENDITURE` END) as `MEGA81_CNT`,
                SUM(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 81 AND ROUND(`EXPENDITURE`/`INCOME`) <= 100 THEN `EXPENDITURE` END) as `MEGA81_EXPENDITURE`,
                COUNT(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 101 THEN `EXPENDITURE` END) as `MEGA101_CNT`,
                SUM(CASE WHEN ROUND(`EXPENDITURE`/`INCOME`) >= 101 THEN `EXPENDITURE` END) as `MEGA101_EXPENDITURE`
                FROM CGRECORD.`GPOINT_BANK1047`
                WHERE TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                AND `ITEM` NOT IN (20,21,22) AND (`INCOME` > 0 OR `F_INCOME` > 0)  AND `EXPENDITURE` / `INCOME` >= 20" . $sqlSource;
            $row_sqlToday = DB::select($sqlToday)[0];
            //echo $sqlToday . "<BR>";
            $MEGA20_CNT = $row_sqlToday->MEGA20_CNT;
            if ($row_sqlToday->MEGA20_EXPENDITURE == null) {
                $MEGA20_EXPENDITURE = 0;
            } else {
                $MEGA20_EXPENDITURE = $row_sqlToday->MEGA20_EXPENDITURE;
            }

            $MEGA41_CNT = $row_sqlToday->MEGA41_CNT;
            if ($row_sqlToday->MEGA41_EXPENDITURE == null) {
                $MEGA41_EXPENDITURE = 0;
            } else {
                $MEGA41_EXPENDITURE = $row_sqlToday->MEGA41_EXPENDITURE;
            }

            $MEGA61_CNT = $row_sqlToday->MEGA61_CNT;
            if ($row_sqlToday->MEGA61_EXPENDITURE == null) {
                $MEGA61_EXPENDITURE = 0;
            } else {
                $MEGA61_EXPENDITURE = $row_sqlToday->MEGA61_EXPENDITURE;
            }

            $MEGA81_CNT = $row_sqlToday->MEGA81_CNT;
            if ($row_sqlToday->MEGA81_EXPENDITURE == null) {
                $MEGA81_EXPENDITURE = 0;
            } else {
                $MEGA81_EXPENDITURE = $row_sqlToday->MEGA81_EXPENDITURE;
            }

            $MEGA101_CNT = $row_sqlToday->MEGA101_CNT;
            if ($row_sqlToday->MEGA101_EXPENDITURE == null) {
                $MEGA101_EXPENDITURE = 0;
            } else {
                $MEGA101_EXPENDITURE = $row_sqlToday->MEGA101_EXPENDITURE;
            }

            //MEGAWIN還要加上SuperSpin的部分
            $megaFreeSpin = "SELECT `ID`,`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1047`
            WHERE `ITEM` = 10 AND (`INCOME` > 0 OR `F_INCOME` > 0)
            AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
            //echo $megaFreeSpin . '<br>';
            $row_megaFreeSpin = DB::select($megaFreeSpin);
            if (count($row_megaFreeSpin) > 0) {
                foreach ($row_megaFreeSpin as $mgKey => $mgValue) {
                    $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1047`
                    WHERE `ID`='" . $mgValue['ID'] . "' AND `ITEM`='" . $mgValue['ITEM'] . "' AND `INCOME`='0' AND `F_INCOME`='0'
                    AND `NO` = " . ($mgValue['NO'] + 1);
                    //echo $megaFreeSpinEx . '<br>';
                    $row_megaFreeSpinEx = DB::select($megaFreeSpinEx);

                    if (count($row_megaFreeSpinEx) > 0) {
                        $OriExpenditure = 0;
                        foreach ($row_megaFreeSpinEx as $exKey => $exValue) {
                            $OriExpenditure = $OriExpenditure + $exValue['EXPENDITURE'];
                        }
                        $exCount = round($OriExpenditure / $mgValue['INCOME']);
                        if ($OriExpenditure / $mgValue['INCOME'] >= 20 && $exCount <= 40) {
                            $MEGA20_CNT = $MEGA20_CNT + 1;
                            $MEGA20_EXPENDITURE = $MEGA20_EXPENDITURE + $OriExpenditure;
                            //echo 'MEGA_CNT = ' . $MEGA20_CNT . '; MEGA_EXPENDITURE = ' . $MEGA20_EXPENDITURE . '<br>';
                        } else if ($exCount >= 41 && $exCount <= 60) {
                            $MEGA41_CNT = $MEGA41_CNT + 1;
                            $MEGA41_EXPENDITURE = $MEGA41_EXPENDITURE + $OriExpenditure;
                        } else if ($exCount >= 61 && $exCount <= 80) {
                            $MEGA61_CNT = $MEGA61_CNT + 1;
                            $MEGA61_EXPENDITURE = $MEGA61_EXPENDITURE + $OriExpenditure;
                        } else if ($exCount >= 81 && $exCount <= 100) {
                            $MEGA81_CNT = $MEGA81_CNT + 1;
                            $MEGA81_EXPENDITURE = $MEGA81_EXPENDITURE + $OriExpenditure;
                        } else if ($exCount >= 101) {
                            $MEGA101_CNT = $MEGA101_CNT + 1;
                            $MEGA101_EXPENDITURE = $MEGA101_EXPENDITURE + $OriExpenditure;
                        }
                    }
                }
            }

            //echo $MEGA20_EXPENDITURE . '<br>';
            $replace1047 = "REPLACE INTO `CGBACKEND`.`sologame_1047_datas` SET `DATE` = '" . $this->date . "',
            `SOURCE` = '" . $value . "',
            `PLAYERCNT` = '" . $PLAYERCNT . "',
            `INCOME` = '" . $INCOME . "',
            `F_INCOME` = '" . $F_INCOME . "',
            `EXPENDITURE` = '" . $EXPENDITURE . "',
            `CNT` = '" . $CNT . "',
            `JP_CNT` = '" . $JP_CNT . "',
            `JP1_CNT` = '" . $JP1_CNT . "',
            `JP2_CNT` = '" . $JP2_CNT . "',
            `JP3_CNT` = '" . $JP3_CNT . "',
            `JP_EXPENDITURE` = '" . $JP_EXPENDITURE . "',
            `JP1_EXPENDITURE` = '" . $JP1_EXPENDITURE . "',
            `JP2_EXPENDITURE` = '" . $JP2_EXPENDITURE . "',
            `JP3_EXPENDITURE` = '" . $JP3_EXPENDITURE . "',
            `NOJP_EXPENDITURE` = '" . $NOJP_EXPENDITURE . "',
            `MAX_USER_COUNT` = '" . $MAX_USER_COUNT . "',
            `BONUS_CNT` = '" . $BONUS_CNT . "',
            `BONUS_EXPENDITURE` = '" . $BONUS_EXPENDITURE . "',
            `WILD_CNT` = '" . $WILD_CNT . "',
            `WILD_EXPENDITURE` = '" . $WILD_EXPENDITURE . "',
            `BIG_CNT` = '" . $BIG_CNT . "',
            `BIG_EXPENDITURE` = '" . $BIG_EXPENDITURE . "',
            `MEGA_CNT` = '" . $MEGA_CNT . "',
            `MEGA_EXPENDITURE` = '" . $MEGA_EXPENDITURE . "',
            `MEGA20_CNT` = '" . $MEGA20_CNT . "',
            `MEGA20_EXPENDITURE` = '" . $MEGA20_EXPENDITURE . "',
            `MEGA41_CNT` = '" . $MEGA41_CNT . "',
            `MEGA41_EXPENDITURE` = '" . $MEGA41_EXPENDITURE . "',
            `MEGA61_CNT` = '" . $MEGA61_CNT . "',
            `MEGA61_EXPENDITURE` = '" . $MEGA61_EXPENDITURE . "',
            `MEGA81_CNT` = '" . $MEGA81_CNT . "',
            `MEGA81_EXPENDITURE` = '" . $MEGA81_EXPENDITURE . "',
            `MEGA101_CNT` = '" . $MEGA101_CNT . "',
            `MEGA101_EXPENDITURE` = '" . $MEGA101_EXPENDITURE . "'
            ,`create_date` = now()";

            //echo '<br>' . $replace1047 . '<br>';
            DB::insert($replace1047);
        }

        //echo "<br>runGame47() OK!<br>";
    }
}
