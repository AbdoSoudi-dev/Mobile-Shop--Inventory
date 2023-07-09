<?php


namespace App;


class helpers
{
    public function compareValues($value)
    {
        if ($value[0] == $value[1]) return ($value[0] ?? "");
        else return ($value[0] ?? "") . "<br> <div class='text-danger'>" . ($value[1] ?? "") . "</div>";
    }
    public function numToArabic($string) {
        return strtr($string, array('0'=>'٠','1'=>'١', '2'=>'٢', '3'=>'٣', '4'=>'٤', '5'=>'٥', '6'=>'٦', '7'=>'٧', '8'=>'٨', '9'=>'٩'));
    }
}
