<?php

$puzzle = file('puzzle.txt', FILE_IGNORE_NEW_LINES);
$display_map = 0;
// Built this array backwards so letters can be popped off alphabetically (a-z then A-Z)
$letter_array = array_merge(range('Z', 'A'), range('z', 'a'));
$coords_array = [];
$max_x = 0;
$max_y = 0;
$map = "";
$distance_criteria = 10000;
$area = 0;

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
        foreach ($coords_array as $letter => $coords) {
            // Determine distance between the current grid point and each puzzle coordinate
            $distance[$letter] = abs($x - $coords[0]) + abs($y - $coords[1]);
            if ($x == $coords[0] && $y == $coords[1]) {
                // A matching coordinate pair was found for this exact grid point
                $match = $letter;
            }
        }
        // Get sum of distances to all points
        $count_of_distances = array_sum($distance);

        // Check if the sum of distances is less than defined criteria
        if ($count_of_distances < $distance_criteria) {
            // Meets criteria
            $area++;
            if ($match != -1) {
                $map .= $match;
            } else {
                $map .= "#";
            }
        } else {
            // Falls outside of criteria-defined range
            if ($match != -1) {
                $map .= $match;
            } else {
                $map .= ".";
            }
        }
    }
    $map .= "\n";
}

// Optionally display the map
if ($display_map) {
    print $map;
}

print "The answer is: $area\n";
