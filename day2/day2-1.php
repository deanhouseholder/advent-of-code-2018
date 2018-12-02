<?php

// Note:
//   Designed in a way that it doesn't care how many
//   duplicate letters could be in a string.

$puzzle = file('puzzle.txt', FILE_IGNORE_NEW_LINES);
$overall_counter = [];
$answer = 1;

foreach ($puzzle as $key => $value) {
    // Initialize arrays (and reset them each loop)
    $letter_array = [];
    $duplicate_array = [];

    // Build array of letters with counts
    for ($i = 0; $i<strlen($value); $i++) {
        $letter_array[$key][$value[$i]] ++;
    }

    // Build array of counts 2 and over
    foreach ($letter_array[$key] as $letter => $count) {
        if ($count >= 2) {
            $duplicate_array[$count]++;
        }
    }

    // Increment overall counter
    foreach ($duplicate_array as $count => $val) {
        $overall_counter[$count]++;
    }
}

// Calculate final answer
foreach ($overall_counter as $value) {
    $answer *= $value;
}

print "The answer is: $answer\n";
