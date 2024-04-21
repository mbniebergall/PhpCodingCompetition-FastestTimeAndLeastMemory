<?php

declare(strict_types=1);

memory_reset_peak_usage();
$startingMemory = memory_get_usage();
$startDatetime = date('Y-m-d H:i:s');
$startTime = microtime(true);

echo 'Start Datetime: ' . $startDatetime . PHP_EOL;
echo 'Start Time: '. $startTime . PHP_EOL;

// run code

$endDatetime = date('Y-m-d H:i:s');
echo 'End Datetime: ' . $endDatetime . PHP_EOL;
$endTime = microtime(true);
echo 'End Time: ' . $endTime . PHP_EOL;
echo 'Total Time: ' . ($endTime - $startTime) . PHP_EOL;
$totalMemoryUsed = memory_get_peak_usage() - $startingMemory;
echo 'Peak Memory: ' . memory_get_peak_usage() . PHP_EOL;
echo 'Memory Used: ' . $totalMemoryUsed . PHP_EOL;
