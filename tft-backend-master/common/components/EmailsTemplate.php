<?php
namespace common\components;
use Yii;
use common\models\EmailTemplate;
use yii\base\Component;
/**
 * Class for common EmailTemplate functions
 */
class EmailsTemplate extends Component
{
    public function replace_string_email($array,$slug){
        if($slug != ''){
            $mail_template_array = EmailTemplate::findOne(['email_slug'=>$slug]);
            if(!empty($mail_template_array)){
                $content = $mail_template_array->email_content;
                $word = array();
                $replace_word = array();
                if(!empty($array)){
                    foreach($array as $k=>$d){
                        $word[] =  $k;
                        $replace_word[] = $d;
                    }
                }	
                $mail_message = str_replace($word, $replace_word, $content);
                return $mail_message;
            }
            return true;
        }
        return "";
    }
}
