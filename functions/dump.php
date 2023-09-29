<?php 

function dump(mixed $data, bool $varDump = false): void
{
    $traces = debug_backtrace();
    $caller = $traces[0];
    
    echo "<div>".$caller['file'].":".$caller['line']."</div>";
    echo "<pre>";
    $varDump ? var_dump($data) : print_r($data);
    echo "</pre>";
}