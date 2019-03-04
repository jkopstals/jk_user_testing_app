<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Respondent;

/**
 * Respondent model unit tests
 */

final class RespondentTest extends SetUpTestCase
{
    private function getTestRespondentArray() {
        return [
            'name' => 'Jānis Priede',
            'test_id' => 1
        ];
    }

    public function testCanConstructWithName(): void
    {
        $name = 'Jānis';
        $test_id = 1;
        $respondent = new Respondent(['name' => $name, 'test_id' => $test_id]);
        $this->assertInstanceOf(
            Respondent::class,
            $respondent
        );
        $this->assertEquals($respondent->name, $name);
    }

    public function testRespondentCanBeSavedToDatabase(): void
    {
        $db = \App\App::app()->db();

        $repo = new \App\Repositories\RespondentRepository($db);

        $respondentInputData = $this->getTestRespondentArray();
        $respondent = new Respondent($respondentInputData);
        
        $repo->save($respondent);

        $this->assertEquals(
            $respondent->name,
            $respondentInputData['name']
        );
    }
}