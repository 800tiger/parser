<?php

namespace Parser\Traits;

trait ValidateTraits {

    public static function verifyKey($record){
        
        $validChars = ['2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C','D', 'E', 'F', 'G', 'H', 'J', 'K','L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T','U', 'V', 'W', 'X', 'Y', 'Z'];
        if(strlen($record) != 10){
            return false;
        }
        else{
            $bankcode = substr(strtoupper($record),0,9);
            $factor = 2;
            $sum = 0;
            $code_length = strlen($bankcode);
            $codearray = str_split($bankcode);
            for($i = $code_length-1; $i >=0; $i--){
                $codepoint = array_search($codearray[$i],$validChars);
                $addend = $factor * $codepoint;
                $addend = ($addend / $code_length) + ($addend % $code_length);
                $sum += $addend;
            }
            $remainder = $sum % $code_length;
            $checkDigital = ($code_length - $remainder) % $code_length;

            if($checkDigital != "00"){
                return true;
            }
            else{
                return false;
            }
        }
    }
}

