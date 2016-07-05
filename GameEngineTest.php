<?php

require_once ('./GameEngine.php');
require_once ('./BlobDB.php');
require_once ('./Blob.php');

class GameEngineTest extends PHPUnit_Framework_Testcase
{
    /** @var GameEngine */
    private $engine;

    function setUp()
    {
        $this->engine = new GameEngine();
        $db = new BlobDB();
        $this->engine->setDB($db);
    }

    /** @test */
    function existingOfCreatedBlob()
    {
        $blob = $this->engine->create();
        $this->assertNotNull($this->engine->look($blob));
    }

    /** @test */
    function startHpWithinADesignatedInterval()
    {
        $blob = $this->engine->create();
        $this->assertGreaterThan(14, $this->engine->look($blob)->getHP());
        $this->assertLessThan(27, $this->engine->look($blob)->getHP());
    }

    /** @test */
    function kickBlob()
    {
        $blob = $this->engine->create();
        $this->engine->kick($blob);
        $this->assertGreaterThanOrEqual(15 - $this->engine->lastModify(), $this->engine->look($blob)->getHP());
        $this->assertLessThanOrEqual(26 - $this->engine->lastModify(), $this->engine->look($blob)->getHP());
    }

    /** @test */
    function kickBlobCheckDamage() // Хороший, обобщённый тест, который проверяет основную функциональность `kick()`
    {
        $blob = $this->engine->create();
        $start_hp = $this->engine->look($blob)->getHP();
        $this->engine->kick($blob);
        $result_hp = $this->engine->look($blob)->getHP();
        $hp_diff = $start_hp - $result_hp;
        $this->assertEquals($hp_diff, $this->engine->lastModify());
    }

    function DamageAmounts()
    {
        return [
            [0],
            [1],
            [3],
            [5]
        ];
    }

    /**
     * @test
     * @dataProvider DamageAmounts
     * @param $damage_amount
     */
    function kickReducedHpAmount($damage_amount)
    {
        $generator = $this->getMock('DamageAmountGenerator');
        $generator
            ->method('getDamageAmount')
            ->willReturn($damage_amount);

        $blob = $this->engine->create();
        $hp_original = $this->engine->look($blob)->getHP();
        $this->engine->setDamageAmountGenerator($generator);

        $this->engine->kick($blob);

        $hp_difference = $hp_original - $this->engine->look($blob)->getHP();
        $this->assertEquals($damage_amount, $hp_difference);
    }

    /** @test */
    function kickAsksDamageAmountGenerator()
    {
        $generator = $this->getMock('DamageAmountGenerator');
        $generator
            ->expects($this->once())
            ->method('getDamageAmount');

        $blob = $this->engine->create();
        $this->engine->setDamageAmountGenerator($generator);
        $this->engine->kick($blob);
    }

    /** @test */
    function kickBlobCheckMaxArguments()
    {
        $blob = $this->engine->create();
        $this->engine->kick($blob);
        $this->assertGreaterThan(3, $this->engine->look($blob)->getHP());
        $this->assertLessThan(26, $this->engine->look($blob)->getHP());
    }

    /** @test */
    function kickBlob12Times()
    {
        $blob = $this->engine->create();
        $i = 0;
        $times_to_run = 12;
        while ($i++ < $times_to_run)
        {
            $this->engine->kick($blob);
        }
        $this->assertGreaterThanOrEqual(-117, $this->engine->look($blob)->getHP());
        $this->assertLessThanOrEqual(15, $this->engine->look($blob)->getHP());
    }

    /** @test */
    function healingBlob()
    {
        $blob = $this->engine->create();
        $start_hp = $this->engine->look($blob)->getHP();
        $this->engine->heal($blob);
        $result_hp = $this->engine->look($blob)->getHP();
        $hp_diff = $result_hp - $start_hp;
        $this->assertEquals($hp_diff, $this->engine->lastModify());
    }

    /** @test */
    function createTwoBlobsHealingOneAndCompareThey()
    {
        $blob1 = $this->engine->create();
        $blob2 = $this->engine->create();
        $i = 0;
        $times_to_run = 11;
        while ($i++ < $times_to_run) // есть шанс на хил по 1хп 11 раз подряд
        {
            $this->engine->heal($blob2);
        }
        $this->assertGreaterThan($this->engine->look($blob1)->getHP(), $this->engine->look($blob2)->getHP());
    }

