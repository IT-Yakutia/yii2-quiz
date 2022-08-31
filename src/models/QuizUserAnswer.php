<?php


namespace ityakutia\quiz\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\User;


/**
 * This is the model class for table "quiz_user_answer".
 *
 * @property int $id
 * @property string $answers
 * @property string $browser_agent
 * @property string $ip
 * @property int $quiz_id
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Quiz $quiz
 */
class QuizUserAnswer extends ActiveRecord
{
	public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'user_id',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->id;
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_user_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answers', 'browser_agent', 'ip', 'quiz_id'], 'required'],
            [['quiz_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['ip'], 'ip'],
            [['answers', 'browser_agent'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['quiz_id'], 'exist', 'skipOnError' => false, 'targetClass' => Quiz::class, 'targetAttribute' => ['quiz_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'answers' => Yii::t('app','Ответы пользователя'),
            'browser_agent' => Yii::t('app','Браузер бользователя'),
            'ip' => 'IP адресс пользователя',
            'quiz_id' => Yii::t('app','Квиз'),
            'user_id' => Yii::t('app','Пользователь'),
            'created_at' => Yii::t('app','Создан'),
            'updated_at' => Yii::t('app','Изменен'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::class, ['id' => 'quiz_id']);
    }
}
