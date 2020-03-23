<?php

namespace ayaalkaplin\quiz\models\schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ayaalkaplin\quiz\models\QuizAnswer;
use frontend\schema\Types;

class MutationQuizAnswerType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => function() {
                return [
                    'create' => [
                        'type' => Types::validationErrorsUnionType(quizTypes::quizResult()),
                        'description' => 'Check answers.',
                        'args' => [
                            'quiz_id' => Type::nonNull(Type::int()),
                            'quiz_answers' => Type::nonNull(Type::listOf(quizTypes::quizAnswerOption())),
                        ],
                        'resolve' => function(\ayaalkaplin\quiz\models\QuizAnswer $quizAnswer, $args) {

                            $quizAnswer->setAttributes($args);
                            if ($quizAnswer->make()) {
                                return $quizAnswer->result();
                            }

                            foreach ($quizAnswer->getErrors() as $field => $messages) {
                                // поля из ValidationErrorType
                                $errors[] = [
                                    'field' => $field,
                                    'messages' => $messages,
                                ];
                            }

                            return ['errors' => $errors];
                        }
                    ],
                ];
            }
        ];

        parent::__construct($config);
    }
}