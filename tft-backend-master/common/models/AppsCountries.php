<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "apps_countries".
 *
 * @property int $id
 * @property string $country_code
 * @property string $country_name
 * @property int $status
 *
 * @property Video[] $videos
 * @property Winners[] $winners
 */
class AppsCountries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apps_countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['country_code'], 'string', 'max' => 2],
            [['country_name'], 'string', 'max' => 100],
            ['status','safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_code' => 'Country Code',
            'country_name' => 'Country Name',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWinners()
    {
        return $this->hasMany(Winners::className(), ['county_id' => 'id']);
    }
}
