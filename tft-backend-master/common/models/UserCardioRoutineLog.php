<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_cardio_routine_logs".
 *
 * @property int $id
 * @property int $user_cardio_routine_id
 * @property int $week_no
 * @property int $day_no
 * @property int $workout_time
 * @property int $exe_id
 * @property int $created_at
 * @property string $status
 * @property int $is_close
 *
 * @property Exercises $exe
 * @property UserCardioRoutine $userCardioRoutine
 */
class UserCardioRoutineLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_cardio_routine_logs';
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
            [['user_cardio_routine_id', 'week_no', 'day_no', 'workout_time', 'exe_id'], 'required'],
            [['user_cardio_routine_id', 'week_no', 'day_no', 'workout_time', 'exe_id', 'created_at', 'is_close'], 'integer'],
            [['status'], 'string'],
            [['exe_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exercise::className(), 'targetAttribute' => ['exe_id' => 'id']],
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
            'week_no' => 'Week No',
            'day_no' => 'Day No',
            'workout_time' => 'Workout Time',
            'exe_id' => 'Exe ID',
            'created_at' => 'Created At',
            'status' => 'Status',
            'is_close' => 'Is Close',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExe()
    {
        return $this->hasOne(Exercise::className(), ['id' => 'exe_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCardioRoutine()
    {
        return $this->hasOne(UserCardioRoutine::className(), ['id' => 'user_cardio_routine_id']);
    }
}
