<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserLogBody;

/**
 * UserLogBodySearch represents the model behind the search form of `common\models\UserLogBody`.
 */
class UserLogBodySearch extends UserLogBody
{
    public $created_at_range;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_log_id'], 'integer'],
            [['body_part', 'created_at_range', 'value_unit'], 'safe'],
            [['value'], 'number'],
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
        $query = UserLogBody::find()
        ->joinWith('userLog as a')
        ->where(['a.user_id'=>$params['user_id']])
        ->orderBy('user_log_id DESC');

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
            'user_log_id' => $this->user_log_id,
            'value' => $this->value,
        ]);

        $query->andFilterWhere(['like', 'body_part', $this->body_part])
            ->andFilterWhere(['like', 'value_unit', $this->value_unit]);
            
        if(!empty($params['UserLogBodySearch']['created_at_range']))
        {
            list($start_date, $end_date) = explode(' - ', $params['UserLogBodySearch']['created_at_range']);
            $query->andFilterWhere(['between', 'a.log_date', strtotime($start_date), strtotime($end_date)]);
        }
        return $dataProvider;
    }
}
