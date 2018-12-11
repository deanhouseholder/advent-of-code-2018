<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$puzzle = file('puzzle.txt', FILE_IGNORE_NEW_LINES);
// Built this array backwards so letters can be popped off alphabetically
$letter_array = array_merge(range('Z', 'A'), range('z', 'a'));
$coords_array = [];
$infinite = [];
$max_x = 0;
$max_y = 0;
$map = "";
$count = [];
$new_count = [];

// Build grid coordinates array
foreach ($puzzle as $coords) {
    list($x, $y) = explode(', ', $coords);
    $coords_array[array_pop($letter_array)] = [$x, $y];

    // Determine the largest dimension of the data
    $max_x = $x > $max_x ? $x : $max_x;
    $max_y = $x > $max_y ? $x : $max_y;
}

// Pick the largest dimension of the data + 1 (to counter for the fact that arrays start at 0)
$max = max([$max_x, $max_y]) + 1;

// Loop through the grid
for ($y=0; $y<=$max; $y++) { // Top to bottom
    for ($x=0; $x<=$max; $x++) { // Left to right
        $match = -1;
        $distances = [];
        foreach ($coords_array as $key => $coords) {
            if ($x == $coords[0] && $y == $coords[1]) {
                // A matching coordinate pair was found for this exact grid point
                // Use a capital letter to indicate it
                $match = $key;
            } else {
                // This coordinate in the coords array does not match this grid point
                // Build array of distances between each coordinate pair and this grid point
                $distances[$key] = distance($x, $coords[0]) + distance($y, $coords[1]);
            }
        }
        $add = $match != -1 ? $match : get_min_or_dot($distances);
        $map .= $add;
        if (!isset($count[$add])) {
            $count[$add] = 0;
        }
        $count[$add]++;

        // Determine if the grid point is touching an edge which would classify the
        // closest cordinate match to be labeled as "infinite"
        if (($x == 0 || $y == 0 || $x == $max || $y == $max) && $add != "." && !in_array($add, $infinite)) {
            $infinite[] = $add;
        }
    }
    $map .= "\n";
}

//print $map;

unset($count['.']);
foreach ($count as $key => $count) {
    if (!in_array($key, $infinite)) {
        $new_count[$key] = $count;
    }
}

$max = max($new_count);
$largest_group = array_keys($new_count, $max);

print "\nThe largest block is: " . $largest_group[0] . "\n";
print "The answer is: " . $max . "\n";

function distance($coord1, $coord2) {
    if ($coord1 > $coord2) {
        return $coord1 - $coord2;   // coord1 is bigger
    } elseif ($coord1 < $coord2) {
        return $coord2 - $coord1;   // coord2 is bigger
    } else {
        return 0;                   // coord1 is equal to coord2
    }
}

function get_min_or_dot($array) {
    $min = min($array);
    $matches = array_keys($array, $min);
    if (count($matches) > 1) {
        return ".";
    } else {
        return $matches[0];
    }
}
