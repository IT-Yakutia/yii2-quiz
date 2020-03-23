<?php

namespace ayaalkaplin\quiz\models\schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use frontend\schema\Types;

class QuizResultType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'description' => 'Quizes Result list',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Result ID',
                    ],
                    'title' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Result title',
                    ],
                    'description' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Result description',
                    ],
                    'photo' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Result photo',
                    ],
                    'quiz_id' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Result Id',
                    ],
                    'is_publish' => [
                        'type' => Type::boolean(),
                        'description' => 'Is Quiz public',
                    ],
                    'status' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Result status',
                    ],
                    'created_at' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Result created datetime',
                    ],
                    'updated_at' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Result updated datetime',
                    ],
                    'quiz' => [
                        'type' => quizTypes::quiz(),
                        'description' => 'Quiz',
                        'resolve' => function(\ayaalkaplin\quiz\models\QuizResult $quizResult) {
                            return $quizResult->quiz;
                        },
                    ],
                ];
            }
        ];
        parent::__construct($config);
    }
}