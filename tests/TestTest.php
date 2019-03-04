<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Test;

/**
 * Respondent model unit tests
 */

final class TestTest extends SetUpTestCase
{

    public function testCanConstructWithName(): void
    {
        $name = 'Example test';
        $test = new Test(['name' => $name]);
        $this->assertInstanceOf(
            Test::class,
            $test
        );
        $this->assertEquals($test->name, $name);
    }

    public function testTestCanBeSavedToDatabase(): void
    {
        $db = \App\App::app()->db();

        $repo = new \App\Repositories\TestRepository($db);

        $name = 'Example test';
        $test = new Test(['name' => $name]);
        
        $test = $repo->save($test);

        $this->assertEquals(
            $test->name,
            $name
        );

        $this->assertObjectHasAttribute('id', $test);
        $this->assertIsInt($test->id);
    }

    public function testCanGetTestsFromDatabase(): void
    {
        $db = \App\App::app()->db();

        $repo = new \App\Repositories\TestRepository($db);

        $tests = $repo->findAll();
        $this->assertIsArray($tests);
        
        $this->assertInstanceOf(
            Test::class,
            $tests[0]
        );
        $test = $tests[0];
        $this->assertObjectHasAttribute('id', $test);
        $this->assertObjectHasAttribute('name', $test);
        $this->assertObjectHasAttribute('created_at', $test);
        $this->assertObjectHasAttribute('published_at', $test);
        $this->assertObjectHasAttribute('uuid', $test);
        $this->assertObjectHasAttribute('sequence', $test);
        $this->assertIsInt($test->id);
        $this->assertIsString($test->name);
        $this->assertIsString($test->uuid);
    }
}