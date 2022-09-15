<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserLog;

/**
 * UserLogSearch represents the model behind the search form of `common\models\UserLog`.
 */
class UserLogSearch extends UserLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'log_date'], 'integer'],
            [['log_type'], 'safe'],
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
        $query = UserLog::find();

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
            'log_date' => $this->log_date,
        ]);

        $query->andFilterWhere(['like', 'log_type', $this->log_type]);

        return $dataProvider;
    }
    public function getUserLog()
    {
        return $this->hasOne(UserLog::className(), ['id' => 'user_log_id']);
    }
}
