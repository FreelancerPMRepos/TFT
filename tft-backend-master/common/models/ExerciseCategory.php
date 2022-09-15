<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exe_category".
 *
 * @property int $id
 * @property string $name
 * @property string $img
 */
class ExerciseCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exe_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'img'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
            'img' => 'Img',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\ExerciseCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\ExerciseCategoryQuery(get_called_class());
    }
}
