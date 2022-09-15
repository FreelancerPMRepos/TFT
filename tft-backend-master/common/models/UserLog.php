<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $log_type
 * @property double $log_date
 */
class UserLog extends \yii\db\ActiveRecord
{
    public $notes;
    public $exe_id;
    public $body_part;
    public $value;
    public $value_unit;
    public $photo;
    public $tag;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_type', 'user_id', 'log_date'], 'required'],
            [['user_id','exe_id'], 'integer'],
            [['value'], 'number'],
            ['log_type', 'in', 'range' => ['training', 'image', 'notes', 'body']],
            [['log_type','notes', 'body_part', 'value_unit', 'tag'], 'string'],
            [['log_date'], 'safe'],
        ];
    }   
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User Id',
            'log_type' => 'Log Type',
            'log_date' => 'Log Date',
        ];
    }
}
