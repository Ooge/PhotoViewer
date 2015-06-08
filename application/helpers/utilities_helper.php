<?php
// This file contains many different utility functions that are available
// Globally throughout the site

// This function generates a random string a-z A-Z 0-9 with a set limit
function generate_random($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWQYZ1234567890';
    $string = '';
    for ($i = 0; $i < 256; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return substr($string, mt_rand(0, strlen($string)-$length), $length-1);
}
// This function takes the time in seconds since epoch and converts it into
// the format x minutes ago, where x is the number of minutes
// It also handles seconds, hours, days, weeks, months and years
function time_ago($time) {

    $time = time() - $time; // to get the time since that moment
    // The different times that are available
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    // Loop through all the times to see which one suits our time
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        // return the time with an s appended if the time is multiple
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}
// The simple function which checks if a date of birth gives an age which is
// below 13 years old.
function under_thirteen($dob) {
    $dob = strtotime($dob);
    //The age to be over, over +13
    $min = strtotime('+13 years', $dob);

    if(time() < $min)  {
        return true;
    }
    return false;
}
// Our custom password hashing function which implements Blowfish with a cost 14
function ry_password_hash($password) {
    $new_pass = password_hash($password, PASSWORD_BCRYPT, array('cost' => 14));
    return $new_pass;
}
// This strips HTML special characters from a string
function strip_html($str)
{
    $t = preg_replace('/<[^<|>]+?>/', '', htmlspecialchars_decode($str));
    $t = htmlentities($t, ENT_QUOTES, "UTF-8");
    return $t;
}
// Returns if we are running on HTTPS or HTTP
function is_https() {
    return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;
}
// function converts a link (type string) to a clickable href'd link
function convert_link_to_clickable($link) {
    return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $link);
}
