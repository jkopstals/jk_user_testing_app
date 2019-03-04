<?php
namespace App\Controllers;

class ApiAnswerController extends Controller {


    public function store() {
        $app = \App\App::app();
        
        //var_dump($app->request()->postParams());
        
        $testRepo = new \App\Repositories\TestRepository($app->db());
        $respondentRepo = new \App\Repositories\RespondentRepository($app->db());
        $questionRepo = new \App\Repositories\QuestionRepository($app->db());
        $optionRepo = new \App\Repositories\OptionRepository($app->db());
        $answerRepo = new \App\Repositories\AnswerRepository($app->db());
        
        $errors = [];
        
        $respondent_uuid = $app->request()->postParam('respondent_uuid');
        $test_uuid = $app->request()->postParam('test_uuid');
        $question_uuid = $app->request()->postParam('question_uuid');
        $option_uuid = $app->request()->postParam('option_uuid');

        if ($question_uuid && !$option_uuid) {
            $errors['option_uuid'] = 'Lai turpinātu, jāizvēlas kāda no atbildēm';
            $app->response()->json(['error' => $errors], 422);
            return;
        }

        if ($option_uuid) {
            $answerSaved = $answerRepo->saveNewAnswerByUUIDs($respondent_uuid, $option_uuid);
        }

        //Next question to show
        $question = $questionRepo->findFirstUnansweredByRespondentUUID($respondent_uuid);

        if (!$question) {
            $app->response()->json(['success' => true, 'next_question' => null], 201);
            return;
        }
        
        $options = $optionRepo->findByQuestionID($question->id);

        $respondentProgress = $respondentRepo->getTestProgressByUUID($respondent_uuid);

        $progress_percent = 0;
        if ($respondentProgress['question_count']) {
            $progress_percent = floor(($respondentProgress['answer_count'] / $respondentProgress['question_count'])*100);
        }

        $app->response()->json(['success' => true, 'progress_percent' => $progress_percent, 'next_question' => [
            'title' => $question->description,
            'respondent_uuid' => $respondent_uuid, 
            'test_uuid' => $test_uuid, 
            'question_uuid' => $question->uuid, 
            'options' => $options,
        ]], 201);
        return;
    }
}
