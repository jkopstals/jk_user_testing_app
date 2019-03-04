<?php
namespace App\Controllers;

class TestController extends Controller {

    public function start() {
        $app = \App\App::app();

        $repo = new \App\Repositories\TestRepository($app->db());
        $tests = $repo->findAll();

        $content = $app->view()->render('start_form.php',['tests' => $tests, 'errors' => $app->request()->getErrors()]);

        $app->response()->html($app->view()->render('layout.php',[
            'title' => 'Testa uzdevums',
            'content' => $content,
            'footer' => '<!-- footer -->'
        ]));

    }


    public function show() {
        $app = \App\App::app();
        
        $testRepo = new \App\Repositories\TestRepository($app->db());
        $respondentRepo = new \App\Repositories\RespondentRepository($app->db());
        $questionRepo = new \App\Repositories\QuestionRepository($app->db());
        $optionRepo = new \App\Repositories\OptionRepository($app->db());
        $answerRepo = new \App\Repositories\AnswerRepository($app->db());
        
        $errors = [];
        
        $respondent_uuid = $app->request()->postParam('respondent_uuid');
        if (!$respondent_uuid) {

            $name = $app->request()->postParam('name');
            $name = trim($name);
            $test_uuid = $app->request()->postParam('test_uuid');
            if (!$name) {
                $errors['name'] = 'Vārds ir obligāts'; 
            }
            if (strlen($name) >= 255) {
                $errors['name'] = 'Vārds ir pārāk garš'; 
            }
            if (!$test_uuid) {
                $errors['test_uuid'] = 'Testa izvēle ir obligāta'; 
            }
            
            if ($errors) {
                $app->request()->setErrors($errors);
                return $this->start();
            }
            $test = $testRepo->findByUUID($test_uuid);
        
            $respondent = new \App\Models\Respondent(['name' => $name, 'test_id' => $test->id]);
            $respondent = $respondentRepo->save($respondent);
            $respondent_uuid = $respondent->uuid;
        }
        if (!isset($test_uuid)) {
            $test_uuid = $app->request()->postParam('test_uuid');
        }

        $question_uuid = $app->request()->postParam('question_uuid');
        $option_uuid = $app->request()->postParam('option_uuid');
        if ($question_uuid && !$option_uuid) {
            $errors['option_uuid'] = 'Lai turpinātu, jāizvēlas kāda no atbildēm';
        }
        if ($option_uuid) {
            $answerSaved = $answerRepo->saveNewAnswerByUUIDs($respondent_uuid, $option_uuid);
        }

        //Next question to show
        $question = $questionRepo->findFirstUnansweredByRespondentUUID($respondent_uuid);

        if (!$question) {
            return $this->complete();
        }
        
        $options = $optionRepo->findByQuestionID($question->id);

        $respondentProgress = $respondentRepo->getTestProgressByUUID($respondent_uuid);

        $progress_percent = 0;
        if ($respondentProgress['question_count']) {
            $progress_percent = floor(($respondentProgress['answer_count'] / $respondentProgress['question_count'])*100);
        }

        $content = $app->view()->render('options_form.php',[
            'respondent_uuid' => $respondent_uuid, 
            'test_uuid' => $test_uuid, 
            'question_uuid' => $question->uuid, 
            'options' => $options,
            'progress_percent' => $progress_percent,
            'errors' => $errors]);

        $app->response()->html($app->view()->render('layout.php',[
            'title' => $question->description,
            'content' => $content,
            'footer' => 'Progress bar'. json_encode($respondentProgress).' <br />'.$respondent_uuid.'<br />'.json_encode($app->request()->postParams())
        ]));
    }

    
    public function complete() {
        $app = \App\App::app();
        
        $respondent_uuid = $app->request()->postParam('respondent_uuid');
        $respondentRepo = new \App\Repositories\RespondentRepository($app->db());

        $successSetCompleted = $respondentRepo->setCompletedByUUID($respondent_uuid);
        $testResult = $respondentRepo->getTestResultByUUID($respondent_uuid);
        $respondent = $respondentRepo->findByUUID($respondent_uuid);

        $app->response()->html($app->view()->render('layout.php',[
            'title' => "Paldies, $respondent->name!",
            'content' => 'Tu atbildēji pareizi uz '.$testResult['correct_count'].' no '.$testResult['answer_count'].' jautājumiem.',
            'footer' => 'Paldies par dalību!'
        ]));
        
    }
}
