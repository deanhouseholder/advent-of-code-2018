<?php

$puzzle = file('puzzle.txt');
$current = 0;
$values_array = [0 => 1];

while (true) {
    foreach ($puzzle as $key=>$value) {
        $value = trim($value);
        $end = $current + $value;
        print "From: $current, $value = $end<br>\n";
        $current = $end;
        $values_array[$current]++;
        if ($values_array[$current] == 2) {
            print "Winner is: $current!<br>\n";
            die;
        }
    }
}
