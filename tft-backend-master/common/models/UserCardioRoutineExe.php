<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_cardio_routine_exe".
 *
 * @property int $id
 * @property int $user_cardio_routine_id
 * @property int $day
 * @property int $exe_id
 */
class UserCardioRoutineExe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_cardio_routine_exe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_cardio_routine_id', 'day', 'exe_id'], 'required'],
            [['user_cardio_routine_id', 'day', 'exe_id'], 'integer'],
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
            'day' => 'Day',
            'exe_id' => 'Exe ID',
        ];
    }
    public function getCardioRoutine()
    {
        return $this->hasOne(UserCardioRoutine::className(), ['id' => 'user_cardio_routine_id']);
    }
    public function getExercise()
    {
        return $this->hasOne(Exercise::className(), ['id' => 'exe_id']);
    }
}
