<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RoutineForm is the model behind the contact form.
 */
class WorkoutForm extends Model
{
    public $exe_id;
    public $exe_category_id;
    public $week_no;
    public $day;
    public $routine_id;
    public $routine_weight_unit;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['exe_id', 'exe_category_id', 'week_no','day','routine_id','routine_weight_unit'], 'required'],    
            [['routine_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Routines::className(), 'targetAttribute' => ['routine_id' => 'id']],
            [['exe_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Exercise::className(), 'targetAttribute' => ['exe_id' => 'id']],     
            
        ];
    }
}
