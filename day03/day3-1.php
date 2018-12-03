<?php

$puzzle = file('puzzle.txt', FILE_IGNORE_NEW_LINES);
$pieces = [];
$coords = [];

/*
$pieces = [
    0 => [
        [Num] => #1
        [x1] => 185
        [y1] => 501
        [x2] => 202
        [y2] => 516
    ],
    1 => [
        [Num] => #2
        [x1] => 821
        [y1] => 899
        [x2] => 846
        [y2] => 920
    ],
    ...
];

$coords = [
    0 => '185,501',
    1 => '185,502',
    ...
];
*/

// Import puzzle data and expand into nested array
foreach ($puzzle as $key => $value) {
    if (preg_match('~^(#[0-9]+) @ ([^,]+),([^:]+): ([^x]+)x([^$]+)$~', $value, $matches)) {
        $pieces[] = [
            'num' => $matches[1],
            'x1' => $matches[2],
            'y1' => $matches[3],
            'x2' => $matches[2] + $matches[4],
            'y2' => $matches[3] + $matches[5],
        ];
    } else {
        die("\nError with preg_match!\n");
    }
}

// Translate pieces array into a list of each x,y square-inch coordinate on the fabric
foreach ($pieces as $key => $piece) {
    for ($y = $piece['y1']; $y < $piece['y2']; $y++) { // Top to bottom
        for ($x = $piece['x1']; $x < $piece['x2']; $x++) { // Left to right
            $coords[] = "$x,$y";
        }
    }
}

// Get Duplicates
$duplicates = array_unique(array_diff_assoc($coords, array_unique($coords)));

print "The answer is: " . count($duplicates);
