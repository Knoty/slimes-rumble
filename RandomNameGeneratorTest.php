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

    /** @test */
    function minMaxNameLength()
    {
        $generator = new RandomNameGenerator();
        $names = [];

        for ($i=0; $i < 1000000; ++$i)
            $names[$i] = $generator->getName();

        $name_lengths = array_map("strlen", $names);
        $min_name_length = min($name_lengths);
        $max_name_length = max($name_lengths);
        $this->assertEquals(4, $min_name_length);
        $this->assertEquals(9, $max_name_length);
    }
}
