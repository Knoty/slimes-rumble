<?php
require_once ('NameGenerator.php');

class RandomNameGenerator implements NameGenerator
{

    /** @return string */
    function getName()
    {
        return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 9);
    }
}