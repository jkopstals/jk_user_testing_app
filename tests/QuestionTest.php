<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Question;

/**
 * Respondent model unit tests
 */

final class QuestionTest extends SetUpTestCase
{

    public function testCanConstructWithDescription(): void
    {
        $description = 'Example question';
        $question = new Question(['description' => $description]);
        $this->assertInstanceOf(
            Question::class,
            $question
        );
        $this->assertEquals($question->description, $description);
    }

    public function testQuestionCanBeSavedToDatabase(): void
    {
        $db = \App\App::app()->db();

        $repo = new \App\Repositories\QuestionRepository($db);

        $description = 'Example question';
        $test_id = 1;
        $question = new Question(['description' => $description, 'test_id' => $test_id]);
        
        $question = $repo->save($question);

        $this->assertEquals(
            $question->description,
            $description
        );
        $this->assertEquals(
            $question->test_id,
            $test_id
        );

        $this->assertObjectHasAttribute('id', $question);
        $this->assertIsInt($question->id);
    }

    public function testCanGetQuestionsFromDatabase(): void
    {
        $db = \App\App::app()->db();

        $repo = new \App\Repositories\QuestionRepository($db);

        $questions = $repo->findAll();
        $this->assertIsArray($questions);
        
        $this->assertInstanceOf(
            Question::class,
            $questions[0]
        );
        $questions = $questions[0];
        $this->assertObjectHasAttribute('id', $questions);
        $this->assertObjectHasAttribute('description', $questions);
        $this->assertObjectHasAttribute('test_id', $questions);
        $this->assertObjectHasAttribute('created_at', $questions);
        $this->assertObjectHasAttribute('uuid', $questions);
        $this->assertObjectHasAttribute('sequence', $questions);
        $this->assertIsInt($questions->id);
        $this->assertIsInt($questions->test_id);
        $this->assertIsString($questions->description);
        $this->assertIsString($questions->uuid);
    }
}