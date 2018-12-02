<?php

print "<pre>\n";

$puzzle = file('puzzle.txt', FILE_IGNORE_NEW_LINES);
$letter_array = [];
$length = strlen($puzzle[0]);
$total_rows = count($puzzle);
$matches = [];
$answer = "";

/*
  Goal: Split strings into a nested array of letters
  Example: $letter_array[$box][$char]
*/
foreach ($puzzle as $key => $value) {
    for ($i = 0; $i<strlen($value); $i++) {
        $letter_array[$key][$i] = $value[$i];
    }
}

/*
  Goal: Build array of number of matches beween each "box id"
  Example: $matches[$outter_box][$inner_box][value]++;
  Logic:
    loop through each box id {
        loop through each box id + 1 (no need to repeat previous comparisons) {
            loop through each character position {
                if outter box char matches inner box char {
                    $matches[$outter_box][$inner_box][value]++;
                }
            }
        }
    }
*/
for ($i = 0; $i < $total_rows; $i++) {
    for ($j = $i+1; $j < $total_rows; $j++) {
        $val = 0;
        for ($p = 0; $p < $length; $p++) {
            if ($letter_array[$i][$p] == $letter_array[$j][$p]) {
                $val++;
                $matches[$i][$j]['value'] = $val;
            }
        }
    }
}

/*
  Goal: Compare match counts and determine winner. Build array of matching id's
  Example: $winners = [$box1, $box2]
  Logic:
    foreach $matches as $outter {
        foreach $outter as $inner {
            if inner[value] == $length - 1;
                print winner!
                $winners = [$outter, $inner];
            }
        }
    }
*/
foreach ($matches as $outter_key => $outter) {
    foreach ($outter as $inner_key => $inner) {
        if ($inner['value'] == $length -1) {
            print "Winners are:\n";
            print "[$outter_key] " . $puzzle[$outter_key] . "\n[$inner_key] " . $puzzle[$inner_key] . "\n";
            $winners = [$outter_key, $inner_key];
            break;
        }
    }
}

/*
  Logic: Build a string of the matching characters
  Example: $answer = "qwugbnhrkplymcjaxefotvdxn"
  Logic:
    loop through each position {
        if $winner1[$pos] == $winner2[$pos] {
            $answer .= $winner1[$pos];
        }
    }
*/
for ($pos = 0; $pos < $length; $pos++) {
    if ($letter_array[$winners[0]][$pos] == $letter_array[$winners[1]][$pos]) {
        $answer .= $letter_array[$winners[0]][$pos];
    }
}

print "\nAnswer is: $answer\n";
