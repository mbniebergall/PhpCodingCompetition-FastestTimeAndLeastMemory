<?php

declare(strict_types=1);

memory_reset_peak_usage();
$startingMemory = memory_get_usage();
$startDatetime = date('Y-m-d H:i:s');
$startTime = microtime(true);

echo 'Start Datetime: ' . $startDatetime . PHP_EOL;
echo 'Start Time: '. $startTime . PHP_EOL;

$total_common_passwords = 0;
$total_words = 0;
$common_passwords_that_match_case_insensitive_dictionary_words = 0;
$common_passwords_that_match_case_sensitive_dictionary_words = 0;
$common_passwords_that_only_contain_lowercase_letters = 0;
$common_passwords_that_only_contain_uppercase_letters = 0;
$common_passwords_that_only_contain_numbers = 0;
$common_passwords_that_only_contain_letters = 0;
$common_passwords_that_only_contain_alphanumeric_characters = 0;
$common_passwords_that_contain_any_non_alphanumeric_characters = 0;
$minimum_common_password_length = PHP_INT_MAX;
$maximum_common_password_length = 0;
$average_common_password_length = 0.0;

function getData(string $filename): \Generator
{
    $handle = fopen($filename, 'r');
    while (($line = fgets($handle)) !== false) {
        yield $line;
    }
    fclose($handle);
}

foreach (getData('src/Password/Common/10-million-password-list-top-10000.txt') as $line) {
    $line = trim($line);
    $total_common_passwords++;
    $total_words += \str_word_count($line);
    $common_passwords_that_only_contain_lowercase_letters += ctype_lower($line) ? 1 : 0;
    $common_passwords_that_only_contain_uppercase_letters += ctype_upper($line) ? 1 : 0;
    $common_passwords_that_only_contain_numbers += ctype_digit($line) ? 1 : 0;
    $common_passwords_that_only_contain_letters += ctype_alpha($line) ? 1 : 0;
    $common_passwords_that_only_contain_alphanumeric_characters += ctype_alnum($line) ? 1 : 0;
    $common_passwords_that_contain_any_non_alphanumeric_characters += ctype_alnum($line) ? 0 : 1;
    $minimum_common_password_length = \min($minimum_common_password_length, \strlen($line));
    $maximum_common_password_length = \max($maximum_common_password_length, \strlen($line));
    $average_common_password_length += \strlen($line);

    foreach (getData('src/Password/Common/10-million-password-list-top-10000.txt') as $word) {
        $word = trim($word);
        if (\strcasecmp($line, $word) === 0) {
            $common_passwords_that_match_case_insensitive_dictionary_words++;
        }
        if (\strcmp($line, $word) === 0) {
            $common_passwords_that_match_case_sensitive_dictionary_words++;
        }
    }
}

// run code
echo "Total Common Passwords: $total_common_passwords" . PHP_EOL;
echo "Total Words: $total_words" . PHP_EOL;
echo "Common Passwords that match case-insensitive Dictionary words: $common_passwords_that_match_case_insensitive_dictionary_words" . PHP_EOL;
echo "Common Passwords that match case-sensitive Dictionary words: $common_passwords_that_match_case_sensitive_dictionary_words" . PHP_EOL;
echo "Common Passwords that only contain lowercase letters: $common_passwords_that_only_contain_lowercase_letters" . PHP_EOL;
echo "Common Passwords that only contain uppercase letters: $common_passwords_that_only_contain_uppercase_letters" . PHP_EOL;
echo "Common Passwords that only contain numbers: $common_passwords_that_only_contain_numbers" . PHP_EOL;
echo "Common Passwords that only contain letters: $common_passwords_that_only_contain_letters" . PHP_EOL;
echo "Common Passwords that only contain alphanumeric characters: $common_passwords_that_only_contain_alphanumeric_characters" . PHP_EOL;
echo "Common Passwords that contain any non-alphanumeric characters: $common_passwords_that_contain_any_non_alphanumeric_characters" . PHP_EOL;
echo "Minimum Common Password Length: $minimum_common_password_length" . PHP_EOL;
echo "Maximum Common Password Length: $maximum_common_password_length" . PHP_EOL;
echo "Average Common Password Length: " . $average_common_password_length / $total_common_passwords . PHP_EOL;

$endDatetime = date('Y-m-d H:i:s');
echo 'End Datetime: ' . $endDatetime . PHP_EOL;
$endTime = microtime(true);
echo 'End Time: ' . $endTime . PHP_EOL;
echo 'Total Time: ' . ($endTime - $startTime) . PHP_EOL;
$totalMemoryUsed = memory_get_peak_usage() - $startingMemory;
echo 'Peak Memory: ' . memory_get_peak_usage() . PHP_EOL;
echo 'Memory Used: ' . $totalMemoryUsed . PHP_EOL;
