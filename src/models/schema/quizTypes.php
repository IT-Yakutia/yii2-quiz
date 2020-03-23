<?php 

namespace ayaalkaplin\quiz\models\schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\UnionType;

class quizTypes
{
    private static $quiz;
    private static $quizQuestion;
    private static $quizResult;
    private static $quizOption;
    private static $quizAnswer;
    private static $quizAnswerOption;
    private static $mutationQuizAnswer;

    public static function quiz()
    {
        return self::$quiz ?: (self::$quiz = new QuizType());
    }

    public static function quizQuestion()
    {
        return self::$quizQuestion ?: (self::$quizQuestion = new QuizQuestionType());
    }

    public static function quizResult()
    {
        return self::$quizResult ?: (self::$quizResult = new QuizResultType());
    }

    public static function quizOption()
    {
        return self::$quizOption ?: (self::$quizOption = new QuizOptionType());
    }

    public static function quizAnswer()
    {
        return self::$quizAnswer ?: (self::$quizAnswer = new QuizAnswerType());
    }

    public static function quizAnswerOption()
    {
        return self::$quizAnswerOption ?: (self::$quizAnswerOption = new QuizAnswerOptionType());
    }

    public static function mutationQuizAnswer()
    {
        return self::$mutationQuizAnswer ?: (self::$mutationQuizAnswer = new MutationQuizAnswerType());
    }
}