<?php

class RandomNameGeneratorTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function generatesStrings()
    {
        $generator = new RandomNameGenerator();
        $name = $generator->getName();
        $this->assertInternalType('string', $name);
    }
}
