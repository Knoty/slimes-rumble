<?php

require_once('./BlobDB.php');

class BlobDBTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function returnSameHpWhichWereSaved()
    {
        $db = new BlobDB();
        $blob_number = $db->createBlob(13);
        list(, $hp) = $db->lookBlob($blob_number);
        $this->assertEquals(13, $hp);
    }

    function hpChanges()
    {
        return [
            [-5, 15],
            [3, 23],
            [0, 20]
        ];
    }

    /**
     * @test
     * @dataProvider hpChanges
     * @param $hp_change
     * @param $expected_hp
     */
    function checkHpChange($hp_change, $expected_hp)
    {
        $db = new BlobDB();
        $blob_number = $db->createBlob(20);
        $db->modifyBlob($blob_number, $hp_change);
        list(, $hp) = $db->lookBlob($blob_number);
        $this->assertEquals($expected_hp, $hp);
    }

    /** @test */
    function massLookToEmptyWorld()
    {
        $db = new BlobDB();
        $this->assertEquals([], $db->massLook());
    }

    /** @test */
    function massLookToOneCreatedBlob()
    {
        $db = new BlobDB();
        $db->createBlob(20);
        $this->assertEquals([20], $db->massLook());
    }

    /** @test */
    function massLookToTwoNMoreBlobs()
    {
        $db = new BlobDB();
        $db->createBlob(20);
        $db->createBlob(15);
        $this->assertEquals([20, 15], $db->massLook());
    }

    /**
     * throw exception & defence programming try
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $blob_number must be integer
     */
    function throwExceptionOnModifyBlobWhenInvalidFirstArgument()
    {
        $db = new BlobDB();
        $db->modifyBlob([], 1);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $hp_change must be integer
     */
    function throwExceptionOnModifyBlobWhenInvalidSecondArgument()
    {
        $db = new BlobDB();
        $db->modifyBlob(2, 'abc');
    }

    /**
     * @test
     * @expectedException LogicException
     * @expectedExceptionMessage no such blob:99
     */
    function throwExceptionOnModifyNonexistentBlob()
    {
        $db = new BlobDB();
        $db->modifyBlob(99, 1);
    }

    /** @test */
    function lookBlobReturnsBlobName()
    {
        $db = new BlobDB();
        $blob = $db->createBlob(10, 'testname');
        $this->assertEquals('testname', $db->lookBlob($blob)[0]);
    }
}