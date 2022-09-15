<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sports".
 *
 * @property int $id
 * @property string $name
 * @property string $images
 * @property int $active
 * @property int $active_for_ssstr
 * @property int $created_at
 */
class Sports extends \yii\db\ActiveRecord
{
    public $sportType;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sports';
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
    public function rules()
    {
        return [
            [['active', 'created_at','active_for_ssstr'], 'integer'],
            [['created_at', 'name', 'images'], 'required'],
            [['name', 'images','sportType'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'images' => 'Images',
            'active' => 'Active',
            'created_at' => 'Created At',
        ];
    }
}
