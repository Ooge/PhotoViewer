<?php

function generate_random($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWQYZ1234567890';
    $string = '';
    for ($i = 0; $i < 256; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return substr($string, mt_rand(0, strlen($string)-$length), $length-1);
}

function time_ago($time) {

    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}

function under_thirteen($dob) {
    $dob = strtotime($dob);
    //The age to be over, over +13
    $min = strtotime('+13 years', $dob);

    if(time() < $min)  {
        return true;
    }
    return false;
}

function ry_password_hash($password) {
    $new_pass = password_hash($password, PASSWORD_BCRYPT, array('cost' => 14));
    return $new_pass;
}

function strip_html($str)
{
    $t = preg_replace('/<[^<|>]+?>/', '', htmlspecialchars_decode($str));
    $t = htmlentities($t, ENT_QUOTES, "UTF-8");
    return $t;
}

function is_https() {
  return
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || $_SERVER['SERVER_PORT'] == 443;
}

function convert_link_to_clickable($link) {
  return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $link);
}
