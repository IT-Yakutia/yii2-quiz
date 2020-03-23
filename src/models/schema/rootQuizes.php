<?php

namespace ayaalkaplin\quiz\models\schema;

use GraphQL\Type\Definition\Type;
use frontend\schema\Types;

return [
    'type' => Type::listOf(quizTypes::quiz()), 
    'description' => 'Root query for get of public Quiz',
    'resolve' => function($root, $args) {
        $query = \ayaalkaplin\quiz\models\Quiz::find();

        $query->andFilterWhere([
            'is_publish' => true,
        ]);

        return $query->all();
    }
];