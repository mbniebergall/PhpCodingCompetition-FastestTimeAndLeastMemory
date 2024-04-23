#!/usr/bin/env php
<?php

declare(strict_types=1);

memory_reset_peak_usage();
$startingMemory = memory_get_usage();
$startDatetime = date('Y-m-d H:i:s');
$startTime = microtime(true);

echo 'Start Datetime: ' . $startDatetime . PHP_EOL;
echo 'Start Time: '. $startTime . PHP_EOL;

// run code

$words = file('src/Password/Common/words.txt', FILE_IGNORE_NEW_LINES);
$wordMap = [];
$wordMapLower = [];

foreach ($words as $word) {
    $wordMap[$word[0]][$word] = true;
    $wordMapLower[strtolower($word[0])][strtolower($word)] = true;
}

$passwords = file('src/Password/Common/10-million-password-list-top-10000.txt', FILE_IGNORE_NEW_LINES);

$stats = new class {
    public int $wordCountNoCase = 0;
    public int $wordCountCase = 0;
    public int $onlyLower = 0;
    public int $onlyUpper = 0;
    public int $onlyNumber = 0;
    public int $onlyLetter = 0;
    public int $onlyAlphanum = 0;
    public int $anyNonAlphaNum = 0;

    public int $maximumLength = 0;
    public int $minimumLength = PHP_INT_MAX;

    public int $totalLength = 0;
};

$alnum = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$lower = 'abcdefghijklmnopqrstuvwxyz';
$upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$number = '0123456789';

foreach ($passwords as $password) {
    if (isset($wordMapLower[strtolower($password[0])][strtolower($password)])) {
        $stats->wordCountNoCase++;
        if (isset($wordMap[$password[0]][$password])) {
            $stats->wordCountCase++;
        }
    }

    if (strspn($password, $alnum) === strlen($password)) {
        $stats->onlyAlphanum++;

        if (strspn($password, $alpha) === strlen($password)) {
            $stats->onlyLetter++;
            if (strspn($password, $lower) === strlen($password)) {
                $stats->onlyLower++;
            } else if (strspn($password, $upper) === strlen($password)) {
                $stats->onlyUpper++;
            }
        } else if (strspn($password, $number) === strlen($password)) {
            $stats->onlyNumber++;
        }
    } else {
        $stats->anyNonAlphaNum++;
    }
    $len = strlen($password);
    if ($len > $stats->maximumLength) {
        $stats->maximumLength = $len;
    } elseif ($len < $stats->minimumLength) {
        $stats->minimumLength = $len;
    }
    $stats->totalLength += $len;
}

echo 'Common Passwords that match case-insensitive Dictionary words: '.$stats->wordCountNoCase.PHP_EOL;
echo 'Common Passwords that match case-sensitive Dictionary words: '.$stats->wordCountCase.PHP_EOL;
echo 'Common Passwords that only contain lowercase letters: '.$stats->onlyLower.PHP_EOL;
echo 'Common Passwords that only contain uppercase letters: '.$stats->onlyUpper.PHP_EOL;
echo 'Common Passwords that only contain numbers: '.$stats->onlyNumber.PHP_EOL;
echo 'Common Passwords that only contain letters: '.$stats->onlyLetter.PHP_EOL;
echo 'Common Passwords that only contain alphanumeric characters: '.$stats->onlyAlphanum.PHP_EOL;
echo 'Common Passwords that contain any non-alphanumeric characters: '.$stats->anyNonAlphaNum.PHP_EOL;
echo 'Minimum Common Password Length: '.$stats->minimumLength.PHP_EOL;
echo 'Maximum Common Password Length: '.$stats->maximumLength.PHP_EOL;
echo 'Average Common Password Length: '.round($stats->totalLength / count($passwords), 1, PHP_ROUND_HALF_DOWN).PHP_EOL;


$endDatetime = date('Y-m-d H:i:s');
echo 'End Datetime: ' . $endDatetime . PHP_EOL;
$endTime = microtime(true);
echo 'End Time: ' . $endTime . PHP_EOL;
echo 'Total Time: ' . ($endTime - $startTime) . PHP_EOL;
$totalMemoryUsed = memory_get_peak_usage() - $startingMemory;
echo 'Peak Memory: ' . memory_get_peak_usage() . PHP_EOL;
echo 'Memory Used: ' . $totalMemoryUsed . PHP_EOL;
