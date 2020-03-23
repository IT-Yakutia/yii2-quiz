<?php


namespace ityakutia\quiz\models;


use Yii;
use yii\base\Model;


class QuizAnswer extends Model
{
    public $quiz_id;
    public $quiz_answers;

    protected $result;

    public function rules()
    {
        return [
            [['quiz_id', 'quiz_answers'], 'required'],
            [['quiz_id',], 'integer'],
            ['quiz_answers', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'quiz_id' => Yii::t('app','Квиз'),
            'quiz_answers' => Yii::t('app','Ответы пользователей'),
        ];
    }

    public function make()
    {
        // получил тип квиза
        $quiz_type = QuizQuestion::find()->where(['id' => $this->quiz_answers[0]['quiz_question_id']])->one()->quiz->type;

        switch ($quiz_type) {
            case Quiz::TYPE_STANDARD : return $this->makeStandardResult();
            case Quiz::TYPE_RATING : return $this->makeRatingResult();
            default : return false;
        }
    }

    private function makeStandardResult() : bool
    {
        $result = [];
        foreach ($this->quiz_answers as $key => $answer) {

            foreach ($answer['quiz_option_ids'] as $_key => $option_id){

                $option = QuizOption::find()->where(['id' => $option_id, 'quiz_question_id' => $answer['quiz_question_id']])->one();
                if($option !== null && $option->quizQuestion->quiz_id == $this->quiz_id) {

                    /*
                    * Добавить веса для результатов
                    */
                    foreach ($option->quizResults as $__key => $quizResult) {
                        $result[] = $quizResult->id;
                    }
                }
            }
        }

        if(empty($result)) {
            return false;
        }

        $tmp = array_count_values($result);
        $max = max($tmp);
        $result = array_search( $max, $tmp);
        $this->result = QuizResult::findOne($result);

        return true;
    }

    private function makeRatingResult()
    {
        $rate = 0;
        $question_id = '';
        foreach ($this->quiz_answers as $answer) {
            $question_id = $answer['quiz_question_id'];
            $client_options = $answer['quiz_option_ids'];

            $correct_options = [];
            $options = QuizQuestion::findOne($question_id)->quizOptions;
            foreach ($options as $option) {
                if($option->correct_answer) {
                    $correct_options[] = $option->id;
                }
            }

            if(empty(array_diff($client_options, $correct_options)) && empty(array_diff($correct_options, $client_options))) {
                $rate++;
            }
        }

        $quiz_id = QuizQuestion::findOne($question_id)->quiz_id;
        $result = QuizResult::find()->where(['quiz_id' => $quiz_id])->andWhere(['<=', 'min_limit', $rate])->andWhere(['>=', 'max_limit', $rate])->one();
        $result->description = str_replace('[RATE]', $rate, $result->description);
        $this->result = $result;

        return true;
    }

    public function result()
    {
        return $this->result;
    }


}
