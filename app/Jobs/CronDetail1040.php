<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class CronDetail1040 extends Job implements ShouldQueue
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
        $source = array(0, 1, 100, 999); //0 web，1 下載，100 排除掛機, 999 = apk

        foreach ($source as $key => $value)
        {
            if ($value == 0) {
                $sqlSource = " AND SOURCE IN (0,2)";
            } else if ($value == 999) {
                $sqlSource = " AND SOURCE = 1040";
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
            FROM `CGRECORD`.`GPOINT_BANK1040`
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
        //             WHERE `GAMENO`='40'
        //             AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'";
        // $row_MAX = DB::select($SQL_MAX)[0];
        //$MAX_USER_COUNT = is_null($row_MAX->MAX_USER_COUNT) ? 0 : $row_MAX->MAX_USER_COUNT;
        $MAX_USER_COUNT = 0;

        //進BONUS模式次數
        $SQL_BONUS = "SELECT COUNT( CASE WHEN (`INCOME` > 0 OR `F_INCOME` > 0) THEN  `NO` END ) AS  `BONUS_CNT` ,
                        SUM( CASE WHEN `INCOME` = 0 AND `F_INCOME` = 0 AND `EXPENDITURE` > 0 THEN  `EXPENDITURE` END ) AS  `BONUS_EXPENDITURE`,
                        COUNT( CASE WHEN `ITEM` = 10 AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN  `NO` END ) AS  `BONUS_EIGHT_CNT` ,
                        SUM( CASE WHEN `ITEM` = 10 AND `INCOME` = 0 AND `F_INCOME` = 0 AND `EXPENDITURE` > 0 THEN  `EXPENDITURE` END ) AS  `BONUS_EIGHT_EXPENDITURE`,
                        COUNT( CASE WHEN `ITEM` = 11 AND (`INCOME` > 0 OR `F_INCOME` > 0) AND LOCATE('#',`PS`) = 0 THEN  `NO` END ) AS  `BONUS_LUCKY_CNT` ,
                        SUM( CASE WHEN `ITEM` = 11 AND `INCOME` = 0 AND `F_INCOME` = 0 AND `EXPENDITURE` > 0 AND LOCATE('#',`PS`) = 0 THEN  `EXPENDITURE` END ) AS  `BONUS_LUCKY_EXPENDITURE`,
                        COUNT( CASE WHEN `ITEM` = 11 AND (`INCOME` > 0 OR `F_INCOME` > 0) AND LOCATE('#',`PS`) <> 0 THEN  `NO` END ) AS  `BONUS_LUCKY2_CNT` ,
                        SUM( CASE WHEN `ITEM` = 11 AND `INCOME` = 0 AND `F_INCOME` = 0 AND `EXPENDITURE` > 0 AND LOCATE('#',`PS`) <> 0 THEN  `EXPENDITURE` END ) AS  `BONUS_LUCKY2_EXPENDITURE`,
                        COUNT( CASE WHEN `ITEM` = 12 AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN  `NO` END ) AS  `BONUS_FREE_CNT` ,
                        SUM( CASE WHEN `ITEM` = 12 AND `INCOME` =0  AND `F_INCOME` = 0 AND `EXPENDITURE` > 0 THEN  `EXPENDITURE` END ) AS  `BONUS_FREE_EXPENDITURE`
                        FROM CGRECORD.`GPOINT_BANK1040`
                        WHERE `ITEM` in (10,11,12) AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
        $row_BONUS = DB::select($SQL_BONUS)[0];
        $BONUS_CNT = is_null($row_BONUS->BONUS_CNT) ? 0 : $row_BONUS->BONUS_CNT;
        $BONUS_EXPENDITURE = is_null($row_BONUS->BONUS_EXPENDITURE) ? 0 : $row_BONUS->BONUS_EXPENDITURE;
        $BONUS_EIGHT_CNT = is_null($row_BONUS->BONUS_EIGHT_CNT) ? 0 : $row_BONUS->BONUS_EIGHT_CNT;
        $BONUS_EIGHT_EXPENDITURE = is_null($row_BONUS->BONUS_EIGHT_EXPENDITURE) ? 0 : $row_BONUS->BONUS_EIGHT_EXPENDITURE;
        $BONUS_LUCKY_CNT = is_null($row_BONUS->BONUS_LUCKY_CNT) ? 0 : $row_BONUS->BONUS_LUCKY_CNT;
        $BONUS_LUCKY_EXPENDITURE = is_null($row_BONUS->BONUS_LUCKY_EXPENDITURE) ? 0 : $row_BONUS->BONUS_LUCKY_EXPENDITURE;
        $BONUS_LUCKY2_CNT = is_null($row_BONUS->BONUS_LUCKY2_CNT) ? 0 : $row_BONUS->BONUS_LUCKY2_CNT;
        $BONUS_LUCKY2_EXPENDITURE = is_null($row_BONUS->BONUS_LUCKY2_EXPENDITURE) ? 0 : $row_BONUS->BONUS_LUCKY2_EXPENDITURE;
        $BONUS_FREE_CNT = is_null($row_BONUS->BONUS_FREE_CNT) ? 0 : $row_BONUS->BONUS_FREE_CNT;
        $BONUS_FREE_EXPENDITURE = is_null($row_BONUS->BONUS_FREE_EXPENDITURE) ? 0 : $row_BONUS->BONUS_FREE_EXPENDITURE;

        //進BigWin模式次數
        $SQL_BIG = "SELECT count(`NO`) as BIG_CNT,SUM(`EXPENDITURE`) AS BIG_EXPENDITURE
        FROM `CGRECORD`.`GPOINT_BANK1040`
        WHERE CAST((`ITEM` %10) AS UNSIGNED) NOT IN (6,7,8) AND (`INCOME` > 0 OR `F_INCOME` > 0) AND `EXPENDITURE`/`INCOME`>= 8  AND `EXPENDITURE`/`INCOME` < 20
        AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
        $row_BIG = DB::select($SQL_BIG)[0];
        $BIG_CNT = $row_BIG->BIG_CNT;
        //$BIG_EXPENDITURE = $row_BIG->BIG_EXPENDITURE;
        $BIG_EXPENDITURE = is_null($row_BIG->BIG_EXPENDITURE) ? 0 : $row_BIG->BIG_EXPENDITURE;
        //echo $SQL_BIG . '<br>';
        //echo 'ORI BIG_CNT = ' . $BIG_CNT . '; BIG_EXPENDITURE = ' . $BIG_EXPENDITURE . '<br>';
        //BigWin還要加上FREE SPIN的部分
        $bigFreeSpin = "SELECT `ID`,`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1040`
        WHERE `ITEM` in (10,11,12) AND (`INCOME` > 0 OR `F_INCOME` > 0)
        AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
        //echo $bigFreeSpin . '<br>';
        $row_bigFreeSpin = DB::select($bigFreeSpin);
        if (count($row_bigFreeSpin) > 0) {
            foreach ($row_bigFreeSpin as $bKey => $bValue) {
                switch ($bValue['ITEM']) {
                    case '12':
                        //FREE SPIN6次
                        $bigFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1040`
                        WHERE `ID`='" . $bValue['ID'] . "' AND `ITEM`='" . $bValue['ITEM'] . "' AND `INCOME`='0' AND `F_INCOME`='0'
                        AND `NO` between " . ($bValue['NO'] + 1) . " AND " . ($bValue['NO'] + 6);
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
                        break;
                    default:
                        //幸運輪跟直接發八倍都只有一筆記錄
                        $bigFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1040`
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
                        break;
                }
            }
        }

        //進MEGAWin模式次數
        $SQL_MEGA = "SELECT count(`NO`) as MEGA_CNT,SUM(`EXPENDITURE`) AS MEGA_EXPENDITURE
        FROM `CGRECORD`.`GPOINT_BANK1040`
        WHERE CAST((`ITEM` %10) AS UNSIGNED) NOT IN (6,7,8) AND (`INCOME` > 0 OR `F_INCOME` > 0) AND `EXPENDITURE`/`INCOME` >= 20
        AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
        $row_MEGA = DB::select($SQL_MEGA)[0];
        $MEGA_CNT = $row_MEGA->MEGA_CNT;
        $MEGA_EXPENDITURE = is_null($row_MEGA->MEGA_EXPENDITURE) ? 0 : $row_MEGA->MEGA_EXPENDITURE;
        //echo $SQL_MEGA . '<br>';
        //echo 'ORI MEGA_CNT = ' . $MEGA_CNT . '; MEGA_EXPENDITURE = ' . $MEGA_EXPENDITURE . '<br>';
        //MEGAWIN還要加上FREE SPIN的部分
        $megaFreeSpin = "SELECT `ID`,`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1040`
        WHERE `ITEM` in (10,11,12) AND (`INCOME` > 0 OR `F_INCOME` > 0)
        AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
        //echo $megaFreeSpin . '<br>';
        $row_megaFreeSpin = DB::select($megaFreeSpin);
        if (count($row_megaFreeSpin) > 0) {
            foreach ($row_megaFreeSpin as $mgKey => $mgValue) {
                switch ($mgValue['ITEM']) {
                    case '12':
                        //FREE SPIN6次
                        $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1040`
                        WHERE `ID`='" . $mgValue['ID'] . "' AND `ITEM`='" . $mgValue['ITEM'] . "' AND `INCOME`='0' AND `F_INCOME`='0'
                        AND `NO` between " . ($mgValue['NO'] + 1) . " AND " . ($mgValue['NO'] + 6);
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
                        break;
                    default:
                        //幸運輪跟直接發八倍都只有一筆記錄
                        $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1040`
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
                        break;
                }
            }
        }

        //送出連線彩的次數,總彩金
        $SQL_JP = " SELECT count(*) AS JP_CNT,
                    SUM(EXPENDITURE) AS JP_EXPENDITURE,
                    COUNT(CASE WHEN `ITEM` = 6 THEN `NO` END) as `JP1_CNT`,
                    COUNT(CASE WHEN `ITEM` = 7 THEN `NO` END) as `JP5_CNT`,
                    COUNT(CASE WHEN `ITEM` = 8 THEN `NO` END) as `JP50_CNT`
                    FROM `CGRECORD`.`GPOINT_BANK1040`
                    WHERE CAST((`ITEM` %10) AS UNSIGNED) IN (6,7,8)
                    AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                    " . $sqlSource;
        $row_JP = DB::select($SQL_JP)[0];
        $JP_CNT = is_null($row_JP->JP_CNT) ? 0 : $row_JP->JP_CNT;
        $JP1_CNT = is_null($row_JP->JP1_CNT) ? 0 : $row_JP->JP1_CNT;
        $JP5_CNT = is_null($row_JP->JP5_CNT) ? 0 : $row_JP->JP5_CNT;
        $JP50_CNT = is_null($row_JP->JP50_CNT) ? 0 : $row_JP->JP50_CNT;
        $JP_EXPENDITURE = is_null($row_JP->JP_EXPENDITURE) ? 0 : $row_JP->JP_EXPENDITURE;

        //不含連線彩的投注額彩金
        $SQL_NOJP = "SELECT SUM(EXPENDITURE) AS NOJP_EXPENDITURE
                    FROM `CGRECORD`.`GPOINT_BANK1040`
                    WHERE CAST((`ITEM` %10) AS UNSIGNED) NOT IN (6,7,8)
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
            FROM CGRECORD.`GPOINT_BANK1040`
            WHERE TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
            AND CAST((`ITEM` %10) AS UNSIGNED) NOT IN (6,7,8) AND (`INCOME` > 0 OR `F_INCOME` > 0)  AND `EXPENDITURE` / `INCOME` >= 20" . $sqlSource;
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

        //MEGAWIN還要加上BONUS GAME的部分
        $megaFreeSpin = "SELECT `ID`,`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1040`
        WHERE `ITEM` in (10,11,12) AND (`INCOME` > 0 OR `F_INCOME` > 0)
        AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
        //echo $megaFreeSpin . '<br>';
        $row_megaFreeSpin = DB::select($megaFreeSpin);
        if (count($row_megaFreeSpin) > 0) {
            foreach ($row_megaFreeSpin as $mgKey => $mgValue) {
                switch ($mgValue['ITEM']) {
                    case '12':
                        //FREE SPIN6次
                        $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1040`
                        WHERE `ID`='" . $mgValue['ID'] . "' AND `ITEM`='" . $mgValue['ITEM'] . "' AND `INCOME`='0' AND `F_INCOME`='0'
                        AND `NO` between " . ($mgValue['NO'] + 1) . " AND " . ($mgValue['NO'] + 6);
                        //echo $megaFreeSpinEx . '<br>';
                        $row_megaFreeSpinEx = DB::select($megaFreeSpinEx);
                        break;
                    default:
                        //幸運輪跟直接發八倍都只有一筆記錄
                        $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1040`
                        WHERE `ID`='" . $mgValue['ID'] . "' AND `ITEM`='" . $mgValue['ITEM'] . "' AND `INCOME`='0' AND `F_INCOME`='0'
                        AND `NO` = " . ($mgValue['NO'] + 1);
                        //echo $megaFreeSpinEx . '<br>';
                        $row_megaFreeSpinEx = DB::select($megaFreeSpinEx);
                        break;
                }

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
        $replace1040 = "REPLACE INTO `CGBACKEND`.`sologame_1040_datas` SET `date` = '" . $this->date . "',
        `SOURCE` = '" . $value . "',
        `PLAYERCNT` = '" . $PLAYERCNT . "',
        `INCOME` = '" . $INCOME . "',
        `F_INCOME` = '" . $F_INCOME . "',
        `EXPENDITURE` = '" . $EXPENDITURE . "',
        `CNT` = '" . $CNT . "',
        `JP_CNT` = '" . $JP_CNT . "',
        `JP50_CNT` = '" . $JP50_CNT . "',
        `JP5_CNT` = '" . $JP5_CNT . "',
        `JP1_CNT` = '" . $JP1_CNT . "',
        `JP_EXPENDITURE` = '" . $JP_EXPENDITURE . "',
        `NOJP_EXPENDITURE` = '" . $NOJP_EXPENDITURE . "',
        `MAX_USER_COUNT` = '" . $MAX_USER_COUNT . "',
        `BONUS_CNT` = '" . $BONUS_CNT . "',
        `BONUS_EXPENDITURE` = '" . $BONUS_EXPENDITURE . "',
        `BONUS_LUCKY_CNT` = '" . $BONUS_LUCKY_CNT . "',
        `BONUS_LUCKY_EXPENDITURE` = '" . $BONUS_LUCKY_EXPENDITURE . "',
        `BONUS_LUCKY2_CNT` = '" . $BONUS_LUCKY2_CNT . "',
        `BONUS_LUCKY2_EXPENDITURE` = '" . $BONUS_LUCKY2_EXPENDITURE . "',
        `BONUS_FREE_CNT` = '" . $BONUS_FREE_CNT . "',
        `BONUS_FREE_EXPENDITURE` = '" . $BONUS_FREE_EXPENDITURE . "',
        `BONUS_EIGHT_CNT` = '" . $BONUS_EIGHT_CNT . "',
        `BONUS_EIGHT_EXPENDITURE` = '" . $BONUS_EIGHT_EXPENDITURE . "',
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

        //echo '<br>' . $replace1040 . '<br>';
        DB::insert($replace1040);
        }
    }
}
