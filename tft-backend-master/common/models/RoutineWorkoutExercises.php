<?php
namespace common\models;

use Yii;

/**
 * This is the model class for table "routine_workout_exercises".
 *
 * @property int $id
 * @property int $routine_workout_id
 * @property int $exe_id
 * @property int $exe_category_id
 * @property int $status 0=Pending, 1= Stated,2= Finished
 *
 * @property RoutineWorkout $routineWorkout
 * @property RoutineWorkoutExercisesSets[] $routineWorkoutExercisesSets
 */
class RoutineWorkoutExercises extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'routine_workout_exercises';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['routine_workout_id', 'exe_id', 'exe_category_id'], 'required'],
            [['routine_workout_id', 'exe_id', 'exe_category_id', 'status'], 'integer'],
            [['routine_workout_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoutineWorkout::className(), 'targetAttribute' => ['routine_workout_id' => 'id']],
            ['routine_id','safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'routine_workout_id' => 'Routine Workout ID',
            'exe_id' => 'Exe ID',
            'exe_category_id' => 'Exe Category ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[RoutineWorkout]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutineWorkout()
    {
        return $this->hasOne(RoutineWorkout::className(), ['id' => 'routine_workout_id']);
    }
    public function getExe()
    {
        return $this->hasOne(Exercise::className(), ['id' => 'exe_id']);
    }
    public function getExeCategory()
    {
        return $this->hasOne(ExerciseCategory::className(), ['id' => 'exe_category_id']);
    }
    
    

    /**
     * Gets query for [[RoutineWorkoutExercisesSets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutineWorkoutExercisesSets()
    {
        return $this->hasMany(RoutineWorkoutExercisesSets::className(), ['routine_workout_exercise_id' => 'id']);
    }
    public function getRoutineWorkoutExercisesSetsUnDone()
    {
        return $this->hasMany(RoutineWorkoutExercisesSets::className(), 
        ['routine_workout_exercise_id' => 'id'])
        ->andOnCondition(['=','routine_workout_exercises_sets.set_completed',0]);
    }
}