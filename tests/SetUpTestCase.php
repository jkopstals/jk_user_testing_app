<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\App;
use App\Database;

/**
 * Respondent model unit tests
 */

class SetUpTestCase extends TestCase
{

    public function setUp() : void
    {
        $db = App::app()->db();
        $db->createSchema();
        $db->seed();

        parent::setUp();
    }

    public function tearDown() : void
    {
        $db = App::app()->db();
        $db->dropSchema();

        parent::tearDown();
    }

}