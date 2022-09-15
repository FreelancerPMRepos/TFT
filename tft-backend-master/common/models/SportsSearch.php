<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sports;

/**
 * SportsSearch represents the model behind the search form of `common\models\Sports`.
 */
class SportsSearch extends Sports
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'active', 'created_at'], 'integer'],
            [['name', 'images'], 'safe'],
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
        $query = Sports::find();
        if($this->sportType == 'ssstr')
        {
            $query->andWhere(['active_for_ssstr'=>1]);
        }
        if($this->sportType = "ssgst")
        {
            $query->andWhere(['active'=>1]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
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
            'active' => $this->active,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'images', $this->images]);

        return $dataProvider;
    }
}
