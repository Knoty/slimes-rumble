<?php

require_once('./BlobDB.php');

class BlobStorageTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function returnSameHpWhichWereSaved()
    {
        $db = new BlobDB;
        $blob_number = $db->createBlob(13);
        $this->assertEquals(13, $db->lookBlob($blob_number));
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
        $db = new BlobDB;
        $blob_number = $db->createBlob(20);
        $db->modifyBlob($blob_number, $hp_change);
        $this->assertEquals($expected_hp, $db->lookBlob($blob_number));
    }
}