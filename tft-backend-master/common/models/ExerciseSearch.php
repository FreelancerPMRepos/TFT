<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Exercise;

/**
 * ExerciseSearch represents the model behind the search form of `common\models\Exercise`.
 */
class ExerciseSearch extends Exercise
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'exe_category_id', 'is_active', 'created_at'], 'integer'],
            [['name'],'string'],
            [['description', 'body_parts', 'steps', 'instructions', 'type', 'record_type', 'source', 'img', 'gif'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Exercise::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    // 'id' => SORT_DESC,
                    // 'title' => SORT_ASC, 
                ]
            ],

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
            'exe_category_id' => $this->exe_category_id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'body_parts', $this->body_parts])
            ->andFilterWhere(['like', 'steps', $this->steps])
            ->andFilterWhere(['like', 'instructions', $this->instructions])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'record_type', $this->record_type])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'gif', $this->gif]);
        $query->orderBy('exe_category_id ASC,name ASC');
        return $dataProvider;
    }
}
