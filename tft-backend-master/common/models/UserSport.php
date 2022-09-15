<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_ssstr_sport".
 *
 * @property int $id
 * @property int $user_id
 * @property int $sport_id
 * @property string|null $response
 *
 * @property Sports $sport
 * @property User $user
 */
class UserSport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_ssstr_sport';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'sport_id'], 'required'],
            [['user_id', 'sport_id'], 'integer'],
            [['response'], 'string'],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sports::className(), 'targetAttribute' => ['sport_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'sport_id' => 'Sport ID',
            'response' => 'Response',
        ];
    }

    /**
     * Gets query for [[Sport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(Sports::className(), ['id' => 'sport_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}