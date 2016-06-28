<?php

require_once('./GameEngine.php');
require_once('./BlobDB.php');

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
        $this->assertGreaterThan(14, $this->engine->look($blob)); // FIXME: в таком виде этот тест не отличается от следующего.
    }                                              // Если мы проверяем только лишь то, что после `create()` блоб вообще существует
                                                   // (самую базовую функциональность), то достаточно `assertNotNull()`.

    /** @test */
    function startHpWithinADesignatedInterval()
    {
        $blob = $this->engine->create();
        $this->assertGreaterThan(14, $this->engine->look($blob));
        $this->assertLessThan(27, $this->engine->look($blob));
    }

    /** @test */
    function kickBlob()
    {
        $blob = $this->engine->create();
        $this->engine->kick($blob);
        $this->assertGreaterThanOrEqual(15 - $this->engine->lastModify(), $this->engine->look($blob));
        $this->assertLessThanOrEqual(26 - $this->engine->lastModify(), $this->engine->look($blob));
    }

    /** @test */
    function kickBlobCheckDamage() // Хороший, обобщённый тест, который проверяет основную функциональность `kick()`
    {
        $blob = $this->engine->create();
        $start_hp = $this->engine->look($blob);
        $this->engine->kick($blob);
        $result_hp = $this->engine->look($blob);
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
        $hp_original = $this->engine->look($blob);
        $this->engine->setDamageAmountGenerator($generator);

        $this->engine->kick($blob);

        $hp_difference = $hp_original - $this->engine->look($blob);
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
        $this->assertGreaterThan(3, $this->engine->look($blob));
        $this->assertLessThan(26, $this->engine->look($blob));
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
        $this->assertGreaterThanOrEqual(-117, $this->engine->look($blob));
        $this->assertLessThanOrEqual(15, $this->engine->look($blob));
    }

    /** @test */
    function healingBlob()
    {
        $blob = $this->engine->create();
        $this->engine->heal($blob);
        $this->assertGreaterThan(15, $this->engine->look($blob)); // FIXME: так мы привязываем этот тест к диапазону жизней, прописанному в методе `create()`
        $this->assertLessThan(38, $this->engine->look($blob));     // на самом деле мы хотим узнать, что после вызова `heal()` у блоба больше жизней, чем сразу после `create()`
    }                                              // То есть, этот тест должен выглядеть так, как `kickBlobCheckDamage()`

    /** @test
     * тест старого дизайна, в новом должен проваливаться
     */
    function createTwoBlobsHealingOneAndCompareThey()
    {
        $blob1 = $this->engine->create();
        $blob2 = $this->engine->create();
        $i = 0;
        $times_to_run = 11;
        while ($i++ < $times_to_run) // FIXME: можно и так, но разве не достаточно было вызвать heal всего один раз в этом тесте?
        {
            $this->engine->heal($blob2);
        }
        $this->assertGreaterThan($this->engine->look($blob1), $this->engine->look($blob2));
        $this->assertLessThan($this->engine->look($blob2), $this->engine->look($blob1)); //FIXME: подразумевается, что мы доверяем тому, что `assertGreaterThan()` обратен `assertLessThan`.
    }

    /** @test */
    function kick2Blobs()
    {
        $blob1 = $this->engine->create();
        $blob2 = $this->engine->create();
        $this->engine->kick($blob1);
        $this->engine->kick($blob2);
        $this->assertGreaterThan(3, $this->engine->look($blob1));
        $this->assertLessThan(26, $this->engine->look($blob1));
        $this->assertGreaterThan(3, $this->engine->look($blob2));
        $this->assertLessThan(26, $this->engine->look($blob2));
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
        $this->assertEquals($this->engine->getWorldState(), []);
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
}