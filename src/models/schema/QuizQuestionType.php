<?php

namespace ayaalkaplin\quiz\models\schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use frontend\schema\Types;

class QuizQuestionType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'description' => 'Quizes Question list. If the field "type" is 0, that question is a multiple choice. If the field "type" is 1, that question is a single choice',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Question ID',
                    ],
                    'title' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Question title',
                    ],
                    'type' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Question type',
                    ],
                    'sort' => [
                        'type' => Type::int(),
                        'description' => 'Sort by ASC',
                    ],
                    'quiz_id' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Question Id',
                    ],
                    'is_publish' => [
                        'type' => Type::boolean(),
                        'description' => 'Is Quiz public',
                    ],
                    'status' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Question status',
                    ],
                    'created_at' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Question created datetime',
                    ],
                    'updated_at' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Question updated datetime',
                    ],
                    'quiz' => [
                        'type' => quizTypes::quiz(),
                        'description' => 'Quiz',
                        'resolve' => function(\ayaalkaplin\quiz\models\QuizQuestion $quizQuestion) {
                            return $quizQuestion->quiz;
                        },
                    ],
                    'options' => [
                        'type' => Type::listof(quizTypes::quizOption()),
                        'description' => 'Quiz questions options',
                        'resolve' => function(\ayaalkaplin\quiz\models\QuizQuestion $quizQuestion) {
                            return $quizQuestion->quizOptions;
                        },
                    ],
                ];
            }
        ];
        parent::__construct($config);
    }
}