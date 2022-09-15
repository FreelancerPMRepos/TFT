<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "routines".
 *
 * @property int $id
 * @property int $user_id
 * @property int $sport_id
 * @property int $mode
 * @property int $template_id
 * @property string $day
 * @property int $pathway_id
 * @property int $time_between_last_sets
 * @property int $created_at
 *
 * @property Pathways $pathway
 * @property RoutinesWeeks[] $routinesWeeks
 */
class Routines extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'routines';
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
            [['day'], 'string'],
            [['pathway_id', 'time_between_last_sets'], 'required'],
            [['pathway_id', 'time_between_last_sets', 'created_at'], 'integer'],
            ['created_at','safe'],
            [['pathway_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pathways::className(), 'targetAttribute' => ['pathway_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'day' => 'Days Per Week',
            'pathway_id' => 'Pathway Name',
            'time_between_last_sets' => 'Time Between Last Sets',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Pathway]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPathway()
    {
        return $this->hasOne(Pathways::className(), ['id' => 'pathway_id']);
    }
    public function getSport()
    {
        return $this->hasOne(Sports::className(), ['id' => 'sport_id']);
    }

    /**
     * Gets query for [[RoutinesWeeks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutineWorkout()
    {
        return $this->hasMany(RoutineWorkout::className(), ['routine_id' => 'id']);
    }
    public function getRoutinesTimes()
    {
        return $this->hasMany(RoutineTime::className(), ['routine_id' => 'id']);
    }

}
