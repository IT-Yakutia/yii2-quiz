<?php

namespace ayaalkaplin\quiz\models\schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use frontend\schema\Types;

class QuizOptionType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'description' => 'Quizes Option list. If the field "type" is 0, that option is a string. If the field "type" is 1, that option is an image',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Option ID',
                    ],
                    'title' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Option title',
                    ],
                    'src' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Option type',
                    ],
                    'type' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Option type',
                    ],
                    'sort' => [
                        'type' => Type::int(),
                        'description' => 'Sort by ASC',
                    ],
                    'quiz_question_id' => [
                        'type' => Type::string(),
                        'description' => 'Quiz Question Id',
                    ],
                    'is_publish' => [
                        'type' => Type::boolean(),
                        'description' => 'Is Quiz public',
                    ],
                    'status' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Option status',
                    ],
                    'created_at' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Option created datetime',
                    ],
                    'updated_at' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Option updated datetime',
                    ],
                    'quizQuestion' => [
                        'type' => quizTypes::quiz(),
                        'description' => 'Quiz',
                        'resolve' => function(\ayaalkaplin\quiz\models\QuizOption $quizOption) {
                            return $quizOption->quizQuestion;
                        },
                    ],
                ];
            }
        ];
        parent::__construct($config);
    }
}