<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "plan_for_licenses".
 *
 * @property int $id
 * @property string $google_inapp_id
 * @property string $apple_inapp_id
 * @property int $nos_license
 * @property int $is_active
 */
class PlanForLicenses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_for_licenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['google_inapp_id', 'apple_inapp_id', 'nos_license'], 'required'],
            [['nos_license', 'is_active'], 'integer'],
            [['google_inapp_id', 'apple_inapp_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'google_inapp_id' => 'Google Inapp ID',
            'apple_inapp_id' => 'Apple Inapp ID',
            'nos_license' => 'Nos License',
            'is_active' => 'Is Active',
        ];
    }
}