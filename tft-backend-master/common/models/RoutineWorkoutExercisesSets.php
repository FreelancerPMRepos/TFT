<?php
namespace common\models;

use Yii;

/**
 * This is the model class for table "routine_workout_exercises_sets".
 *
 * @property int $id
 * @property int $reps
 * @property int $routine_workout_exercise_id
 * @property float $weight
 * @property int $lifting_time
 * @property float|null $one_rm
 * @property int $set_completed Only For User Login
 *
 * @property RoutineWorkoutExercises $routineWorkoutExercise
 */
class RoutineWorkoutExercisesSets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'routine_workout_exercises_sets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reps', 'routine_workout_exercise_id', 'weight', 'lifting_time','countdown_timer','time_btn_exe','time_btn_set','no_sets'], 'required'],
            [['reps', 'routine_workout_exercise_id', 'lifting_time', 'set_completed','countdown_timer','time_btn_exe','time_btn_set','no_sets'], 'integer'],
            [['weight', 'one_rm'], 'number'],
            [['routine_workout_exercise_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoutineWorkoutExercises::className(), 'targetAttribute' => ['routine_workout_exercise_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reps' => 'Reps',
            'routine_workout_exercise_id' => 'Routine Workout Exercise ID',
            'weight' => 'Weight',
            'lifting_time' => 'Lifting Time',
            'one_rm' => 'One Rm',
            'set_completed' => 'Set Completed',
        ];
    }

    /**
     * Gets query for [[RoutineWorkoutExercise]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutineWorkoutExercise()
    {
        return $this->hasOne(RoutineWorkoutExercises::className(), ['id' => 'routine_workout_exercise_id']);
    }
}