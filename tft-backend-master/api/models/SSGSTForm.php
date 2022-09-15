<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RoutineForm is the model behind the contact form.
 */
class SSGSTForm extends Model
{
    public $user_selected_sport_id;
    public $how_many_day_per_week;
    public $routine_day_and_time;
    public $workout_json;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['user_selected_sport_id','routine_day_and_time','how_many_day_per_week'], 'required'],
            ['how_many_day_per_week', 'in', 'range' => [2,3,4,5]],
            ['workout_json','safe']
        ];
    }
}
