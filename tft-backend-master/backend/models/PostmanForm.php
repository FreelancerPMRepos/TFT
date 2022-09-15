<?php
namespace app\models;

use Yii;
use yii\base\Model;
use lajax\translatemanager\helpers\Language as Lx;

class PostmanForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $url;
    public $attributes;
    public $method;
    public $user_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['url','method'], 'required'],
            ['user_id','integer']
        ];
    }
}