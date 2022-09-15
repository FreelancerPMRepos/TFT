<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_log_note".
 *
 * @property int $id
 * @property int $exe_id
 * @property string $notes
 */
class UserLogNote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_log_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_log_id', 'notes'], 'required'],
            [['user_log_id'], 'integer'],
            [['notes'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_log_id' => 'User Log Id',
            'exe_id' => 'Exercise',
            'notes' => 'Notes',
        ];
    }

    public function getUserLog()
    {
        return $this->hasOne(UserLog::className(), ['id' => 'user_log_id']);
    }

    public function getExercise()
    {
        return $this->hasOne(Exercise::className(), ['id' => 'exe_id']);
    }
}
