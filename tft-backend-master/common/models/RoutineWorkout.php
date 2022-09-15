<?php
namespace common\models;

use Yii;

/**
 * This is the model class for table "routine_workout".
 *
 * @property int $id
 * @property int $week_no
 * @property int $day_no
 * @property int $routine_id
 * @property int $workout_date
 * @property int $status 0=Pending, 1= Started,2= Finished
 *
 * @property Routines $routine
 * @property RoutineWorkoutExercises[] $routineWorkoutExercises
 */
class RoutineWorkout extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'routine_workout';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['week_no', 'day_no', 'routine_id', 'workout_date'], 'required'],
            [['week_no', 'day_no', 'routine_id', 'workout_date', 'status'], 'integer'],
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
            'week_no' => 'Week No',
            'day_no' => 'Day No',
            'routine_id' => 'Routine ID',
            'workout_date' => 'Workout Date',
            'status' => 'status',
        ];
    }

    /**
     * Gets query for [[Routine]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutine()
    {
        return $this->hasOne(Routines::className(), ['id' => 'routine_id']);
    }
    /**
     * Gets query for [[RoutineWorkoutExercises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutineWorkoutExercises()
    {
        return $this->hasMany(RoutineWorkoutExercises::className(), ['routine_workout_id' => 'id']);
    }
    public function getOngoingExes()
    {
        return $this->hasMany(RoutineWorkoutExercises::className(), ['routine_workout_id' => 'id'])
            ->andOnCondition(['routine_workout_exercises.status' => 1]);
    }
}