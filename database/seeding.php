<?php
$now = date("Y-m-d H:i:s");

$seed = [
    'tests' => [
        ['name' => 'Matemātika', 'sequence' => 1, 'published_at' => $now, 
            'questions' => [
                    ['description' => '1+1=?', 'sequence' => 1, 'options' => [
                            ['label' => '2', 'sequence' => 1, 'is_correct_answer' => 1],
                            ['label' => '3', 'sequence' => 2, 'is_correct_answer' => 0],
                            ['label' => '4', 'sequence' => 3, 'is_correct_answer' => 0],
                        ]
                    ],
                
                    ['description' => 'sqrt(9)=?', 'sequence' => 2, 'options' => [
                        ['label' => '2', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => '3', 'sequence' => 2, 'is_correct_answer' => 1],
                        ['label' => '4', 'sequence' => 3, 'is_correct_answer' => 0],
                    ]],
                    ['description' => 'pow(9^2)=?', 'sequence' => 3, 'options' => [
                        ['label' => '9', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => '81', 'sequence' => 2, 'is_correct_answer' => 1],
                        ['label' => '18', 'sequence' => 3, 'is_correct_answer' => 0],
                        ['label' => '100', 'sequence' => 4, 'is_correct_answer' => 0],
                        ['label' => '99', 'sequence' => 5, 'is_correct_answer' => 0],
                        ['label' => '999', 'sequence' => 6, 'is_correct_answer' => 0],
                    ]]
            ]
        ],
        ['name' => 'Matemātika #2', 'sequence' => 2, 'published_at' => $now, 
            'questions' => [
                    ['description' => '10+10=?', 'sequence' => 1, 'options' => [
                        ['label' => '20', 'sequence' => 1, 'is_correct_answer' => 1],
                        ['label' => '30', 'sequence' => 2, 'is_correct_answer' => 0],
                        ['label' => '40', 'sequence' => 3, 'is_correct_answer' => 0],
                    ]],
                    ['description' => 'sqrt(81)=?', 'sequence' => 2, 'options' => [
                        ['label' => '8', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => '9', 'sequence' => 2, 'is_correct_answer' => 1],
                        ['label' => '99', 'sequence' => 3, 'is_correct_answer' => 0],
                        ['label' => '3', 'sequence' => 3, 'is_correct_answer' => 0],
                    ]],
                    ['description' => 'pow(2^2)=?', 'sequence' => 3, 'options' => [
                        ['label' => '9', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => '81', 'sequence' => 2, 'is_correct_answer' => 0],
                        ['label' => '18', 'sequence' => 3, 'is_correct_answer' => 0],
                        ['label' => '100', 'sequence' => 4, 'is_correct_answer' => 0],
                        ['label' => '4', 'sequence' => 5, 'is_correct_answer' => 1],
                        ['label' => '999', 'sequence' => 6, 'is_correct_answer' => 0],
                    ]]
                ]
        ],
        ['name' => 'PHP', 'sequence' => 3, 'published_at' => $now, 
            'questions' => [
                    ['description' => '______ will display everything about the PHP installation, including the modules, version, variables, etc.', 'sequence' => 1, 'options' => [
                        ['label' => 'info()', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => 'phpinfo()', 'sequence' => 2, 'is_correct_answer' => 1],
                        ['label' => 'phpdata()', 'sequence' => 3, 'is_correct_answer' => 0],
                        ['label' => 'php()', 'sequence' => 4, 'is_correct_answer' => 0],
                    ]],
                    ['description' => 'Which of the following is the correct way to create an array in PHP?', 'sequence' => 2, 'options' => [
                        ['label' => '$animals = array (Cat, Dog, Horse);', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => '$animals = array (“Cat”, “Dog”, “Horse”);', 'sequence' => 2, 'is_correct_answer' => 1],
                        ['label' => '$animals = array [Cat, Dog, Horse];', 'sequence' => 3, 'is_correct_answer' => 0],
                        ['label' => '$animals = “Cat”, “Dog”, “Horse”;', 'sequence' => 3, 'is_correct_answer' => 0],
                    ]],
                    ['description' => 'Which of the following is not a PHP magic constant?', 'sequence' => 3, 'options' => [
                        ['label' => '__FUNCTION__', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => '__FILE__', 'sequence' => 2, 'is_correct_answer' => 0],
                        ['label' => '__NAMESPACE__', 'sequence' => 3, 'is_correct_answer' => 0],
                        ['label' => '__CLASS__', 'sequence' => 4, 'is_correct_answer' => 0],
                        ['label' => '__TIME__', 'sequence' => 5, 'is_correct_answer' => 1],
                    ]],
                    ['description' => 'What does PHP stand for?', 'sequence' => 4, 'options' => [
                        ['label' => 'Private Home Page', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => 'Personal Hypertext Processor', 'sequence' => 2, 'is_correct_answer' => 0],
                        ['label' => 'PHP: Hypertext Preprocessor', 'sequence' => 3, 'is_correct_answer' => 1],
                    ]],
                    ['description' => 'What is the correct way to add 1 to the $count variable?', 'sequence' => 5, 'options' => [
                        ['label' => 'count++;', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => '$count =+ 1;', 'sequence' => 2, 'is_correct_answer' => 0],
                        ['label' => '$count++;', 'sequence' => 3, 'is_correct_answer' => 1],
                        ['label' => '++count', 'sequence' => 2, 'is_correct_answer' => 0],
                    ]],
                    ['description' => 'The die() and exit() functions do the exact same thing.', 'sequence' => 6, 'options' => [
                        ['label' => 'False', 'sequence' => 1, 'is_correct_answer' => 0],
                        ['label' => 'True', 'sequence' => 2, 'is_correct_answer' => 1],
                    ]],
                ]
        ],

        
    ]
];

return $seed;