<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class CronDetail1049 extends Job implements ShouldQueue
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

        foreach ($source as $key => $value) {
            $sqlSource = '';
            if ($value == 0) {
                $sqlSource = " AND SOURCE IN (0,2)";
            } else if ($value == 999) {
                $sqlSource = " AND SOURCE = 1049";
            } else {
                $sqlSource = " AND SOURCE='1'";
            }

            //基本數據
            // $sqlToday = "
            //     SELECT a.*,b.*
            //     FROM (
            //         SELECT
            //         COUNT(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) AS CNT,
            //         SUM(`INCOME`) AS INCOME,
            //         SUM(`F_INCOME`) AS F_INCOME,
            //         SUM(`EXPENDITURE`) AS EXPENDITURE,
            //         COUNT(CASE WHEN `ITEM` = 3 THEN `NO` END) as JP_CNT,
            //         SUM(CASE WHEN `ITEM` = 3 THEN `EXPENDITURE` END) as JP_EXPENDITURE,
            //         SUM(`EXPENDITURE`) - IFNULL(SUM(CASE WHEN `ITEM` = 3 THEN `EXPENDITURE` END),0) as NOJP_EXPENDITURE,
            //         COUNT( DISTINCT(`ID`) ) AS PLAYERCNT,
            //         COUNT(CASE WHEN `ITEM` IN (10,11,12) AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN `NO` END) as FEATURE_CNT,
            //         COUNT(CASE WHEN `ITEM` = 12 AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN `NO` END) as BONUS_CNT,
            //         SUM(CASE WHEN `ITEM` = 12 AND `INCOME` = 0 AND `F_INCOME` = 0 THEN `EXPENDITURE` END) as BONUS_EXPENDITURE,
            //         COUNT(CASE WHEN `ITEM` = 11 AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN `NO` END) as FREE_CNT,
            //         SUM(CASE WHEN `ITEM` = 11 AND `INCOME` = 0 AND `F_INCOME` = 0 THEN `EXPENDITURE` END) as FREE_EXPENDITURE,
            //         COUNT(CASE WHEN `ITEM` = 10 AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN `NO` END) as EIGHT_CNT,
            //         SUM(CASE WHEN `ITEM` = 10 AND `INCOME` = 0 AND `F_INCOME` = 0 THEN `EXPENDITURE` END) as EIGHT_EXPENDITURE
            //         FROM `CGRECORD`.`GPOINT_BANK1049`
            //         WHERE TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'" . $sqlSource . "
            //     ) a,
            //     (
            //     SELECT MAX(AREAG + AREAP + AREAT) AS MAX_USER_COUNT
            //     FROM `CGRECORD`.`USER_COUNT_INGAME`
            //     WHERE `GAMENO`='49' AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
            //     ) b
            // ";
            $sqlToday = "SELECT
                    COUNT(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) AS CNT,
                    SUM(`INCOME`) AS INCOME,
                    SUM(`F_INCOME`) AS F_INCOME,
                    SUM(`EXPENDITURE`) AS EXPENDITURE,
                    COUNT(CASE WHEN `ITEM` = 3 THEN `NO` END) as JP_CNT,
                    SUM(CASE WHEN `ITEM` = 3 THEN `EXPENDITURE` END) as JP_EXPENDITURE,
                    SUM(`EXPENDITURE`) - IFNULL(SUM(CASE WHEN `ITEM` = 3 THEN `EXPENDITURE` END),0) as NOJP_EXPENDITURE,
                    COUNT( DISTINCT(`ID`) ) AS PLAYERCNT,
                    COUNT(CASE WHEN `ITEM` IN (10,11,12) AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN `NO` END) as FEATURE_CNT,
                    COUNT(CASE WHEN `ITEM` = 12 AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN `NO` END) as BONUS_CNT,
                    SUM(CASE WHEN `ITEM` = 12 AND `INCOME` = 0 AND `F_INCOME` = 0 THEN `EXPENDITURE` END) as BONUS_EXPENDITURE,
                    COUNT(CASE WHEN `ITEM` = 11 AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN `NO` END) as FREE_CNT,
                    SUM(CASE WHEN `ITEM` = 11 AND `INCOME` = 0 AND `F_INCOME` = 0 THEN `EXPENDITURE` END) as FREE_EXPENDITURE,
                    COUNT(CASE WHEN `ITEM` = 10 AND (`INCOME` > 0 OR `F_INCOME` > 0) THEN `NO` END) as EIGHT_CNT,
                    SUM(CASE WHEN `ITEM` = 10 AND `INCOME` = 0 AND `F_INCOME` = 0 THEN `EXPENDITURE` END) as EIGHT_EXPENDITURE
                    FROM `CGRECORD`.`GPOINT_BANK1049`
                    WHERE TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'" . $sqlSource."";
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
            // 送出連線彩次數
            $JP_CNT = is_null($row_sqlToday->JP_CNT) ? 0 : $row_sqlToday->JP_CNT;
            // 送出連線彩彩金
            $JP_EXPENDITURE = is_null($row_sqlToday->JP_EXPENDITURE) ? 0 : $row_sqlToday->JP_EXPENDITURE;
            // 不含連線彩金
            $NOJP_EXPENDITURE = is_null($row_sqlToday->NOJP_EXPENDITURE) ? 0 : $row_sqlToday->NOJP_EXPENDITURE;
            // FEATURE次數
            $FEATURE_CNT = is_null($row_sqlToday->FEATURE_CNT) ? 0 : $row_sqlToday->FEATURE_CNT;
            // 翻翻樂次數
            $BONUS_CNT = is_null($row_sqlToday->BONUS_CNT) ? 0 : $row_sqlToday->BONUS_CNT;
            // 翻翻樂彩金
            $BONUS_EXPENDITURE = is_null($row_sqlToday->BONUS_EXPENDITURE) ? 0 : $row_sqlToday->BONUS_EXPENDITURE;
            // 免費5轉次數
            $FREE_CNT = is_null($row_sqlToday->FREE_CNT) ? 0 : $row_sqlToday->FREE_CNT;
            // 免費5轉彩金
            $FREE_EXPENDITURE = is_null($row_sqlToday->FREE_EXPENDITURE) ? 0 : $row_sqlToday->FREE_EXPENDITURE;
            // 8倍次數
            $EIGHT_CNT = is_null($row_sqlToday->EIGHT_CNT) ? 0 : $row_sqlToday->EIGHT_CNT;
            // 8倍彩金
            $EIGHT_EXPENDITURE = is_null($row_sqlToday->EIGHT_EXPENDITURE) ? 0 : $row_sqlToday->EIGHT_EXPENDITURE;
            // 最大上線人數
            //$MAX_USER_COUNT = is_null($row_sqlToday->MAX_USER_COUNT) ? 0 : $row_sqlToday->MAX_USER_COUNT;
            $MAX_USER_COUNT = 0;

            //進BigWin模式次數
            $SQL_BIG = "SELECT count(`NO`) as BIG_CNT,SUM(`EXPENDITURE`) AS BIG_EXPENDITURE
            FROM `CGRECORD`.`GPOINT_BANK1049`
            WHERE `ITEM` <> 3 AND (`INCOME` > 0 OR `F_INCOME` > 0) AND `EXPENDITURE`/`INCOME`>= 8  AND `EXPENDITURE`/`INCOME` < 20
            AND TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'" . $sqlSource;
            $row_BIG = DB::select($SQL_BIG)[0];
            $BIG_CNT = $row_BIG->BIG_CNT;
            $BIG_EXPENDITURE = is_null($row_BIG->BIG_EXPENDITURE) ? 0 : $row_BIG->BIG_EXPENDITURE;
            //echo $SQL_BIG . '<br>';
            //echo 'ORI BIG_CNT = ' . $BIG_CNT . '; BIG_EXPENDITURE = ' . $BIG_EXPENDITURE . '<br>';
            //BigWin還要加上三種BONUS GAME的部分
            $bigFreeSpin = "SELECT 'ID',`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1049`
            WHERE `ITEM` in (10,11,12) AND (`INCOME` > 0 OR `F_INCOME` > 0)
            AND TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'" . $sqlSource;
            //echo $bigFreeSpin . '<br>';
            $row_bigFreeSpin = DB::select($bigFreeSpin);
            if (count($row_bigFreeSpin) > 0) {
                foreach ($row_bigFreeSpin as $bKey => $bValue) {
                    $bigFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1049`
                    WHERE `ID`='" . $bValue['ID'] . "' AND `ITEM`='" . $bValue['ITEM'] . "' AND `INCOME`='0' AND `F_INCOME`='0' AND `NO` = " . ($bValue['NO'] + 1);
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
            FROM `CGRECORD`.`GPOINT_BANK1049`
            WHERE `ITEM` <> 3 AND (`INCOME` > 0 OR `F_INCOME` > 0) AND `EXPENDITURE`/`INCOME` >= 20
            AND TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'" . $sqlSource;
            $row_MEGA = DB::select($SQL_MEGA)[0];
            $MEGA_CNT = $row_MEGA->MEGA_CNT;
            $MEGA_EXPENDITURE = is_null($row_MEGA->MEGA_EXPENDITURE) ? 0 : $row_MEGA->MEGA_EXPENDITURE;
            //echo $SQL_MEGA . '<br>';
            //echo 'ORI MEGA_CNT = ' . $MEGA_CNT . '; MEGA_EXPENDITURE = ' . $MEGA_EXPENDITURE . '<br>';
            //MEGAWIN還要加上三種BONUS GAME的部分
            $megaFreeSpin = "SELECT `ID`,`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1049`
            WHERE `ITEM` in (10,11,12) AND (`INCOME` > 0 OR `F_INCOME` > 0)
            AND TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'" . $sqlSource;
            //echo $megaFreeSpin . '<br>';
            $row_megaFreeSpin = DB::select($megaFreeSpin);
            if (count($row_megaFreeSpin) > 0) {
                foreach ($row_megaFreeSpin as $mgKey => $mgValue) {
                    $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1049`
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

            //WILD輪數,彩金
            // $SQL_WILD = "SELECT
            //             COUNT( CASE WHEN `Log2` = 1 THEN  `NO` END ) AS  `WILD1_CNT` ,
            //             SUM( CASE WHEN `Log2` = 1 THEN `EXPENDITURE` END ) AS  `WILD1_EXPENDITURE`,
            //             COUNT( CASE WHEN `Log2` = 2 THEN  `NO` END ) AS  `WILD2_CNT` ,
            //             SUM( CASE WHEN `Log2` = 2 THEN `EXPENDITURE` END ) AS  `WILD2_EXPENDITURE`,
            //             COUNT( CASE WHEN `Log2` = 3 THEN  `NO` END ) AS  `WILD3_CNT` ,
            //             SUM( CASE WHEN `Log2` = 3 THEN  `EXPENDITURE` END ) AS  `WILD3_EXPENDITURE`
            //             FROM CGRECORD.`BankAnalysisLog`
            //             WHERE GAMENO=49 AND `Log2` in (1,2,3) AND TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'" . $sqlSource;
            // $row_WILD = DB::select($SQL_WILD)[0];
            // $WILD1_CNT = is_null($row_WILD['WILD1_CNT']) ? 0 : $row_WILD['WILD1_CNT'];
            // $WILD2_CNT = is_null($row_WILD['WILD2_CNT']) ? 0 : $row_WILD['WILD2_CNT'];
            // $WILD3_CNT = is_null($row_WILD['WILD3_CNT']) ? 0 : $row_WILD['WILD3_CNT'];
            // $WILD1_EXPENDITURE = is_null($row_WILD['WILD1_EXPENDITURE']) ? 0 : $row_WILD['WILD1_EXPENDITURE'];
            // $WILD2_EXPENDITURE = is_null($row_WILD['WILD2_EXPENDITURE']) ? 0 : $row_WILD['WILD2_EXPENDITURE'];
            // $WILD3_EXPENDITURE = is_null($row_WILD['WILD3_EXPENDITURE']) ? 0 : $row_WILD['WILD3_EXPENDITURE'];

            $WILD1_CNT = 0;
            $WILD2_CNT = 0;
            $WILD3_CNT = 0;
            $WILD1_EXPENDITURE = 0;
            $WILD2_EXPENDITURE = 0;
            $WILD3_EXPENDITURE = 0;

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
                FROM CGRECORD.`GPOINT_BANK1049`
                WHERE TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                AND `ITEM` <> 3 AND (`INCOME` > 0 OR `F_INCOME` > 0)  AND `EXPENDITURE` / `INCOME` >= 20" . $sqlSource;
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
            $megaFreeSpin = "SELECT `ID`,`NO`,`INCOME`,`ITEM` FROM `CGRECORD`.`GPOINT_BANK1049`
            WHERE `ITEM` in (10,11,12) AND (`INCOME` > 0 OR `F_INCOME` > 0)
            AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' " . $sqlSource;
            //echo $megaFreeSpin . '<br>';
            $row_megaFreeSpin = DB::select($megaFreeSpin);
            if (count($row_megaFreeSpin) > 0) {
                foreach ($row_megaFreeSpin as $mgKey => $mgValue) {
                    $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1049`
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
            $replace1049 = "REPLACE INTO `CGBACKEND`.`sologame_1049_datas` SET `DATE` = '" . $this->date . "',
            `SOURCE` = '" . $value . "',
            `PLAYERCNT` = '" . $PLAYERCNT . "',
            `INCOME` = '" . $INCOME . "',
            `F_INCOME` = '" . $F_INCOME . "',
            `EXPENDITURE` = '" . $EXPENDITURE . "',
            `CNT` = '" . $CNT . "',
            `JP_CNT` = '" . $JP_CNT . "',
            `JP_EXPENDITURE` = '" . $JP_EXPENDITURE . "',
            `NOJP_EXPENDITURE` = '" . $NOJP_EXPENDITURE . "',
            `MAX_USER_COUNT` = '" . $MAX_USER_COUNT . "',
            `WILD1_CNT` = '" . $WILD1_CNT . "',
            `WILD2_CNT` = '" . $WILD2_CNT . "',
            `WILD3_CNT` = '" . $WILD3_CNT . "',
            `WILD1_EXPENDITURE` = '" . $WILD1_EXPENDITURE . "',
            `WILD2_EXPENDITURE` = '" . $WILD2_EXPENDITURE . "',
            `WILD3_EXPENDITURE` = '" . $WILD3_EXPENDITURE . "',
            `FEATURE_CNT` = '" . $FEATURE_CNT . "',
            `BONUS_CNT` = '" . $BONUS_CNT . "',
            `BONUS_EXPENDITURE` = '" . $BONUS_EXPENDITURE . "',
            `FREE_CNT` = '" . $FREE_CNT . "',
            `FREE_EXPENDITURE` = '" . $FREE_EXPENDITURE . "',
            `EIGHT_CNT` = '" . $EIGHT_CNT . "',
            `EIGHT_EXPENDITURE` = '" . $EIGHT_EXPENDITURE . "',
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

            echo '<br>' . $replace1049 . '<br>';
            DB::insert($replace1049);
        }

        //echo "<br>runGame49() OK!<br>";
    }
}
