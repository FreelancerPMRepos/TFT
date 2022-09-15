<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_verification_code".
 *
 * @property int $id
 * @property int $user_id
 * @property int $code
 * @property int $expired_at
 */
class UserVerificationCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_verification_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'code', 'expired_at'], 'required'],
            [['user_id', 'expired_at'], 'integer'],
            [['code'], 'string', 'max' => 6,'min' => 6],
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
            'code' => 'Code',
            'expired_at' => 'Expired At',
        ];
    }
}
