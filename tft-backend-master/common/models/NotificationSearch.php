<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Notification;

/**
 * NotificationSearch represents the model behind the search form of `\common\models\Notification`.
 */
class NotificationSearch extends Notification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'created_by'], 'integer'],
            [['uuid', 'title', 'message', 'type', 'app_type', 'is_read', 'push_request', 'push_response'], 'safe'],
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
        $query = Notification::find();

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
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'app_type', $this->app_type])
            ->andFilterWhere(['like', 'is_read', $this->is_read])
            ->andFilterWhere(['like', 'push_request', $this->push_request])
            ->andFilterWhere(['like', 'push_response', $this->push_response]);

        return $dataProvider;
    }
}
