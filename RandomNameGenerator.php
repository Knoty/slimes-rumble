<?php
require_once ('NameGenerator.php');

class RandomNameGenerator implements NameGenerator
{
    /** @var string[] */
    private $syllables = ['nar', 'ka', 'wer', 'tok', 'far', 'ta', 'to', 'ko', 'tor', 'tak', 'kon', 'ro', 'de', 'kar', 'man', 'ga', 'rus', 'lan'];

    /** @return string */
    function getName()
    {
        $syllables_count = mt_rand(2, 3);
        $last_index = count($this->syllables) - 1;
        $name = "";
        for ($i = 0; $i < $syllables_count; ++$i) {
            $name = $name . $this->syllables[mt_rand(0, $last_index)];
        }
        return $name;
    }
}