<?php

namespace common\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `\common\models\User`.
 */
class UserSearch extends UserData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'access_token_expired_at', 'confirmed_at', 'last_login_at','blocked_at', 'status', 'role', 'created_at', 'updated_at'], 'integer'],
            [['username','auth_key', 'social_type','password_hash', 'password_reset_token', 'email', 'unconfirmed_email', 'registration_ip', 'last_login_ip', 'user_type'], 'safe'],
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
        // $query = UserData::find()->joinWith(['userAdditionalInfos']);
        $query = UserData::find()->joinWith(['userAdditionalInfos']);
        $query->where(['!=','user.id',Yii::$app->user->id]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);
        if($this->id){
            $query->andFilterWhere(['IN','user.id',$this->id]);
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'access_token_expired_at' => $this->access_token_expired_at,
            'confirmed_at' => $this->confirmed_at,
            'last_login_at' => $this->last_login_at,
            'blocked_at' => $this->blocked_at,
            'status' => $this->status,
            'role' => $this->role,
            'social_type' => $this->social_type,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'user_type', $this->user_type]);
            
        return $dataProvider;
    }
}
