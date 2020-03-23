<?php

namespace ityakutia\quiz\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class QuizOptionSearch extends QuizOption
{
    public $quiz_id;

    public function rules()
    {
        return [
            [['id', 'type', 'sort', 'quiz_question_id', 'is_publish', 'status', 'created_at', 'updated_at', 'quiz_id', 'correct_answer'], 'integer'],
            [['title', 'src'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = QuizOption::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['sort'=>SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('quizQuestion');
        // grid filtering conditions
        $query->andFilterWhere([
            'quiz_option.id' => $this->id,
            'quiz_option.is_publish' => $this->is_publish,
            'quiz_id' => $this->quiz_id,
            'quiz_option.status' => $this->status,
            'quiz_option.created_at' => $this->created_at,
            'quiz_option.updated_at' => $this->updated_at,
            'quiz_option.quiz_question_id' => $this->quiz_question_id,
            'quiz_option.type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'quiz_option.title', $this->title])
            ->andFilterWhere(['like', 'quiz_option.src', $this->src]);

        return $dataProvider;
    }
}