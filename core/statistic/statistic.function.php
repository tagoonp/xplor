<?php
function mmmr($array, $output = 'mean'){
    if(!is_array($array)){
        return FALSE;
    }else{
        switch($output){
            case 'mean':
                $count = count($array);
                $sum = array_sum($array);
                $total = $sum / $count;
            break;
            case 'median':
                rsort($array);
                $middle = round(count($array) / 2);
                $total = $array[$middle-1];
            break;
            case 'mode':
                $v = array_count_values($array);
                arsort($v);
                foreach($v as $k => $v){$total = $k; break;}
            break;
            case 'range':
                sort($array);
                $sml = $array[0];
                rsort($array);
                $lrg = $array[0];
                $total = $lrg - $sml;
            break;
        }
        return $total;
    }
}

function standard_deviation($sample){
	if(is_array($sample)){
		$mean = array_sum($sample) / count($sample);
		foreach($sample as $key => $num) $devs[$key] = pow($num - $mean, 2);
		return sqrt(array_sum($devs) / (count($devs) - 1));
	}
}
?>