    /** @test */
    function kick2Blobs()
    {
        $blob1 = $this->engine->create();
        $blob2 = $this->engine->create();
        $this->engine->kick($blob1);
        $this->engine->kick($blob2);
        $this->assertGreaterThan(3, $this->engine->look($blob1)->getHP());
        $this->assertLessThan(26, $this->engine->look($blob1)->getHP());
        $this->assertGreaterThan(3, $this->engine->look($blob2)->getHP());
        $this->assertLessThan(26, $this->engine->look($blob2)->getHP());
    }

    /**
     * FIXME: в логике отсутсвует ограничение на хил здоровых блобов
     */
    function fullHPHealTest()
    {
        $this->markTestIncomplete('see FIXME comment on my definition');
        $blob = $this->engine->create();
        $this->engine->heal($blob);
        $this->assertEquals(0, $this->engine->lastModify());
    }

    /** @test */
    function lastModifyCreationTest()
    {
        $this->engine->create();
        $this->assertEquals(0, $this->engine->lastModify());
    }

    /** @test */
    function saveWorldStatus()
    {
        $test_world_state = 'test_state.db';
        $this->engine->create();
        $this->engine->save($test_world_state);
        $second_engine = new GameEngine();
        $second_engine->restore($test_world_state);

        $this->assertEquals($this->engine->getWorldState(), $second_engine->getWorldState());
    }

    /** @test */
    function emptyWorldState()
    {
        $this->assertEquals([], $this->engine->getWorldState());
    }

    /** @test */
    function oneObjectWorldState()
    {
        $this->engine->create();
        $this->assertCount(1, $this->engine->getWorldState());
    }

    /** @test */
    function twoObjectsWorldState()
    {
        $this->engine->create();
        $this->engine->create();
        $this->assertCount(2, $this->engine->getWorldState());
    }

    /** @test */
    function fullDataOnEmptyWorld()
    {
        $this->assertEquals([], $this->engine->getFullData());
    }

    /** @test */
    function fullDataOnOneBlob()
    {
        $this->engine->create();
        $fullData = $this->engine->getFullData();
        $this->assertNotEquals([], $fullData);
        $this->assertInternalType("array", $fullData);
        $this->assertInstanceOf("Blob", $fullData[0]);
    }

    /** @test */
    function fullDataReturnsAmountOfCreatedBlobs()
    {
        $this->engine->create();
        $this->engine->create();
        $this->engine->create();
        $blobs = $this->engine->getFullData();
        $this->assertCount(3, $blobs);
    }

    /** @test
     * here we try to check that foreach can be use
     */
    function fullDataReturnsFullRealData()
    {
        $this->engine->create();
        $this->engine->create();
        $this->engine->create();
        $blobs = $this->engine->getFullData();
        foreach ($blobs as $blob)
        {
            $this->assertGreaterThan(0, strlen($blob->getName()));
            $this->assertGreaterThan(0, $blob->getHP());
        };
    }

    /** @test */
    function fullDataDisplaysNames()
    {
        $db = new BlobDB();
        $db->createBlob(10, 'testname');
        $this->engine->setDB($db);

        $blob = $this->engine->getFullData()[0];
        $name = $blob->getName();
        $this->assertEquals('testname', $name);
    }

    /** @test */
    function created2BlobsHaveDifferentNames()
    {
        $blob1 = $this->engine->create();
        $blob2 = $this->engine->create();
        $this->assertNotEquals($this->engine->look($blob1)->getName(), $this->engine->look($blob2)->getName());
    }

    /** @test */
    function restoreShouldNotBrakeNonexistentStateFile()
    {
        @unlink('nonexistent');
        $this->engine->restore('nonexistent');
    }

    /** @test */
    function saveSavesNames()
    {
        $db = new BlobDB();
        $db->createBlob(10, 'SavedName');
        $this->engine->setDB($db);
        $this->engine->save('test_state.db');

        $engine = new GameEngine();
        $engine->restore('test_state.db');
        $blobs = $engine->getFullData();

        $this->assertCount(1, $blobs);
        $this->assertEquals('SavedName', $blobs[0]->getName());
    }

    function tearDown()
    {
        @unlink('nonexistent');
    }
}