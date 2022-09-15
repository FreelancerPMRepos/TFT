<?php
namespace app\models;

use Yii;
use yii\base\Model;
use lajax\translatemanager\helpers\Language as Lx;
/** 
 * SetUpProfile form
*/
class SetUpProfile extends Model
{
    public $gender;
    public $date_of_birth;
    public $measurement;
    public $weight;
    public $height;
    public $sports;
    public $height_unit;
    public $weight_unit;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender','date_of_birth','measurement','weight','height','height_unit','weight_unit'],'required'],
            [['weight','height'],'number'],
            ['height_unit', 'in', 'range' => ['in','cm']],
            ['weight_unit', 'in', 'range' => ['lb','kg']],
            ['gender', 'in', 'range' => ['male','female','other']],
            ['measurement', 'in', 'range' => ['lbs/in','kg/cm']],
            [['gender','measurement','date_of_birth'],'string'],
            ['sports','sportValidation'],
            
        ];
    }
    public function sportValidation(){
       if($this->sports){
            foreach ($this->sports as $key => $sport_id) {
                $sportData = \common\models\Sports::find()->where(['id'=>$sport_id])->one();
                if(!$sportData){
                    $this->addError('sports','Invalid sports you have selected');
                    break;
                }                
            }
       }
    }

}