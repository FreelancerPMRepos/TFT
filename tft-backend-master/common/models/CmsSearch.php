<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Cms;

/**
 * CmsSearch represents the model behind the search form of `\common\models\Cms`.
 */
class CmsSearch extends Cms
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'html_body', 'app_body', 'meta_tile', 'meta_keyword', 'meta_description','slug'], 'safe'],
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
        $query = Cms::find();

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
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'html_body', $this->html_body])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'app_body', $this->app_body])
            ->andFilterWhere(['like', 'meta_tile', $this->meta_tile])
            ->andFilterWhere(['like', 'meta_keyword', $this->meta_keyword])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description]);

        return $dataProvider;
    }
}
