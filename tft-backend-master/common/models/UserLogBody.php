<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_log_body".
 *
 * @property int $id
 * @property string $body_part
 * @property float $value
 * @property string $value_unit
 
 */
class UserLogBody extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_log_body';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['body_part', 'value'], 'required'],
            [['body_part'], 'string'],
        //    ['body_part', 'in', 'range' => ['Weight','Body Fat','Heart Rate','Waist','Chest','Arms Right','Arms Left','Forearms Right','Forearms Left','Shoulders','Hip','Waist-Hip Ratio (calculated)','Thighs Right','Thighs Left','Calves Right','Calves Left','Neck']],
            [['value'], 'number'],
            [['value_unit'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_log_id' => 'User Log Id',
            'body_part' => 'Body Part',
            'value' => 'Value',
            'value_unit' => 'Value Unit',
        ];
    }

    public function getUserLog()
    {
        return $this->hasOne(UserLog::className(), ['id' => 'user_log_id']);
    }
}
