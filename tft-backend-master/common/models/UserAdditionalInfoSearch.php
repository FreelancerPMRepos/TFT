<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserAdditionalInfo;
use common\models\Admin;

/**
 * UserAdditionalInfoSearch represents the model behind the search form of `common\models\UserAdditionalInfo`.
 */
class UserAdditionalInfoSearch extends UserAdditionalInfo
{
    public $user_type;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['user_type','photo', 'thum_photo', 'date_of_birth', 'gender', 'units_of_measurement', 'height_unit', 'weight_unit', 'sports_interest'], 'safe'],
            [['height', 'weight'], 'number'],
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
        $query =  UserAdditionalInfo::find()->joinWith(['user']);
        // print_r($query->asArray()->all());die;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 24,
            ],
        ]);

        $this->load($params);

        // print_r($this->user_type);die;
        if($this->user_type == "Trainer") {
            $query->andWhere([
                'role' => 50,
            ]);
        }
        else if($this->user_type == "Trainee") {
            $query->andWhere([
                'role' => 60,
            ]);
            $query->andWhere(['IN', 'user_id', $this->user_id]);
        }
        else if($this->user_type == "User"){
            $query->andWhere([
                'role' => 10,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'user_id' => $this->user_id,
            'date_of_birth' => $this->date_of_birth,
            'height' => $this->height,
            'weight' => $this->weight,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'thum_photo', $this->thum_photo])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'units_of_measurement', $this->units_of_measurement])
            ->andFilterWhere(['like', 'height_unit', $this->height_unit])
            ->andFilterWhere(['like', 'weight_unit', $this->weight_unit])
            ->andFilterWhere(['like', 'sports_interest', $this->sports_interest]);

        return $dataProvider;
    }
}