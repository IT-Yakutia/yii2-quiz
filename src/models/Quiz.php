<?php


namespace ityakutia\quiz\models;


use uraankhayayaal\sortable\behaviors;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "quiz".
 *
 * @property int $id
 * @property string $title
 * @property string $photo
 * @property int $sort
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $slug
 * @property int $user_id
 * @property int $is_publish
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property QuizQuestion[] $quizQuestions
 * @property QuizResult[] $quizResults
 */
class Quiz extends ActiveRecord
{
    public const TYPE_STANDARD = 0;
    public const TYPE_RATING = 1;

    public const TYPES = [
        self::TYPE_STANDARD => 'Стандартный',
        self::TYPE_RATING => 'Рейтинговый'
    ];

	public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'sortable' => [
                'class' => Sortable::class,
                'query' => self::find(),
            ],
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
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort', 'user_id', 'is_publish', 'status', 'created_at', 'updated_at', 'type'], 'integer'],
            [['title', 'photo', 'slug', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['slug', 'unique'],
            ['slug', 'compare', 'operator' => '!=', 'compareValue' => 'about'],
            ['slug', 'compare', 'operator' => '!=', 'compareValue' => 'contact'],
            ['slug', 'compare', 'operator' => '!=', 'compareValue' => 'login'],
            ['slug', 'compare', 'operator' => '!=', 'compareValue' => 'blog'],
            ['slug', 'compare', 'operator' => '!=', 'compareValue' => 'games'],
            ['slug', 'compare', 'operator' => '!=', 'compareValue' => 'career'],
            ['slug', 'compare', 'operator' => '!=', 'compareValue' => 'darkwood-quiz'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('app','Название Квиза'),
            'photo' => Yii::t('app','Фото'),
            'sort' => 'Sort',
            'slug' => Yii::t('app','Ссылка квиза'),
            'user_id' => Yii::t('app','Автор'),
            'is_publish' => Yii::t('app','Опубликовать'),
            'status' => 'Status',
            'type' => Yii::t('app', 'Тип Квиза'),
            'created_at' => Yii::t('app','Создан'),
            'updated_at' => Yii::t('app','Изменен'),
            'meta_description' => Yii::t('app','SEO описание'),
            'meta_keywords' => Yii::t('app','SEO ключевые слова')
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
    public function getQuizQuestions()
    {
        return $this->hasMany(QuizQuestion::class, ['quiz_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getQuizResults()
    {
        return $this->hasMany(QuizResult::class, ['quiz_id' => 'id']);
    }

    public static function findOneForFront($slug)
    {
        if(Yii::$app->user->can("quiz"))
            return self::find()->where(['slug' => $slug])->one();
        else
            return self::find()->where(['slug' => $slug, 'is_publish' => true])->one();
    }
}
