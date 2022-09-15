<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserLogPhoto;

/**
 * UserLogPhotoSearch represents the model behind the search form of `common\models\UserLogPhoto`.
 */
class UserLogPhotoSearch extends UserLogPhoto
{
    public $created_at_range;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_log_id'], 'integer'],
            [['photo', 'tag','created_at_range'], 'safe'],
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
        $query = UserLogPhoto::find()
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
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
        ->andFilterWhere(['like', 'tag', $this->tag]);

        if(!empty($params['UserLogPhotoSearch']['created_at_range']))
        {
            list($start_date, $end_date) = explode(' - ', $params['UserLogPhotoSearch']['created_at_range']);
            $query->andFilterWhere(['between', 'a.log_date', strtotime($start_date), strtotime($end_date)]);
        }
        return $dataProvider;
    }
}
