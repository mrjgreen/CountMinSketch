<?php
ini_set('memory_limit','512M');

include __DIR__ . '/../vendor/autoload.php';

use SebastianBergmann\Diff\Differ;

$registerSize = 16;

$sketch = new CountMinSketch($registerSize);

$frequencyCounter = [];

$actualCounter = [];
$actualFrequencyCounter = [];

$a = 1000000;

$filename = __DIR__ . '/../errortest/data/countminsketch-'.$a . '-' . $registerSize . '-' .date('YmdHis').'.csv';

while($a--)
{
    echo $a . "\r";
    $rand = rand(1000,900000);

    $newFreq = $sketch->updateQuery($rand, 1);


    isset($frequencyCounter[$newFreq - 1]) && $frequencyCounter[$newFreq - 1]--;
    isset($frequencyCounter[$newFreq]) ? $frequencyCounter[$newFreq]++ : $frequencyCounter[$newFreq] = 1;

    isset($actualCounter[$rand]) ? $actualCounter[$rand]++ : $actualCounter[$rand] = 1;
    $newActual = $actualCounter[$rand];

    isset($actualFrequencyCounter[$newActual - 1]) && $actualFrequencyCounter[$newActual - 1]--;
    isset($actualFrequencyCounter[$newActual]) ? $actualFrequencyCounter[$newActual]++ : $actualFrequencyCounter[$newActual] = 1;

}

echo PHP_EOL . 'Done... Saving...' . PHP_EOL;

$blah = $sketch->export();

$s = new CountMinSketch();

$max = max(array_keys($frequencyCounter));
$max2 = max(array_keys($actualFrequencyCounter));
$max = max($max,$max2);

$fh = fopen($filename,'w');
fwrite($fh, "#views\tactual\testimated\n");
for($i = 1; $i <= $max; $i++)
{
    $a = isset($actualFrequencyCounter[$i]) ? $actualFrequencyCounter[$i] : 0;
    $b = isset($frequencyCounter[$i]) ? $frequencyCounter[$i] : 0;
    fwrite($fh, "$i\t$a\t$b\n");
}

fclose($fh);