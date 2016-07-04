<?php

require_once('./BlobDB.php');

class BlobDBTest extends PHPUnit_Framework_TestCase
{
    /** @var BlobDB */
    private $db;
    
    function setUp ()
    {
        $this->db = new BlobDB();
    }
    
    /** @test */
    function createDefaultName()
    {
        $blob_number = $this->db->createBlob(10);
        $this->assertEquals('name', $this->db->lookBlob($blob_number)[0]);
    }
    
    /** @test */
    function returnSameHpWhichWereSaved()
    {
        $blob_number = $this->db->createBlob(13);
        list(, $hp) = $this->db->lookBlob($blob_number);
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
        $blob_number = $this->db->createBlob(20);
        $this->db->modifyBlob($blob_number, $hp_change);
        list(, $hp) = $this->db->lookBlob($blob_number);
        $this->assertEquals($expected_hp, $hp);
    }

    /** @test */
    function massLookToEmptyWorld()
    {
        $this->assertEquals([], $this->db->massLook());
    }

    /** @test */
    function massLookToOneCreatedBlob()
    {
        $this->db->createBlob(20);
        $this->assertEquals([20], $this->db->massLook());
    }

    /** @test */
    function massLookToTwoNMoreBlobs()
    {
        $this->db->createBlob(20);
        $this->db->createBlob(15);
        $this->assertEquals([20, 15], $this->db->massLook());
    }

    /**
     * throw exception & defence programming try
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $blob_number must be integer
     */
    function throwExceptionOnModifyBlobWhenInvalidFirstArgument()
    {
        $this->db->modifyBlob([], 1);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $hp_change must be integer
     */
    function throwExceptionOnModifyBlobWhenInvalidSecondArgument()
    {
        $this->db->modifyBlob(2, 'abc');
    }

    /**
     * @test
     * @expectedException LogicException
     * @expectedExceptionMessage no such blob:99
     */
    function throwExceptionOnModifyNonexistentBlob()
    {
        $this->db->modifyBlob(99, 1);
    }

    /** @test */
    function lookBlobReturnsBlobName()
    {
        $blob_number = $this->db->createBlob(10, 'testname');
        $this->assertEquals('testname', $this->db->lookBlob($blob_number)[0]);
    }

    /** @test */
    function massLookNamesToEmptyWorld()
    {
        $this->assertEquals([], $this->db->massLookNames());
    }

    /** @test */
    function massLookNamesToOneCreatedBlob()
    {
        $this->db->createBlob(20, 'testname');
        $this->assertEquals(['testname'], $this->db->massLookNames());
    }

    /** @test */
    function massLookNamesToTwoNMoreBlobs()
    {
        $this->db->createBlob(20);
        $this->db->createBlob(15, 'testname');
        $this->db->createBlob(10, 'testname2');
        $this->assertEquals(['name', 'testname', 'testname2'], $this->db->massLookNames());
    }
}