<?php

namespace common\models;

use yii\base\Model;

class WorkoutSearch extends Model
{
    public $created_at_range;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['created_at_range', 'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'created_at_range' => 'Date',
        ];
    }
}
