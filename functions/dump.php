<?php 

function dump(mixed $data, bool $varDump = false): void
{
    $traces = debug_backtrace();
    $caller = $traces[0];
    
    echo '<div style="background-color: #2c3645; border-radius: 6px; margin: 30px 0;">';
    echo '<pre style="border-radius: 6px; padding: 15px; background-color: #050d18; color: #FFFFFF; margin: 0;">';
    $varDump ? var_dump($data) : print_r($data);
    echo '</pre>';
    echo '<div style="font-weight: 700; font-size: .8rem; color: #ffffff; padding: 8px 15px;">'.$caller['file'].":".$caller['line'].'</div>';
    echo '</div>';
}