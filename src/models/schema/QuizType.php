<?php

namespace ayaalkaplin\quiz\models\schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use frontend\schema\Types;

class QuizType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'description' => 'Quizes list',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::int(),
                        'description' => 'Quiz ID',
                    ],
                    'title' => [
                        'type' => Type::string(),
                        'description' => 'Quiz title',
                    ],
                    'photo' => [
                        'type' => Type::string(),
                        'description' => 'Quiz photo',
                    ],
                    'type' => [
                        'type' => Type::int(),
                        'description' => 'Quiz type',
                    ],
                    'sort' => [
                        'type' => Type::boolean(),
                        'description' => 'Sort dy ASC',
                    ],
                    'is_publish' => [
                        'type' => Type::boolean(),
                        'description' => 'Is Quiz public',
                    ],
                    'status' => [
                        'type' => Type::int(),
                        'description' => 'Quiz status',
                    ],
                    'created_at' => [
                        'type' => Type::int(),
                        'description' => 'Quiz created datetime',
                    ],
                    'updated_at' => [
                        'type' => Type::int(),
                        'description' => 'Quiz updated datetime',
                    ],
                    'meta_description' => [
                        'type' => Type::string(),
                        'description' => 'SEO description',
                    ],
                    'meta_keywords' => [
                        'type' => Type::string(),
                        'description' => 'SEO key words',
                    ],
                    'questions' => [
                        'type' => Type::listof(quizTypes::quizQuestion()),
                        'description' => 'Quiz questions',
                        'resolve' => function(\ayaalkaplin\quiz\models\Quiz $quiz) {
                            return $quiz->quizQuestions;
                        },
                    ],
                    'results' => [
                        'type' => Type::listof(quizTypes::quizResult()),
                        'description' => 'Quiz results',
                        'resolve' => function(\ayaalkaplin\quiz\models\Quiz $quiz) {
                            return $quiz->quizResults;
                        },
                    ],
                ];
            }
        ];

        parent::__construct($config);
    }

}
