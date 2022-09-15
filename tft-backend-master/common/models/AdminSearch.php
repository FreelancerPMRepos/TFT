<?php

namespace common\models;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Admin;

/**
 * AdminSearch represents the model behind the search form of `common\models\Admin`.
 */
class AdminSearch extends Admin
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'access_token_expired_at', 'confirmed_at', 'last_login_at', 'blocked_at', 'status', 'role', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'unconfirmed_email', 'registration_ip', 'last_login_ip', 'user_type', 'social_provider_id', 'social_type'], 'safe'],
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
        $query = Admin::find();
        $query->where(['not in','user.id',Yii::$app->user->id])->andWhere(['role'=>99]);
        

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
            'access_token_expired_at' => $this->access_token_expired_at,
            'confirmed_at' => $this->confirmed_at,
            'last_login_at' => $this->last_login_at,
            'blocked_at' => $this->blocked_at,
            'status' => $this->status,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'unconfirmed_email', $this->unconfirmed_email])
            ->andFilterWhere(['like', 'registration_ip', $this->registration_ip])
            ->andFilterWhere(['like', 'last_login_ip', $this->last_login_ip])
            ->andFilterWhere(['like', 'user_type', $this->user_type])
            ->andFilterWhere(['like', 'social_provider_id', $this->social_provider_id])
            ->andFilterWhere(['like', 'social_type', $this->social_type]);

        return $dataProvider;
    }
}
