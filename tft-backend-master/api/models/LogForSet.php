<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RoutineForm is the model behind the contact form.
 */
class LogForSet extends Model
{
    public $reps;
    public $weight;
    public $lifting_time;
    public $set_id;
    public $set_completed;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['reps', 'weight', 'lifting_time','set_id'], 'required'], 
            ['set_completed','safe']
        ];
    }
   
}
