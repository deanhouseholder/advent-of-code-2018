<?php

$puzzle = file('puzzle.txt', FILE_IGNORE_NEW_LINES);
$current = 0;
$values_array = [0 => 1];

while (true) {
    foreach ($puzzle as $key=>$value) {
        $end = $current + $value;
        print "$current $value = $end<br>\n";
        $current = $end;
        $values_array[$current]++;
        if ($values_array[$current] == 2) {
            print "Winner is: $current!<br>\n";
            die;
        }
    }
}
