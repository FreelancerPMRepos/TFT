<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Setting;

/**
 * SettingSearch represents the model behind the search form of `\common\models\Setting`.
 */
class SettingSearch extends Setting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_public', 'status'], 'integer'],
            [['meta_key', 'meta_name', 'meta_type', 'meta_desc', 'meta_attribute', 'meta_value'], 'safe'],
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
        $query = Setting::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'is_public' => $this->is_public,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'meta_key', $this->meta_key])
            ->andFilterWhere(['like', 'meta_name', $this->meta_name])
            ->andFilterWhere(['like', 'meta_type', $this->meta_type])
            ->andFilterWhere(['like', 'meta_desc', $this->meta_desc])
            ->andFilterWhere(['like', 'meta_attribute', $this->meta_attribute])
            ->andFilterWhere(['like', 'meta_value', $this->meta_value]);

        return $dataProvider;
    }
}
