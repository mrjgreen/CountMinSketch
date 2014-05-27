<?php

$allowed = array();

foreach(glob(__DIR__ . '/../data/*') as $file){
    $option = basename($folder).'/'.basename($file);
    $allowed[$option] = $option;
}

if(isset($_GET['dataset']) && isset($allowed[$_GET['dataset']]))
{
    return file(__DIR__ . '/../data/' . $allowed[$_GET['dataset']]);
}

die('Please specify a valid data file to plot.');

