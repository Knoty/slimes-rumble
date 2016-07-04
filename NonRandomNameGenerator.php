<?php
require_once ('NameGenerator.php');

class NonRandomNameGenerator implements NameGenerator
{
    /** @return string */
    public function getName()
    {
        $blob_name = 'name';
        return $blob_name;
    }
}