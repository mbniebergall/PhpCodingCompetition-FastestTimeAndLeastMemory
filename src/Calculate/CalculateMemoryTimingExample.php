<?php

declare(strict_types=1);

memory_reset_peak_usage();
$startingMemory = memory_get_usage();
$startDatetime = date('Y-m-d H:i:s');
$startTime = microtime(true);

echo 'Start Datetime: ' . $startDatetime . PHP_EOL;
echo 'Start Time: '. $startTime . PHP_EOL;

$totalCommonPasswords = 0;
$totalWords = 0;
$matchescaseinsensitivedictionarywords = 0;
$matchescasesensitivedictionarywords = 0;
$onlycontainlowercaseletters = 0;
$onlycontainuppercaseletters = 0;
$onlycontainnumbers = 0;
$onlycontainletters = 0;
$onlycontainalphanumericcharacters = 0;
$containanynonalphanumericcharacters = 0;
$minimumCommonPasswordLength = PHP_INT_MAX;
$maximumCommonPasswordLength = 0;
$averageCommonPasswordLength = 0.0;

function isInDictionary(string $password): array
{
    $dictionaryFile = fopen(__DIR__ . '/../Password/Common/words.txt', 'r');
    $isInDictCaseSensitive = false;
    $isInDictCaseInsensitive = false;

    while (($dictionaryWord = fgets($dictionaryFile)) !== false) {
        $dictionaryWord = trim($dictionaryWord);

        if ($dictionaryWord === $password) {
            $isInDictCaseSensitive = true;
            $isInDictCaseInsensitive = true;
            break;
        }

        if (strtolower($dictionaryWord) === strtolower($password)) {
            $isInDictCaseInsensitive = true;
            break;
        }
    }

    fclose($dictionaryFile);
    return [$isInDictCaseSensitive, $isInDictCaseInsensitive];
}

// count the length of lines in the dictionary file without empty lines
$dictionaryFile = fopen(__DIR__ . '/../Password/Common/words.txt', 'r');
while (!feof($dictionaryFile)) {
    $line = fgets($dictionaryFile);
    if ($line !== false && trim($line) !== '') {
        $totalWords++;
    }
}
fclose($dictionaryFile);


// Now go through the common passwords file one line by one...
$passwordFile = fopen(__DIR__ . '/../Password/Common/10-million-password-list-top-100000.txt', 'r');

$index = 0;
while(!feof($passwordFile)) {
    // Read a password
    $password = fgets($passwordFile);
    $index++;

    // Trim the password and skip if it is empty
    $password = trim($password);
    if(trim($password) === '') {
        continue;
    }

    if($index === 1000) {
        break;
    }

    // echo $password . PHP_EOL;

    $totalCommonPasswords++;

    $length = strlen($password);

    $minimumCommonPasswordLength = min($minimumCommonPasswordLength, $length);
    $maximumCommonPasswordLength = max($maximumCommonPasswordLength, $length);
    $averageCommonPasswordLength += $length;

    $isAlphaNumeric = false;
    $isOnlyLetters = false;
    if (preg_match('/^[a-z]+$/', $password)) {
        $onlycontainlowercaseletters++;
        $isAlphaNumeric = true;
        $isOnlyLetters = true;
    }
    if (preg_match('/^[A-Z]+$/', $password)) {
        $onlycontainuppercaseletters++;
        $isAlphaNumeric = true;
        $isOnlyLetters = true;
    }
    if (preg_match('/^[0-9]+$/', $password)) {
        $onlycontainnumbers++;
        $isAlphaNumeric = true;
    }
    if ($isOnlyLetters || preg_match('/^[a-zA-Z]+$/', $password)) {
        $onlycontainletters++;
        $isAlphaNumeric = true;
    }

    if ($isAlphaNumeric || preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        $onlycontainalphanumericcharacters++;
        $isAlphaNumeric = true;
    }

    if (!$isAlphaNumeric ) {
        $containanynonalphanumericcharacters++;
    }

    [$isInDictCaseSensitive, $isInDictCaseInsensitive] = isInDictionary($password);

    if($isInDictCaseSensitive) {
        $matchescasesensitivedictionarywords++;
    }

    if ($isInDictCaseInsensitive) {
        $matchescaseinsensitivedictionarywords++;
    }

    /* if($index % 1000 === 0) {
        echo "Processed $index passwords.\n";
    } */
}

fclose($passwordFile);

$averageCommonPasswordLength /= $totalCommonPasswords;

$output = <<<EOT
Total Common Passwords: $totalCommonPasswords
Total Words: $totalWords
Common Passwords that match case-insensitive Dictionary words: $matchescaseinsensitivedictionarywords
Common Passwords that match case-sensitive Dictionary words: $matchescasesensitivedictionarywords
Common Passwords that only contain lowercase letters: $onlycontainlowercaseletters
Common Passwords that only contain uppercase letters: $onlycontainuppercaseletters
Common Passwords that only contain numbers: $onlycontainnumbers
Common Passwords that only contain letters: $onlycontainletters
Common Passwords that only contain alphanumeric characters: $onlycontainalphanumericcharacters
Common Passwords that contain any non-alphanumeric characters: $containanynonalphanumericcharacters
Minimum Common Password Length: $minimumCommonPasswordLength
Maximum Common Password Length: $maximumCommonPasswordLength
Average Common Password Length: $averageCommonPasswordLength

EOT;

echo $output;

$endDatetime = date('Y-m-d H:i:s');
echo 'End Datetime: ' . $endDatetime . PHP_EOL;
$endTime = microtime(true);
echo 'End Time: ' . $endTime . PHP_EOL;
echo 'Total Time: ' . ($endTime - $startTime) . PHP_EOL;
$totalMemoryUsed = memory_get_peak_usage() - $startingMemory;
echo 'Peak Memory: ' . memory_get_peak_usage() . PHP_EOL;
echo 'Memory Used: ' . $totalMemoryUsed . PHP_EOL;
