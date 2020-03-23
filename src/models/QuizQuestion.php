<?php


namespace ityakutia\quiz\models;


use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;
use uraankhayayaal\sortable\behaviors;


/**
 * This is the model class for table "quiz_question".
 *
 * @property int $id
 * @property string $title
 * @property int $type
 * @property int $sort
 * @property int $quiz_id
 * @property int $is_publish
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property QuizOption[] $quizOptions
 * @property Quiz $quiz
 */
class QuizQuestion extends ActiveRecord
{
    const TYPE_MULTISELECT = 0;
    const TYPE_SINGLESELECT = 1;

    const TYPES = [
        self::TYPE_MULTISELECT => 'Выбор нескольких вариантов',
        self::TYPE_SINGLESELECT => 'Выбор одного варианта'
    ];

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'sortable' => [
                'class' => Sortable::class,
                'query' => self::find(),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'quiz_id'], 'required'],
            [['type', 'sort', 'quiz_id', 'is_publish', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
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
            'title' => Yii::t('app','Вопрос'),
            'type' => Yii::t('app','Тип вопроса'),
            'sort' => 'Sort',
            'quiz_id' => Yii::t('app','Квиз'),
            'is_publish' => Yii::t('app','Опубликовать'),
            'status' => 'Status',
            'created_at' => Yii::t('app','Создан'),
            'updated_at' => Yii::t('app','Изменен'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getQuizOptions()
    {
        return $this->hasMany(QuizOption::class, ['quiz_question_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::class, ['id' => 'quiz_id']);
    }
}
