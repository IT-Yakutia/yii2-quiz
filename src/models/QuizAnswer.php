<?php


namespace ityakutia\quiz\models;


use Yii;
use yii\base\Model;


class QuizAnswer extends Model
{
    public $quiz_id;
    public $quiz_answers;

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
        foreach ($this->quiz_answers as $question_id => $answer) {
            $questionModel = QuizQuestion::findOne($question_id);
            if ($questionModel->type == QuizQuestion::TYPE_MULTISELECT) {
                // мультселект
                $client_options = array_keys($answer);
            } else {
                // едиственный верный
                $client_options = [$answer['radio']];
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
}
