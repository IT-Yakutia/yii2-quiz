<?php

namespace ityakutia\quiz\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "quiz_result".
 *
 * @property int $id
 * @property string $title
 * @property string $photo
 * @property int $quiz_id
 * @property int $is_publish
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property QuizOption[] $quizOptions
 * @property Quiz $quiz
 */
class QuizResult extends ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'quiz_id'], 'required'],
            [['quiz_id', 'is_publish', 'status', 'created_at', 'updated_at'], 'integer'],
            [['min_limit', 'max_limit'], 'integer', 'max' => 100],
            [['title', 'photo'], 'string', 'max' => 255],
            ['description', 'string'],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::class, 'targetAttribute' => ['quiz_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('app','Заголовок результата'),
            'photo' => Yii::t('app','Изображение результата'),
            'quiz_id' => Yii::t('app','Квиз'),
            'min_limit' => Yii::t('app', 'Минимальное значение'),
            'max_limit' => Yii::t('app', 'Максимальное значение'),
            'is_publish' => Yii::t('app','Опубликовать'),
            'status' => 'Status',
            'created_at' => Yii::t('app','Создан'),
            'updated_at' => Yii::t('app','Изменен'),
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getQuizOptions()
    {
        return $this->hasMany(QuizOption::class, ['id' => 'quiz_option_id'])->viaTable('quiz_result_select', ['quiz_result_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getQuiz() : ActiveQuery
    {
        return $this->hasOne(Quiz::class, ['id' => 'quiz_id']);
    }
}
