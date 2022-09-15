<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RoutineForm is the model behind the contact form.
 */
class RoutineForm extends Model
{
    public $pathway;
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
            [['pathway', 'how_many_day_per_week', 'routine_day_and_time'], 'required'],            
            ['how_many_day_per_week', 'in', 'range' => [2,3,4,5]],
            ['pathway', 'in', 'range' => ['PoST','SST','PrST']],
            ['workout_json','safe']
            //['pathway_id','validateRoutineCombination']
        ];
    }
}
