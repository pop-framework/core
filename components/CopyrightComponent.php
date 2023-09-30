<?php 
namespace Pop\Components;

class CopyrightComponent
{
    public static function name(): string 
    {
        return "copyright";
    }

    // public function load(string $data, string $date)
    public function load(?string $brand=null)
    {
        $date = date("Y");

        $str = "&copy $date";
        
        if ($brand)
        {
            $str.= " $brand";
        }

        return $str;
    }
}