<?php

require_once realpath(__DIR__ . '/ColorCLI.php');

$path = realpath(__DIR__ . "/../build/logs/junit.xml");

if (!file_exists($path)) {
    return;
}

$xml = simplexml_load_file($path);
$testsuite = $xml->testsuite;
echo sprintf("total:    %s msec", formatMsec($testsuite['time'])) . PHP_EOL;

foreach ($testsuite->testsuite as $suite) {
    echo sprintf("  suite:  %s msec : %s", formatMsec($suite['time']), $suite['name']) . PHP_EOL;

    foreach ($suite->testcase as $testcase) {
        echo sprintf("    case: %s msec :   %s", printMsec($testcase['time']), $testcase['name']) . PHP_EOL;
    }
}

function msec($str)
{
    return floatval((string)$str) * 1000;
}

function formatMsec($time)
{
    return sprintf('%9.3f', msec($time));
}

function printMsec($time, $limit = 5)
{
    $str = formatMsec($time);

    if (!class_exists('ColorCLI')) {
        return $str;
    }

    if (msec($time) < $limit) {
        return ColorCLI::lightGreen($str);
    }

    return ColorCLI::red($str);
}
