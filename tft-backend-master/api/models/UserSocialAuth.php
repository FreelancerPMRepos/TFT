<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_social_auth".
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_id
 *
 * @property User $user
 */
class UserSocialAuth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_social_auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'provider', 'provider_id'], 'required'],
            [['user_id'], 'integer'],
            [['provider'], 'string'],
            [['provider_id'], 'string', 'max' => 255],
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
            'provider' => 'Provider',
            'provider_id' => 'Provider ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
