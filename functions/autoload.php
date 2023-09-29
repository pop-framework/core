<?php 

foreach(scandir(__DIR__) as $file) 
{
    $file = __DIR__."/".$file;

    if (preg_match("/\.php$/", $file) && $file !== __FILE__) 
    {
        include_once $file;
    }
}