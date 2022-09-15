<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_additional_info".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $photo
 * @property string|null $thum_photo
 * @property string|null $date_of_birth
 * @property string $gender
 * @property string $units_of_measurement
 * @property float|null $height
 * @property string $height_unit
 * @property float|null $weight
 * @property string $weight_unit
 * @property string|null $sports_interest
 * @property string|null $address
 * @property string|null $contact_data
 *
 * @property User $user
 */
class UserAdditionalInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_additional_info';
    }

    public $img;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','stripe_customer_id','lbw','bmi'], 'safe'],
            [['user_id'], 'integer'],
            [['photo', 'thum_photo', 'gender', 'units_of_measurement', 'height_unit', 'weight_unit'], 'string'],
            [['date_of_birth'], 'safe'],
            [['height', 'weight','lbw','bmi'], 'number'],
            [['sports_interest','contact_data','address','stripe_customer_id'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['img'], 'file','skipOnEmpty' => true, 'extensions' => 'png, jpge, jpg', 'mimeTypes' => 'image/jpeg, image/png, image/jpe'],
            [['img'], 'file','skipOnEmpty' => true, 'extensions' => 'png, jpge, jpg', 'mimeTypes' => 'image/jpeg, image/png, image/jpe', 'on' => 'update'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    public function fields()
    {
       
        return [
            "id",
            "user_id",
            'photo' => function ($model) {
                $d = !empty($model->photo)?\yii\helpers\Url::base(true).'/img_assets/users/'.$model->photo:"";
                return $d;
            },
            "thum_photo",
            "date_of_birth",
            "gender",
            "units_of_measurement",
            "height",
            "height_unit",
            "weight",
            "weight_unit",
            "sports_interest",
            "address",
            "contact_data",
            'stripe_customer_id',
            'lbw' => function ($model) {
                $d = \Yii::$app->general->bmi($model->weight,$model->weight_unit,$model->height,$model->height_unit,$model->gender,"lbw");
                return $d;
            },
            'bmi' => function ($model) {
                $d = \Yii::$app->general->bmi($model->weight,$model->weight_unit,$model->height,$model->height_unit,$model->gender,"bmi");
                return $d;
            },
        ];
    }
   

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'photo' => 'Photo',
            'thum_photo' => 'Thum Photo',
            'date_of_birth' => 'Date Of Birth',
            'gender' => 'Gender',
            'units_of_measurement' => 'Units Of Measurement',
            'height' => 'Height',
            'height_unit' => 'Height Unit',
            'weight' => 'Weight',
            'weight_unit' => 'Weight Unit',
            'sports_interest' => 'Sports Interest',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $user                     = \common\models\User::find()->where(['id'=>$this->user_id])->one();
            $this->stripe_customer_id = \Yii::$app->stripePayment->createCustomer($user->username,$user->email);
            return true;
        } else {
            return false;
        }
    }
}
