<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$puzzle = file('puzzle.txt', FILE_IGNORE_NEW_LINES);
sort($puzzle);

/*
$events = [
    0 => [
        [year]    => 1518
        [month]   => 03
        [day]     => 16
        [hour]    => 00
        [minute]  => 04
        [guard]   => 1973
        [awake]   => 1
        [message] => Guard #1973 begins shift
    ],
    1 => [
        [year]    => 1518
        [month]   => 03
        [day]     => 16
        [hour]    => 00
        [minute]  => 34
        [guard]   => 1973
        [awake]   => 0
        [message] => falls asleep
    ],
    ...
]
*/

// Import puzzle data and expand into nested array
foreach ($puzzle as $key => $value) {
    if (preg_match('~^\[([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})\] (.*)$~', $value, $matches)) {
        $message = $matches[6];

        if (preg_match('~^Guard #([0-9]+) .*~', $message, $message_matches)) {
            $guard = $message_matches[1];
            $awake = 1;
        } else {
            if (strpos($message, 'falls') === false) {
                $awake = 1;
            } else {
                $awake = 0;
            }
        }
        $events[] = [
            'year'    => $matches[1],
            'month'   => $matches[2],
            'day'     => $matches[3],
            'hour'    => $matches[4],
            'minute'  => $matches[5],
            'guard'   => $guard,
            'awake'   => $awake,
            'message' => $message,
        ];
    } else {
        die("\nError with preg_match!\n");
    }
}

/*

$guard_sleeping = [
    $guard_id => [
        $minute => $times_asleep_this_min,
        $minute => $times_asleep_this_min,
        ...
    ],
]

Example:

$guard_sleeping = [
    1973 => [
        0 => 3,
        1 => 4,
    ],
    1949 => [
        0 => 8,
        1 => 5,
    ],
    ...
]

*/

// Create a nested array of sleep by guard id by minute
$guard_sleeping = [];
$last_guard_id = 0;
$sleep_started_min = -1;

foreach ($events as $key => $event) {
    if ($event['awake'] == 0) {
        // don't write to array yet
        $last_guard_id = $event['guard'];
        $sleep_started_min = $event['minute'];
    } elseif ($event['message'] == 'wakes up') {
        // increment every minute that he was asleep
        for ($min=abs($sleep_started_min); $min<$event['minute']; $min++) {
            if (!isset($guard_sleeping[$event['guard']][$min])) {
                $guard_sleeping[$event['guard']][$min] = 0;
            }
            $guard_sleeping[$event['guard']][$min] += 1;
        }
    }
}

// Sorting the array to make it pretty
ksort($guard_sleeping);
foreach ($guard_sleeping as $guard_id => $minutes) {
    ksort($minutes);
    $guard_sleeping[$guard_id] = $minutes;
}

// Find the guard who spent the most time sleeping
$highest_seen = 0;
$guard_id = 0;
foreach ($guard_sleeping as $guard_id => $minutes) {
    $sum = array_sum($minutes);
    if ($highest_seen < $sum) {
        $highest_seen = $sum;
        $guard_highest_id = $guard_id;
    }
}

print "Guard who sleeps the most: " . $guard_highest_id . "\n";

// Find the minute he most often slept through
$max_minutes_slept = max($guard_sleeping[$guard_highest_id]);
foreach ($guard_sleeping[$guard_highest_id] as $minute => $slept) {
    if ($slept == $max_minutes_slept) {
        $max_minute = $minute;
        print "Minute the guard sleeps the most: " . $minute . "\n";
    }
}

$total = $guard_highest_id * $max_minute;
print "Final answer is: " . $total . "\n";
