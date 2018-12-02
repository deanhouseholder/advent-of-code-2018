<?php

$puzzle = file('puzzle.txt');
$current = 0;

foreach ($puzzle as $value) {
    $current += $value;
}

print "The answer is: $current!";
