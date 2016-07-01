<?php

require_once ('./Blob.php');
require_once ('./BlobDB.php');

class BlobTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function data()
    {
        $blob = new Blob('name', 15);
        $this->assertEquals('name', $blob->getName());
        $this->assertEquals(15, $blob->getHP());
    }
}
