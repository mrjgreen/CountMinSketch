<?php

include __DIR__ . '/../vendor/autoload.php';

use SebastianBergmann\Diff\Differ;

$sketch = new CountMinSketch();

$frequencyCounter = [];

$actualCounter = [];
$actualFrequencyCounter = [];

$a = 10000;

while($a--)
{
    $rand = rand(1000,20000);

    $newFreq = $sketch->updateQuery($rand, 1);


    isset($frequencyCounter[$newFreq - 1]) && $frequencyCounter[$newFreq - 1]--;
    isset($frequencyCounter[$newFreq]) ? $frequencyCounter[$newFreq]++ : $frequencyCounter[$newFreq] = 1;

    isset($actualCounter[$rand]) ? $actualCounter[$rand]++ : $actualCounter[$rand] = 1;
    $newActual = $actualCounter[$rand];

    isset($actualFrequencyCounter[$newActual - 1]) && $actualFrequencyCounter[$newActual - 1]--;
    isset($actualFrequencyCounter[$newActual]) ? $actualFrequencyCounter[$newActual]++ : $actualFrequencyCounter[$newActual] = 1;

}

$blah = $sketch->export();

$s = new CountMinSketch();

$differ = new Differ;

print $differ->diff($frequencyCounter, $actualFrequencyCounter);

var_dump(strlen($blah));