<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_token".
 *
 * @property int $id
 * @property int $user_id
 * @property string $platform
 * @property string $uuid
 * @property string $app_type
 * @property int $created_at
 * @property int $created_by
 *
 * @property User $user
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_token';
    }
    public function behaviors()
    {
        return [           
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                   \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            [
                'class' => \yii\behaviors\BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['platform', 'uuid'], 'required'],
            [['user_id', 'created_at', 'created_by'], 'integer'],
            [['platform', 'uuid'], 'string'],
            [['app_type'], 'string', 'max' => 255],
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
            'platform' => 'Platform',
            'uuid' => 'Uuid',
            'app_type' => 'App Type',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    
}
