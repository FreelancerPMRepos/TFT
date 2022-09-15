<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_cardio_routine_time".
 *
 * @property int $id
 * @property int $cardio_routine_id
 * @property int $day_no
 * @property string $day_time
 * @property int $exe_id
 *
 * @property UserCardioRoutine $cardioRoutine
 * @property Exercises $exe
 */
class UserCardioRoutineTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_cardio_routine_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cardio_routine_id', 'day_no', 'day_time'], 'required'],
            [['cardio_routine_id', 'day_no'], 'integer'],
            [['day_time'], 'string', 'max' => 255],
            [['cardio_routine_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserCardioRoutine::className(), 'targetAttribute' => ['cardio_routine_id' => 'id']],
            // [['exe_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exercises::className(), 'targetAttribute' => ['exe_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cardio_routine_id' => 'Cardio Routine ID',
            'day_no' => 'Day No',
            'day_time' => 'Day Time'
        ];
    }

    /**
     * Gets query for [[CardioRoutine]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCardioRoutine()
    {
        return $this->hasOne(UserCardioRoutine::className(), ['id' => 'cardio_routine_id']);
    }
}
