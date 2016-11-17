<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\GpointBank1036;
use App\GpointBank1040;
use App\GpointBank1045;
use App\GpointBank1047;
use App\GpointBank1049;
use App\GameReport;
use DB;

class GameReportJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $start_date;
    protected $end_date;
    public function __construct($start_date,$end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data1036 = GpointBank1036::selectRaw("count(DISTINCT(`ID`)) user_count ,sum(income) total_come, count(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) count ,sum(expenditure) exp ,count(expenditure > 0) exp_count")->whereBetween('TIME',[$this->start_date,$this->end_date])->get();
        $data1040 = GpointBank1040::selectRaw("count(DISTINCT(`ID`)) user_count,sum(income) total_come,count(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) count ,sum(expenditure) exp ,count(expenditure > 0) exp_count")->whereBetween('TIME',[$this->start_date,$this->end_date])->get();
        $data1045 = GpointBank1045::selectRaw("count(DISTINCT(`ID`)) user_count,sum(income) total_come,count(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) count ,sum(expenditure) exp ,count(expenditure > 0) exp_count")->whereBetween('TIME',[$this->start_date,$this->end_date])->get();
        $data1047 = GpointBank1047::selectRaw("count(DISTINCT(`ID`)) user_count,sum(income) total_come,count(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) count ,sum(expenditure) exp ,count(expenditure) exp_count")->whereBetween('TIME',[$this->start_date,$this->end_date])->get();
        $data1049 = GpointBank1049::selectRaw("count(DISTINCT(`ID`)) user_count,sum(income) total_come,count(CASE WHEN `INCOME` > 0 OR `F_INCOME` > 0 THEN `NO` END) count ,sum(expenditure) exp ,count(expenditure) exp_count")->whereBetween('TIME',[$this->start_date,$this->end_date])->get();

        foreach($data1036 as $neon) {
             $neon_data = [
                $neon['user_count'],
                $neon['total_come'],
                $neon['count'],
                $neon['exp'],
                $neon['exp_count'],
            ];
        }
        foreach($data1040 as $dancer) {
             $dancer_data = [
                $dancer['user_count'],
                $dancer['total_come'],
                $dancer['count'],
                $dancer['exp'],
                $dancer['exp_count'],
            ];
        }
        foreach($data1045 as $neon2) {
             $neon2_data = [
                $neon2['user_count'],
                $neon2['total_come'],
                $neon2['count'],
                $neon2['exp'],
                $neon2['exp_count'],
            ];
        }
        foreach($data1047 as $toi) {
             $toi_data = [
                $toi['user_count'],
                $toi['total_come'],
                $toi['count'],
                $toi['exp'],
                $toi['exp_count'],
            ];
        }
        foreach($data1049 as $spy) {
             $spy_data = [
                $spy['user_count'],
                $spy['total_come'],
                $spy['count'],
                $spy['exp'],
                $spy['exp_count'],
            ];
        }
        $totalInCome = $neon_data[1] + $dancer_data[1] + $neon2_data[1] + $toi_data[1] + $spy_data[1];
        $totalBonus = $neon_data[3] + $dancer_data[3] + $neon2_data[3] + $toi_data[3] + $spy_data[3];
        $totalCountIncome = $neon_data[2] + $dancer_data[2] + $neon2_data[2] + $toi_data[2] + $spy_data[2];
        $totalCountBonus = $neon_data[4] + $dancer_data[4] + $neon2_data[4] + $toi_data[4] + $spy_data[4];

        if ($totalBonus == 0) {
            $expect = 0;
        } else {
            $expect = round(($totalBonus/$totalInCome),2);
        }

        if ($neon_data[1] == 0 || $neon_data[2] == 0 || $neon_data[3] == 0) {
            $user1036_avg = 0;
            $total1036_per = 0;
            $bonus1036_per = 0;
            $bet1036_avg = 0;
            $bonus1036_avg = 0;
        } else {
            $user1036_avg = round($neon_data[2]/$neon_data[0]);
            $total1036_per = round(($neon_data[1]/$totalInCome),1);
            $bonus1036_per = round(($neon_data[3]/$totalBonus),1);
            $bet1036_avg = round(($neon_data[1]/$neon_data[2]),1);
            $bonus1036_avg = round(($neon_data[3]/$neon_data[4]),1);
        }
        $game1036 = [
            "1036",
            $neon_data[0],//不重覆帳號數
            $user1036_avg,//平均投注次數
            $neon_data[1],//單一遊戲總投注額
            $total1036_per,//總投注額占比
            $neon_data[3],//單一遊戲中彩金額
            $bonus1036_per,//總彩金額占比
            ($neon_data[1] - $neon_data[3]), // Net Win (總投注額 - 中彩金額)
            $expect,//期望值(%)
            $neon_data[2],//總投注次數
            $neon_data[4],//中彩總次數
            $bet1036_avg,//平均投注額
            $bonus1036_avg,//平均彩金額
        ];

        if ($dancer_data[1] == 0 || $dancer_data[2] == 0 || $dancer_data[3] == 0) {
            $user1040_avg = 0;
            $total1040_per = 0;
            $bonus1040_per = 0;
            $bet1040_avg = 0;
            $bonus1040_avg = 0;
        } else {
            $user1040_avg = round($dancer_data[2]/$dancer_data[0]);
            $total1040_per = round(($dancer_data[1]/$totalInCome),1);
            $bonus1040_per = round(($dancer_data[3]/$totalBonus),1);
            $bet1040_avg = round(($dancer_data[1]/$dancer_data[2]),1);
            $bonus1040_avg = round(($dancer_data[3]/$dancer_data[4]),1);
        }

        $game1040 = [
            "1040",
            $dancer_data[0],//不重覆帳號數
            $user1040_avg,//平均投注次數
            $dancer_data[1],//單一遊戲總投注額
            $total1040_per,//總投注額占比
            $dancer_data[3],//單一遊戲中彩金額
            $bonus1040_per,//總彩金額占比
            ($dancer_data[1] - $dancer_data[3]), // Net Win (總投注額 - 中彩金額)
            $expect,//期望值(%)
            $dancer_data[2],//總投注次數
            $dancer_data[4],//中彩總次數
            $bet1040_avg,//平均投注額
            $bonus1040_avg,//平均彩金額
        ];

        if ($neon2_data[1] == 0 || $neon2_data[2] == 0 || $neon2_data[3] == 0) {
            $user1045_avg = 0;
            $total1045_per = 0;
            $bonus1045_per = 0;
            $bet1045_avg = 0;
            $bonus1045_avg = 0;
        } else {
            $user1045_avg = round($neon2_data[2]/$neon2_data[0]);
            $total1045_per = round(($neon2_data[1]/$totalInCome),1);
            $bonus1045_per = round(($neon2_data[3]/$totalBonus),1);
            $bet1045_avg = round(($neon2_data[1]/$neon2_data[2]),1);
            $bonus1045_avg = round(($neon2_data[3]/$neon2_data[4]),1);
        }

        $game1045 = [
            "1045",
            $neon2_data[0],//不重覆帳號數
            $user1045_avg,//平均投注次數
            $neon2_data[1],//單一遊戲總投注額
            $total1045_per,//總投注額占比
            $neon2_data[3],//單一遊戲中彩金額
            $bonus1045_per,//總彩金額占比
            ($neon2_data[1] - $neon2_data[3]), // Net Win (總投注額 - 中彩金額)
            $expect,//期望值(%)
            $neon2_data[2],//總投注次數
            $neon2_data[4],//中彩總次數
            $bet1045_avg,//平均投注額
            $bonus1045_avg,//平均彩金額
        ];

        if ($toi_data[1] == 0 || $toi_data[2] == 0 || $toi_data[3] == 0) {
            $user1047_avg = 0;
            $total1047_per = 0;
            $bonus1047_per = 0;
            $bet1047_avg = 0;
            $bonus1047_avg = 0;
        } else {
            $user1047_avg = round($toi_data[2]/$toi_data[0]);
            $total1047_per = round(($toi_data[1]/$totalInCome),1);
            $bonus1047_per = round(($toi_data[3]/$totalBonus),1);
            $bet1047_avg = round(($toi_data[1]/$toi_data[2]),1);
            $bonus1047_avg = round(($toi_data[3]/$toi_data[4]),1);
        }

        $game1047 = [
            "1047",
            $toi_data[0],//不重覆帳號數
            $user1047_avg,//平均投注次數
            $toi_data[1],//單一遊戲總投注額
            $total1047_per,//總投注額占比
            $toi_data[3],//單一遊戲中彩金額
            $bonus1047_per,//總彩金額占比
            ($toi_data[1] - $toi_data[3]), // Net Win (總投注額 - 中彩金額)
            $expect,//期望值(%)
            $toi_data[2],//總投注次數
            $toi_data[4],//中彩總次數
            $bet1047_avg,//平均投注額
            $bonus1047_avg,//平均彩金額
        ];

        if ($spy_data[1] == 0 || $spy_data[2] == 0 || $spy_data[3] == 0) {
            $user1049_avg = 0;
            $total1049_per = 0;
            $bonus1049_per = 0;
            $bet1049_avg = 0;
            $bonus1049_avg = 0;
        } else {
            $user1049_avg = round($spy_data[2]/$spy_data[0]);
            $total1049_per = round(($spy_data[1]/$totalInCome),1);
            $bonus1049_per = round(($spy_data[3]/$totalBonus),1);
            $bet1049_avg = round(($spy_data[1]/$spy_data[2]),1);
            $bonus1049_avg = round(($spy_data[3]/$spy_data[4]),1);
        }

        $game1049 = [
            "1049",
            $spy_data[0],//不重覆帳號數
            $user1049_avg,//平均投注次數
            $spy_data[1],//單一遊戲總投注額
            $total1049_per,//總投注額占比
            $spy_data[3],//單一遊戲中彩金額
            $bonus1049_per,//總彩金額占比
            ($spy_data[1] - $spy_data[3]), // Net Win (總投注額 - 中彩金額)
            $expect,//期望值(%)
            $spy_data[2],//總投注次數
            $spy_data[4],//中彩總次數
            $bet1049_avg,//平均投注額
            $bonus1049_avg,//平均彩金額
        ];
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $datas = collect([$game1036,$game1040,$game1045,$game1047,$game1049])->all();

        foreach ($datas as $data) {
            $gameData = new GameReport;
            $gameData->date = $yesterday;
            $gameData->gameno = $data[0];
            $gameData->user_count = $data[1];
            $gameData->bet_count_avg = $data[2];
            $gameData->total_bet = $data[3];
            $gameData->total_bet_per = $data[4];
            $gameData->total_bonus = $data[5];
            $gameData->total_bonus_per = $data[6];
            $gameData->netWin = $data[7];
            $gameData->expectation = $data[8];
            $gameData->bet_count = $data[9];
            $gameData->bonus_count = $data[10];
            $gameData->total_bet_avg = $data[11];
            $gameData->total_bonus_avg = $data[12];
            $gameData->save();
        }
        dd("執行成功");
    }
}
