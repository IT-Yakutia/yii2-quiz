<?php

namespace ityakutia\quiz\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class QuizResultSearch extends QuizResult
{
    public function rules()
    {
        return [
            [['id', 'is_publish', 'status', 'created_at', 'updated_at', 'quiz_id', 'min_limit', 'max_limit'], 'integer'],
            [['title', 'photo'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = QuizResult::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_publish' => $this->is_publish,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}