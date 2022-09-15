<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pathways".
 *
 * @property int $id
 * @property string $name
 * @property string $subtext
 * @property string $description
 *
 * @property Routines[] $routines
 */
class Pathways extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pathways';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'subtext', 'description'], 'required'],
            [['name', 'subtext', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'subtext' => 'Subtext',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Routines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutines()
    {
        return $this->hasMany(Routines::className(), ['pathway_id' => 'id']);
    }
}
