<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_cardio_routine".
 *
 * @property int $id
 * @property int $user_id
 * @property string $cardio_type
 * @property int $how_many_day_per_week 
 *
 */
class UserCardioRoutine extends \yii\db\ActiveRecord
{
    public $user_routine_day_and_time;
    public $exe_id;
    /**
     * {@inheritdoc}
     */
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
    public static function tableName()
    {
        return 'user_cardio_routine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cardio_type', 'how_many_day_per_week', 'user_routine_day_and_time','name'], 'required'],
            [['cardio_type','name'], 'string'],
            ['cardio_type', 'in', 'range' => ['Steady-State','Interval Training']],
            [['user_id', 'how_many_day_per_week'], 'integer'],
            ['how_many_day_per_week', 'in', 'range' => [1,2,3,4,5,6,7]],
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
            'cardio_type' => 'Cardio Type',
            'how_many_day_per_week' => 'How many Day per Week',
        ];
    }

    /**
     * Gets query for [[RoutineTime]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCardioRoutineTime()
    {
        return $this->hasMany(UserCardioRoutineTime::className(), ['cardio_routine_id' => 'id']);
    }
}
