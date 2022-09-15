<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pathway_exercises".
 *
 * @property int $id
 * @property string $workout
 * @property int $day
 * @property int $exe_id
 * @property int $exe_category_id
 * @property string $exe_category
 * @property string $exe_name
 */
class PathwayExercises extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pathway_exercises';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workout', 'day', 'exe_id', 'exe_category_id', 'exe_category', 'exe_name'], 'required'],
            [['day', 'exe_id', 'exe_category_id'], 'integer'],
            [['workout'], 'string', 'max' => 1],
            [['exe_category', 'exe_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'workout' => 'Workout',
            'day' => 'Day',
            'exe_id' => 'Exe ID',
            'exe_category_id' => 'Exe Category ID',
            'exe_category' => 'Exe Category',
            'exe_name' => 'Exe Name',
        ];
    }
    public function getExeName()
    {
        return $this->hasOne(\common\models\Exercise::className(), ['id' => 'exe_id']);
    }
}
