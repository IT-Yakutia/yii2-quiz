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
        $quiz_type = Quiz::findOne($this->quiz_id)->type;
        switch ($quiz_type) {
            case Quiz::TYPE_STANDARD : return $this->makeStandardResult();
            case Quiz::TYPE_RATING : return $this->makeRatingResult();
            default : return false;
        }
    }

    /*
     * Стандартный - Балловая система с определенным резульатом
     */
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

    /*
     * Рейтинговый - Ответы с правильным вариантом
     */
    private function makeRatingResult()
    {
        $rate = 0;
        foreach ($this->quiz_answers as $question_id => $answer) {
            $questionModel = QuizQuestion::findOne($question_id);
            if ($questionModel->type == QuizQuestion::TYPE_MULTISELECT) {
                // мультселект
                $client_options = array_keys($answer);
            } else {
                // едиственный верный
                $client_options = [$answer['radio']];
            }
            $correct_options = [];
            // $options = $questionModel->quizOptions;
            foreach ($questionModel->quizOptions as $option) {
                if($option->correct_answer) {
                    $correct_options[] = $option->id;
                }
            }

            if(empty(array_diff($client_options, $correct_options)) && empty(array_diff($correct_options, $client_options))) {
                $rate++;
            }
        }

        $quizUserAnswer = new QuizUserAnswer();
        $quizUserAnswer->quiz_id = $this->quiz_id;
        $quizUserAnswer->answers = json_encode($this->quiz_answers);
        $quizUserAnswer->browser_agent = Yii::$app->request->userAgent;
        $quizUserAnswer->ip = Yii::$app->request->userIP;
        if ($quizUserAnswer->save()) {
            return $quizUserAnswer;
        } else {
            var_dump($quizUserAnswer->errors);die;
        }

        return false;
    }

    public function result()
    {
        return $this->result;
    }
}
