<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class CronDetail1036 extends Job implements ShouldQueue
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
            FROM CGRECORD.`GPOINT_BANK1036`
            WHERE TIME BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
            AND (`ITEM` < 3 OR (`ITEM` >= 10 AND `ITEM` < 13)) AND `INCOME` > 0  AND `EXPENDITURE` / `INCOME` >= 20";
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

        //MEGAWIN還要加上FREE SPIN的部分
        $megaFreeSpin = "SELECT `ID`,`NO`,`INCOME` FROM `CGRECORD`.`GPOINT_BANK1036`
        WHERE `ITEM` >= 10
        AND `TIME` BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59' ";
        //echo $megaFreeSpin . '<br>';
        $row_megaFreeSpin = DB::select($megaFreeSpin);
        if (count($row_megaFreeSpin) > 0) {
            foreach ($row_megaFreeSpin as $mgKey => $mgValue) {
                $megaFreeSpinEx = "SELECT `EXPENDITURE` FROM `CGRECORD`.`GPOINT_BANK1036` WHERE `ID`='" . $mgValue['ID'] . "' AND `ITEM`='0' AND `INCOME`='0'
                AND `F_INCOME`='0' AND `NO` between " . ($mgValue['NO'] + 1) . " AND " . ($mgValue['NO'] + 6);
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
        $replaceMega1036 = "REPLACE INTO `CGBACKEND`.`sologame_1036_mega` SET `DATE` = '" . $this->date . "',
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

        //echo $replaceMega1036 . "<br><hr>";
        DB::insert($replaceMega1036);

        //echo "\n runGameMega36() OK!\n";
    }
}
