<?php

$puzzle = trim(file_get_contents('puzzle.txt'));

// Check for initial matching pairs
$matches = find_matches($puzzle);
$matching_pairs = number_format(count($matches)/2);

do {
    // Remove previously found matches
    $puzzle = remove_matches($puzzle, $matches);

    // Check to see if there are more matches
    $matches = find_matches($puzzle);
    $matching_pairs = number_format(count($matches)/2);
} while ($matching_pairs > 0);

print "The answer is: " . strlen($puzzle) . "\n";


/* Functions */

// Find matching pairs of characters with opposite casing (upper/lower)
function find_matches($puzzle) {
    $skip_one = 0;
    $matches = [];
    $length = strlen($puzzle);

    for ($i=0; $i<($length-1); $i++) {

        // If the last iteration found a match to be destroyed, skip this iteration
        if ($skip_one == 1) {
            $skip_one = 0;
            continue;
        }

        $char1 = $puzzle[$i];
        $char2 = $puzzle[$i+1];

        // If the characters are the same letter irrespective of case
        if (strcasecmp($char1, $char2) == 0) {

            // Check if they are opposite case
            $char1_is_upper = ctype_upper($char1) ? 1 : 0;
            $char2_is_upper = ctype_upper($char2) ? 1 : 0;
            if (($char1_is_upper + $char2_is_upper) == 1) {
                $matches[] = $i;
                $matches[] = $i+1;
                $skip_one = 1;
            }
        }
    }
    return $matches;
}

// Remove previously found matching pairs from the puzzle
function remove_matches($puzzle, $matches) {
    $new_puzzle = "";
    $length = strlen($puzzle);
    for ($i=0; $i<($length); $i++) {
        if (!in_array($i, $matches)) {
            // Keep the character
            $new_puzzle .= $puzzle[$i];
        }
    }
    return $new_puzzle;
}
