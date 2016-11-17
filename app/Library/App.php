<?php
namespace App\Library;

class App
{
    public function hourOption($selected = null)
    {
		$option = '';
        if ($selected === null) {
            $selected = date('H', time() + 600);
        }

        for ($i = 0; $i < 24; ++$i) {
            $hour = str_pad($i, 2, 0, STR_PAD_LEFT);

            if ($selected === $hour) {
                $option .= '<option value="'.$hour.'" selected>'.$hour.'</option>';
            } else {
                $option .= '<option value="'.$hour.'">'.$hour.'</option>';
            }
        }
		
        return $option;
    }

    public function minOption($selected = null)
    {
		$option = '';
        if ($selected === null) {
            $selected = date('i', time() + 600);
        }

        for ($i = 0; $i < 60; ++$i) {
            $mins = str_pad($i, 2, 0, STR_PAD_LEFT);
			
            if ($selected === $mins) {
                $option .= '<option value="'.$mins.'" selected>'.$mins.'</option>';
            } else {
                $option .= '<option value="'.$mins.'">'.$mins.'</option>';
            }
        }
		
        return $option;
    }

    public function dateOption($inverval = 14)
    {
		$option = '';
        for ($i = 0; $i < $inverval; ++$i) {
            $date = date('Y-m-d', strtotime("+{$i} day"));
            $option .= '<option value="'.$date.'">'.$date.'</option>';
        }

        return $option;
    }

    /**
     * Build option string.
     *
     * @param array $datas
     *
     * @return string
     */
    public function makeOption($datas = array())
    {
        $option = '';

        foreach ($datas as $key => $data) {
            $option .= '<option value="'.$key.'">'.$data.'</option>';
        }

        return $option;
    }
}