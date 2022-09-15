<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_cardio_workout_time".
 *
 * @property int $id
 * @property int $user_cardio_routine_id
 * @property int $user_id
 * @property int $week_no
 * @property int $day_no
 * @property int $created_at
 *
 * @property User $user
 * @property UserCardioRoutine $userCardioRoutine
 */
class UserCardioWorkoutTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_cardio_workout_time';
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
            [['user_cardio_routine_id', 'user_id', 'week_no', 'day_no'], 'required'],
            [['user_cardio_routine_id', 'user_id', 'week_no', 'day_no', 'created_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['user_cardio_routine_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserCardioRoutine::className(), 'targetAttribute' => ['user_cardio_routine_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_cardio_routine_id' => 'User Cardio Routine ID',
            'user_id' => 'User ID',
            'week_no' => 'Week No',
            'day_no' => 'Day No',
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

    /**
     * Gets query for [[UserCardioRoutine]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCardioRoutine()
    {
        return $this->hasOne(UserCardioRoutine::className(), ['id' => 'user_cardio_routine_id']);
    }
}