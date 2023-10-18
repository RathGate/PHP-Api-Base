<?php

class SortLib {
    static function insertionSort($arr){
        for ($i = 0; $i < count($arr); $i++) {
            $currentValue = $arr[$i];
            $prevIndex = $i - 1;
            while ($prevIndex >= 0 && $currentValue < $arr[$prevIndex]) {
                $arr[$prevIndex + 1] = $arr[$prevIndex];
                $prevIndex = $prevIndex - 1;
            }
            $arr[$prevIndex+1] = $currentValue;
        }
        return $arr;
    }

    static function bubbleSort($arr) {
        for ($i = 0; $i < count($arr); $i++) {
            $hasSwapped = false;
            for ($j = 0; $j < count($arr) - $i - 1; $j++) {
                if ($arr[$j] > $arr[$j+1]) {
                    $temp = $arr[$j];
                    $arr[$j] = $arr[$j+1];
                    $arr[$j+1] = $temp;
                    $hasSwapped = true;
                }
            }
            if (!$hasSwapped) {
                break;
            }
        }
        return $arr;
    }

}