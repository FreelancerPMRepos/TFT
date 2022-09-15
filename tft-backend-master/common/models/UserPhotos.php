<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_photos".
 *
 * @property int $user_id
 * @property string $photo
 * @property string $created_at
 * @property string $tag
 *
 * @property User $user
 */
class UserPhotos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['photo','required'],
            [['photo'], 'file', 'extensions' => 'png, jpg,jpeg'],
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['tag'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'photo' => 'Photo',
            'created_at' => 'Created At',
            'tag' => 'Tag',
        ];
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
