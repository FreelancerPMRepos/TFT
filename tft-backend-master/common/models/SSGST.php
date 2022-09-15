<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * RoutineForm is the model behind the contact form.
 */
class SSGST extends Model
{
    public $user_selected_sport_id;
    public $how_many_day_per_week;
    public $routine_day_and_time;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['user_selected_sport_id','how_many_day_per_week'], 'required'],
            [['routine_day_and_time'],'required'],
            ['how_many_day_per_week', 'in', 'range' => [2,3,4,5]],
        ];
    }
}
