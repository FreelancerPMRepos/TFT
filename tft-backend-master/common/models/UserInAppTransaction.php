<?php
namespace common\models;

use Yii;

/**
 * This is the model class for table "user_in_app_transaction".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $description
 * @property int|null $value
 * @property string|null $t_type
 * @property int $created_at
 *
 * @property User $user
 */
class UserInAppTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_in_app_transaction';
    }
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['description', 't_type','value'], 'string'],
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
            'description' => 'Description',
            'value' => 'Value',
            't_type' => 'T Type',
            'created_at' => 'Created At',
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