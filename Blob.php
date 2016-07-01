<?php

class Blob
{
    /** @var string */
    private $name;

    /** @var int */
    private $hp;

    public function __construct($name, $hp)
    {
        $this->name = $name;
        $this->hp = $hp;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getHP()
    {
        return $this->hp;
    }
}