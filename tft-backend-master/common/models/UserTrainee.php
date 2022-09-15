<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_trainee".
 *
 * @property int $id
 * @property int $trainer_id
 * @property int $trainee_id
 * @property int $created_at
 *
 * @property User $trainee
 * @property User $trainer
 */
class UserTrainee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_trainee';
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
            [['trainer_id', 'trainee_id', 'created_at'], 'required'],
            [['trainer_id', 'trainee_id', 'created_at'], 'integer'],
            [['trainee_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['trainee_id' => 'id']],
            [['trainer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['trainer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trainer_id' => 'Trainer ID',
            'trainee_id' => 'Trainee ID',
            'created_at' => 'Created At',
        ];
    }

    
    public function getTrainer()
    {
        return $this->hasOne(User::className(), ['id' => 'user_trainee.trainer_id']);
    }
    public function getTraineeDetail()
    {
        return $this->hasOne(User::className(), ['id' => 'trainee_id']);
    }
    public function getTraineeAdditionalDetail()
    {
        return $this->hasOne(UserAdditionalInfo::className(), ['user_id' => 'trainee_id']);
    }
    public function getTraineeLicenseDetail()
    {
        return $this->hasOne(UserLicenses::className(), ['trainee_id' => 'trainee_id']);
    }
    
}
