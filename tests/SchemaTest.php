<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\App;

/**
 * Database object unit tests
 */

final class SchemaTest extends TestCase
{
    public function testCanQueryDatabase(): void
    {
        $db = App::app()->db();
        $response = $db->query('SELECT NOW()')->fetch();
        $this->assertIsArray($response);
    }

    public function testCanCreateSchema(): void
    {
        $db = App::app()->db();
        $schemaCreated = $db->createSchema();
        $this->assertTrue($schemaCreated);
    }

    public function testCanDropSchema(): void
    {
        $db = App::app()->db();
        $schemaDropped = $db->dropSchema();
        $this->assertTrue($schemaDropped);
    }

}