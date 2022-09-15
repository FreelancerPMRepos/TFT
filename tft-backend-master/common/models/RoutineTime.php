<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "routine_time".
 *
 * @property int $id
 * @property int $routine_id
 * @property int $day_no
 * @property string $day_time
 *
 * @property Routines $routine
 */
class RoutineTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'routine_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['routine_id', 'day_no', 'day_time'], 'required'],
            [['routine_id', 'day_no'], 'integer'],
            [['day_time'], 'string', 'max' => 255],
            [['routine_id'], 'exist', 'skipOnError' => true, 'targetClass' => Routines::className(), 'targetAttribute' => ['routine_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'routine_id' => 'Routine ID',
            'day_no' => 'Day No',
            'day_time' => 'Day Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutine()
    {
        return $this->hasOne(Routines::className(), ['id' => 'routine_id']);
    }
    public function getUserRoutine()
    {
        return $this->hasOne(UserCardioRoutine::className(), ['id' => 'routine_id']);
    }
}
