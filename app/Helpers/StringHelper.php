<?php

use Illuminate\Support\Str;

if (!function_exists('str_random')) {
    function str_random($length = 11)
    {
        $uuid = Str::random($length);
        return $uuid;
    }
}

function strip_slash($text)
{
    return preg_replace('/[\/\\\\]+/', '/', $text);
}

function trimBase64($base64) {
    return rtrim($base64, '=');
}

function generateSlug($class, $field, $value) {
    
    $slug = Str::slug($value);
    $count = $class::where($field, $slug)->count();
    return $count ? $slug . '-' . $count : $slug;

}