<?php

namespace ayaalkaplin\quiz\models\schema;

use GraphQL\Type\Definition\Type;
use frontend\schema\Types;

return [
    'type' => quizTypes::quiz(), 
    'description' => 'Root query for get a Quiz',
    'args' => [
        'slug' => Type::nonNull(Type::string()),
    ],
    'resolve' => function($root, $args) {
        return \ayaalkaplin\quiz\models\Quiz::findOneForFront($args['slug']);
    }
];