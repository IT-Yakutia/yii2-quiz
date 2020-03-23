<?php

namespace ityakutia\quiz\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "quiz_result_select".
 *
 * @property int $id
 * @property int $quiz_option_id
 * @property int $quiz_result_id
 *
 * @property QuizOption $quizOption
 * @property QuizResult $quizResult
 */
class QuizResultSelect extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_result_select';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_option_id', 'quiz_result_id'], 'required'],
            [['quiz_option_id', 'quiz_result_id'], 'integer'],
            [['quiz_option_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuizOption::class, 'targetAttribute' => ['quiz_option_id' => 'id']],
            [['quiz_result_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuizResult::class, 'targetAttribute' => ['quiz_result_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quiz_option_id' => 'Quiz Option ID',
            'quiz_result_id' => 'Quiz Result ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getQuizOption()
    {
        return $this->hasOne(QuizOption::class, ['id' => 'quiz_option_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getQuizResult()
    {
        return $this->hasOne(QuizResult::class, ['id' => 'quiz_result_id']);
    }
}
