# PHP Coding Competition

## Challenge: Fastest Time

### Calculate criteria helpful in determining password complexity on 100k common passwords

### Requirements
- Time is calculated in microseconds: ss.0123456789012
  - Example: 123.0123456789012
  - See: `src/Calculate/CalculateMemoryTimingExample.php`
  - MUST be first and last lines of script
```php
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
```
- 10k most common passwords file: https://github.com/danielmiessler/SecLists/blob/master/Passwords/Common-Credentials/10-million-password-list-top-10000.txt
- ~466k English words: https://github.com/dwyl/english-words/blob/master/words.txt
- Complete analysis of common passwords, replacing all `<...>` with correct value:
```txt
Start Datetime: <date('Y-m-d H:i:s')>
Start Time: <microtime()>
Total Common Passwords: <int>
Total Words: <int>
Common Passwords that match case-insensitive Dictionary words: <int>
Common Passwords that match case-sensitive Dictionary words: <int>
Common Passwords that only contain lowercase letters: <int>
Common Passwords that only contain uppercase letters: <int>
Common Passwords that only contain numbers: <int>
Common Passwords that only contain letters: <int>
Common Passwords that only contain alphanumeric characters: <int>
Common Passwords that contain any non-alphanumeric characters: <int>
Minimum Common Password Length: <int>
Maximum Common Password Length: <int>
Average Common Password Length: <float, 1, round down>
End Datetime: <date('Y-m-d H:i:s')>
End Time: <microtime()>
Total Time: <End Time - Start Time>
Peak Memory: <memory_get_peak_usage()>
```