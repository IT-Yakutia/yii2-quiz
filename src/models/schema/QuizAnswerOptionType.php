<?php

namespace ayaalkaplin\quiz\models\schema;

//use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class QuizAnswerOptionType extends InputObjectType
{
    public function __construct()
    {
        $config = [
            'description' => 'Quizes Answer Option list',
            'fields' => function() {
                return [
                    'quiz_question_id' => [
                        'type' => Type::int(),
                        'description' => 'Quiz Question ID',
                    ],
                    'quiz_option_ids' => [
                        'type' => Type::listof(Type::int()),
                        'description' => 'Quiz Option IDs',
                    ],
                ];
            }
        ];
        parent::__construct($config);
    }
}