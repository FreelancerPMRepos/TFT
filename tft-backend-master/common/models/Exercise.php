<?php 
namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "exercises".
 *
 * @property int $id
 * @property int $exe_category_id
 * @property string $name
 * @property string $description
 * @property string $body_parts
 * @property string $steps
 * @property string $instructions
 * @property string $type
 * @property string $record_type
 * @property string $source
 * @property string $img
 * @property string $gif
 * @property int $is_active
 * @property int $created_at
 */
class Exercise extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exercises';
    }

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */

    public $image;
    public $GIF;

    public function rules()
    {
        return [
            [['exe_category_id', 'name'], 'required'],
            [['exe_category_id', 'is_active', 'created_at'], 'integer'],
            [['created_at'], 'safe'],
            [['description', 'body_parts', 'steps', 'instructions', 'type', 'record_type','name','source'], 'string'],
            [['gif'], 'string', 'max' => 255],
            [['image'], 'file','skipOnEmpty' => true, 'extensions' => 'png, jpge, jpg', 'mimeTypes' => 'image/jpeg, image/png, image/jpg'],
            [['image'], 'file','skipOnEmpty' => true, 'extensions' => 'png, jpge, jpg', 'mimeTypes' => 'image/jpeg, image/png, image/jpg','on' => 'scenarioupdate'],
            [['GIF'], 'file','skipOnEmpty' => true, 'extensions' => 'png, jpge, jpg', 'mimeTypes' => 'image/jpeg, image/png, image/jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exe_category_id' => 'Exerice Category',
            'name' => 'Name',
            'description' => 'Description',
            'body_parts' => 'Body Parts',
            'steps' => 'Steps',
            'instructions' => 'Instructions',
            'type' => 'Exercise Type',
            'record_type' => 'Record Type',
            'source' => 'Source',
            'img' => 'Image',
            'gif' => 'GIF',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(ExerciseCategory::className(), ['id' => 'exe_category_id']);
    }
}
