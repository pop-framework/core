<?php 

function dump(mixed $data, bool $varDump = false): void
{
    $traces = debug_backtrace();
    $caller = $traces[0];
    
    echo '<pre class="dump">';
    $varDump ? var_dump($data) : print_r($data);
    echo '</pre>';
    echo '<div class="dump-caption">'.$caller['file'].":".$caller['line'].'</div>';
}