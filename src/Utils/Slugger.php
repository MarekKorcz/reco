<?php

namespace App\Utils;

class Slugger
{
    static public function slugify($string)
    {
        //Lower case everything
        $lowerCasedString = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $alphanumericString = preg_replace("/[^a-z0-9_\s-]/", "", $lowerCasedString);
        //Clean up multiple dashes or whitespaces
        $withoutMultipleDashesOrWhitespacesString = preg_replace("/[\s-]+/", " ", $alphanumericString);
        //Convert whitespaces and underscore to dash
        return preg_replace("/[\s_]/", "-", $withoutMultipleDashesOrWhitespacesString);
    }
}