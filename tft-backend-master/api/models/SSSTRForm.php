<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RoutineForm is the model behind the contact form.
 */
class SSSTRForm extends Model
{
    public $user_selected_sport_id;
    public $user_selected_season;
    public $how_many_day_per_week = 3;
    public $user_selected_start = "Regular";
    public $routine_day_and_time;
    public $workout_json;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['user_selected_sport_id', 'user_selected_season','routine_day_and_time','how_many_day_per_week'], 'required'],
            ['how_many_day_per_week','integer'],
            ['workout_json','safe']
        ];
    }
}
