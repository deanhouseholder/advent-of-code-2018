<?php

$puzzle = file('puzzle.txt', FILE_IGNORE_NEW_LINES);
$display_map = 0;
// Built this array backwards so letters can be popped off alphabetically (a-z then A-Z)
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
        // Loop through the array of puzzle coordinates
        foreach ($coords_array as $letter => $coords) {
            if ($x == $coords[0] && $y == $coords[1]) {
                // A matching coordinate pair was found for this exact grid point
                $match = $letter;
            } else {
                // This coordinate in the puzzle coords array does not match this grid point
                // Build array of distances between each coordinate pair and this grid point
                $distances[$letter] = abs($x - $coords[0]) + abs($y - $coords[1]);
            }
        }
        // $add holds the value to display on the $map
        if ($match != -1) {
            $add = $match;
        } else {
            // Determine the minimum distance value
            $min = min($distances);
            // Find one or more keys which match the minimum value
            $matches = array_keys($distances, $min);
            if (count($matches) > 1) {
                // If more than 1 match, $add is a '.' to indicate more than 1 match
                $add = ".";
            } else {
                // Otherwise, $add is the value of the matching key, which is the letter of the nearest puzzle coordinate
                $add = $matches[0];
            }
        }
        // Build a visual map
        $map .= $add;

        // Initialize the array key if it doesn't yet exist
        if (!isset($count[$add])) {
            $count[$add] = 0;
        }
        // Increment an array for each $add which will be used to calculate the sizes of each area
        $count[$add]++;

        // Determine if the grid point is touching an edge which would classify the
        // closest cordinate match to be labeled as "infinite"
        if (($x == 0 || $y == 0 || $x == $max || $y == $max) && $add != "." && !in_array($add, $infinite)) {
            $infinite[] = $add;
        }
    }
    $map .= "\n";
}

// Optionally display the map
if ($display_map) {
    print $map;
}

// Remove the '.' element from the array
unset($count['.']);

// Build a new count array to exclude the "infinite" areas
foreach ($count as $key => $count) {
    if (!in_array($key, $infinite)) {
        $new_count[$key] = $count;
    }
}

// Determine the largest area size
$max = max($new_count);

// Determine the largest area letter
$largest_group = array_keys($new_count, $max);

print "\nThe largest block is: " . $largest_group[0] . "\n";
print "The answer is: " . $max . "\n";
