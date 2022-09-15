<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sport_exe_map".
 *
 * @property int $id
 * @property int $sport_id
 * @property int $exe_id
 * @property string $season
 *
 * @property Exercises $exe
 * @property Sports $sport
 */
class SportExe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sport_exe_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sport_id', 'exe_id'], 'required'],
            [['sport_id', 'exe_id'], 'integer'],
            [['season'], 'string'],
            [['exe_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exercise::className(), 'targetAttribute' => ['exe_id' => 'id']],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sports::className(), 'targetAttribute' => ['sport_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sport_id' => 'Sport ID',
            'exe_id' => 'Exe ID',
            'season' => 'Season',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExe()
    {
        return $this->hasOne(Exercise::className(), ['id' => 'exe_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(Sports::className(), ['id' => 'sport_id']);
    }
}
