<?php
namespace app\models;

use Yii;
use yii\base\Model;
use lajax\translatemanager\helpers\Language as Lx;

class ImageForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $photo;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['photo'], 'file', 'extensions' => 'png, jpg']
        ];
    }
}