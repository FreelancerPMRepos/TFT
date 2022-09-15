<?php
namespace common\components;
class Setting extends \yii\web\Request {

    public function val($meta_key,$meta_type="text"){
       $setting =  \common\models\Setting::find()->where(['meta_key'=>$meta_key])->asArray()->one();
       if($meta_type=="select"){
            return !empty($setting['meta_attribute'])?$setting['meta_attribute']:"";
       }else{
            return !empty($setting['meta_value'])?$setting['meta_value']:"";
       }
    }
}
?>