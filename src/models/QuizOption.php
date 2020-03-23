<?php

namespace ityakutia\quiz\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;
use uraankhayayaal\sortable\behaviors\Sortable;

/**
 * This is the model class for table "quiz_option".
 *
 * @property int $id
 * @property string $title
 * @property int $type
 * @property int $sort
 * @property string $src
 * @property int $quiz_question_id
 * @property int $is_publish
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property QuizQuestion $quizQuestion
 */
class QuizOption extends ActiveRecord
{
    public $quiz_result_ids = [];

    const TYPE_STRING = 0;
    const TYPE_IMAGE = 1;

    const TYPES = [
        self::TYPE_STRING => 'Строка',
        self::TYPE_IMAGE => 'Изображение',
    ];

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
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
        return 'quiz_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'quiz_question_id'], 'required'],
            [['type', 'sort', 'quiz_question_id', 'is_publish', 'status', 'created_at', 'updated_at', 'correct_answer'], 'integer'],
            [['title', 'src'], 'string', 'max' => 255],
            [['quiz_question_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuizQuestion::class, 'targetAttribute' => ['quiz_question_id' => 'id']],
            ['quiz_result_ids', 'each', 'rule' => ['string'], 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('app','Варинт ответа'),
            'type' => Yii::t('app','Тип варианта ответа'),
            'sort' => 'Sort',
            'src' => Yii::t('app','Изображение'),
            'quiz_question_id' => Yii::t('app','Вопрос квиза'),
            'is_publish' => Yii::t('app','Опубликовать'),
            'status' => 'Status',
            'correct_answer' => Yii::t('app', 'Правильный Ответ'),
            'created_at' => Yii::t('app','Создан'),
            'updated_at' => Yii::t('app','Изменен'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getQuizQuestion()
    {
        return $this->hasOne(QuizQuestion::class, ['id' => 'quiz_question_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getQuizResults()
    {
        return $this->hasMany(QuizResult::class, ['id' => 'quiz_result_id'])->viaTable('quiz_result_select', ['quiz_option_id' => 'id']);
    }

    /*
     * Check is setted a result for this option
     */
    public function hasQuizResult($id)
    {
        return QuizResultSelect::find()->where(['quiz_option_id' => $this->id, 'quiz_result_id' => $id])->exists();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->updateQuizResults();
    }

    public function updateQuizResults(){
        if(!empty($this->quiz_result_ids)){
            $selected_quiz_results = $this->quizResults;
            foreach ($selected_quiz_results as $key => $selected_quiz_result) { 
                if(!array_key_exists($selected_quiz_result->id, $this->quiz_result_ids)){
                    QuizResultSelect::findOne($selected_quiz_result->id)->delete();
                }
            }
            
            foreach ($this->quiz_result_ids as $key => $quiz_result_id) {
                $isSeted = $this->hasQuizResult($key);
                if(!$isSeted){
                    $model = new QuizResultSelect();
                    $model->quiz_option_id = $this->id;                    
                    $model->quiz_result_id = $key;                    
                    $model->save();
                }
            }
        }else{
            return QuizResultSelect::deleteAll(['quiz_option_id' => $this->id]);
        }
        return true;
    }
}
